<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;


/**
 * Class DefaultController
 * @package AppBundle\Controller
 *
 */
class DefaultController extends Controller
{
    /**
     * @Route("/slug/{nr}",name="slug")
     */
    public function slugAction($nr)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $judete = $em->getRepository("AppBundle:County")->findAll();
        $inc = 0;
        $coduri_postale = $em->getRepository('AppBundle:CoduriPostale')->findByJudet($judete[$nr]->getName());

        foreach ($coduri_postale as $cp) {
                $inc++;
                $cp->setStreetSlug($cp->__toString());
                $em->persist($cp);
            }
            $em->flush();

        return $this->render('default/slug.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'inc'=>$inc
        ));

    }

    /**
     * @Route("/ajax_request_007/",name="ajaxAction")
     */
    public function ajaxAction(Request $request)
    {

       $searchTerm = $request->request->get("address");
       $page = $request->request->get("page");
       $cp_amount=$this->container->getParameter('cp.per_page');

        /*--------------------------------------------------------------------*/
        /*       Set $searchTerm for testing postal code in browser url       */
        /*--------------------------------------------------------------------*/

       // $searchTerm = "Strada Tineretului, Giurgiu, Județul Giurgiu, România";
        //$searchTerm="Fainari, București, Municipiul București, România";
       // $page=1;
        //$cp_amount=10;


        $finder = $this->container->get('fos_elastica.finder.app.cp');
        $boolQuery = new \Elastica\Query\BoolQuery();

        $chunks=explode(",",$searchTerm);
        //remove last element from search string (country)
        if(trim(end($chunks))==="România" || trim(end($chunks))==="Romania" ){
            array_pop($chunks);
        }
        $count=count($chunks);

        if($count===1){

            /*--------------------------------------------------------------------*/
            /*       Get postal codes for localities with same name               */
            /*--------------------------------------------------------------------*/

            $cityQuery = new \Elastica\Query\Match();
            $cityQuery->setFieldQuery('localitate', $chunks[0]);
            $boolQuery->addMust($cityQuery);

        }elseif($count==2){

            /*--------------------------------------------------------------------*/
            /*      Get postal code for locality in county                        */
            /*--------------------------------------------------------------------*/

            $cityQuery = new \Elastica\Query\Match();
            $cityQuery->setFieldQuery('localitate', $chunks[0]);
            $boolQuery->addMust($cityQuery);

            $countyQuery = new \Elastica\Query\Match();
            $countyQuery->setFieldQuery('judet', $chunks[1]);
            $boolQuery->addMust($countyQuery);


        }elseif($count==3){

            /*--------------------------------------------------------------------*/
            /*      Get postal code for street in the city,county                 */
            /*--------------------------------------------------------------------*/

            $streetQuery = new \Elastica\Query\Match();
            $streetQuery->setFieldQuery('denumire_artera', $chunks[0]);
            $boolQuery->addMust($streetQuery);

            $cityQuery = new \Elastica\Query\Match();
            if(strpos($chunks[1],'Bucure')){
                /*For Bucharest set field query in Judet column. The Localitate column is associated with Bucharest's sectors*/
                $cityQuery->setFieldQuery('judet',  $chunks[1]);

            }else{
                $cityQuery->setFieldQuery('localitate',  $chunks[1]);
            }

            $boolQuery->addMust($cityQuery);

        }else{
           die();
        }


        //$nameQuery->setFieldParam('denumire_artera', 'analyzer', 'default_search');

        //$keywordsQuery = new \Elastica\Query\Match();
        //$keywordsQuery->setFieldQuery('judet', "Bucuresti");
        //$keywordsQuery->setFieldParam('localitate', 'analyzer', 'standard');

        //Strada Bucureşti, Cartojani, Giurgiu, România

       /* $countyQuery = new \Elastica\Query\Match();
        $countyQuery->setFieldQuery('judet', $county);
        $boolQuery->addMust($countyQuery);*/


        $adapter=$finder->createPaginatorAdapter($boolQuery);
        $nr=$adapter->getTotalHits();

        $results= $adapter->getResults(($page-1)*$cp_amount, $cp_amount);
        $data=$results->toArray();
        $data[]=['total_results'=>$nr];


        $serializer = $this->get('jms_serializer');
        $serialized_data=$serializer->serialize($data,'json');
       // dump($serialized_data);exit();
        $response= new Response($serialized_data);
        $response->headers->set("Content-Type","application/json");
        return $response;
    }

    /**
     *
     * @Route("/{county}", requirements={
     * "county":"(\w+)([\-\s])*(\w)*"
     *
     * },name="home"
     * )
     */
    public function indexAction($county=null,Request $request)
    {

        if($county!==null)
        {
            $em=$this->getDoctrine()->getManager();
            $query =  $em->createQuery(
                'SELECT o, j FROM AppBundle:City o  JOIN o.county j WHERE j.name = :county'
            )->setParameter('county', $county);

            $cities=$this->getPaginator($query,$request);
            // parameters to template
            return $this->render('default/index.html.twig', array(
                'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
                'county'=>$county,
                'cities' => $cities
            ));
        }

        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'counties'=>""
        ));
    }

    /**
     * @param $query
     * @param $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface|\Knp\Component\Pager\Paginator
     */
    public function getPaginator($query,$request)
    {
        $paginator  = $this->get('knp_paginator');
        $paginator = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            20/*limit per page*/
        );

        return $paginator;
    }

    /**
     * @Route("/coduri-postale/{county}/{city}",name="coduri_postale")
     *
     */

    public function getCodesAction(Request $request,$county,$city)
    {

    //[A-Za-z0-9ăĂâÂîÎşŞţŢ\-\s]

        $em=$this->getDoctrine()->getManager();
        if($county==='Bucuresti')
        {
            $query = $em->createQuery('SELECT cp FROM AppBundle:CoduriPostale cp WHERE cp.judet = :county')
                ->setParameter('county',$county);
        }else{
            $query = $em->createQuery('SELECT cp FROM AppBundle:CoduriPostale cp WHERE cp.judet = :county and cp.localitate=:city')
                ->setParameters(['county'=>$county,'city'=>$city]);
        }


        $results=$this->getPaginator($query,$request);


        return $this->render('default/get_codes.html.twig',array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'results'=>$results,
            'city'=>$city,
            'county'=>$county
        ));
    }

    /**
     *
     * @Route("cod-postal-{slug}", name="cod_postal")
     */
    public function getCodeAction($slug)
    {
        $repo=$this->getDoctrine()->getRepository("AppBundle:CoduriPostale");
        $cp = $repo->findOneBy(['slug'=>$slug]);

        $street_slug=$cp->getStreetSlug();

        $codes_on_street=$repo->findBy(['street_slug'=>$street_slug]);



       // dump( $codes_on_street);exit();

        return $this->render('default/get_code.html.twig',array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'result'=>$cp,
            'codes_on_street'=>$codes_on_street
        ));
    }

    /**
     * Cauta localitate dupa cod postal
     * @Route("cauta-dupa-cod-postal-{cod_postal}",name="cauta_dupa_cod_postal")
     */
    public function getAddressByCodeAction(Request $request,$cod_postal=null)
    {

        $address=null;
        $rep=$this->getDoctrine()->getRepository("AppBundle:CoduriPostale");
        $form=$this->createSearchForm('cauta_dupa_cod_postal','Cauta dupa cod postal');
        $form->handleRequest($request);

        if($cod_postal !==null){
            $address=$rep->findOneByCodpostal($cod_postal);
        }elseif($form->isValid())
        {
            $cod_postal=$form->get('cod')->getData();
            $address=$rep->findOneByCodpostal($cod_postal);
        }

        return $this->render('default/get_address_by_code.html.twig',array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'form'=>$form->createView(),
            'address'=>$address
        ));
    }

    /**
     * @param Request $request
     * @param null $siruta
     * @return Response
     * @Route("cauta-dupa-siruta-{siruta}",name="cauta_dupa_siruta")
     */
    public function getAdressBySirutaAction(Request $request,$siruta=null)
    {

        $city=null;
        $rep=$this->getDoctrine()->getRepository("AppBundle:City");
        $form=$this->createSearchForm('cauta_dupa_siruta','Introdu SIRUTA');
        $form->handleRequest($request);

        if($siruta!==null){
            $city=$rep->findOneBySiruta($siruta);

        }elseif($form->isValid())
        {
            $siruta=$form->get("cod")->getData();
            $city=$rep->findOneBySiruta($siruta);


        }
        return $this->render('default/get_city_by_siruta.html.twig',array(
            'base_dir'=>realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'form'=>$form->createView(),
            'city'=>$city

        ));
    }

    /**
     * @param Request $request
     * @param null $lat
     * @param null $long
     * @return Response
     * @Route("latitudine-longitudine-{lat}-{long}",name="latlong")
     */
    public function getCityByLatLongAction(Request $request,$lat=null,$long=null)
    {
        $rep=$this->getDoctrine()->getRepository("AppBundle:City");
        $form=$this->createSearchForm('latlong','Introdu lat si long separate prin ;');
        $form->handleRequest($request);

       if($form->isValid())
        {
            list($lat,$long)=explode(";",$form->get('code')->getData());
        }

        if($lat !==null && $long!==null){
            $city=$rep->findOneBy([
                'latitude'=>$lat,
                'longitude'=>$long
            ]);
        }else{
            $city=null;
        }

        return $this->render('default/get_city_by_siruta.html.twig',array(
            'base_dir'=>realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'form'=>$form->createView(),
            'city'=>$city

        ));
    }

    /**
     * @param $action
     * @param $placeholder
     * @return \Symfony\Component\Form\Form
     */
    public function createSearchForm($action,$placeholder)
    {
        $form = $this->createFormBuilder()
            ->setMethod("POST")
            ->setAction($this->generateUrl($action))
            ->add('cod','text',array(
                'attr'=>array(
                    'placeholder'=>$placeholder
                ),
                'constraints'=>array(
                    new NotBlank(),
                    new Length([
                        'min'=>4,
                        'max'=>8
                    ])

                )

            ))->add('Cauta','submit')
            ->getForm();

        return $form;
    }






}
