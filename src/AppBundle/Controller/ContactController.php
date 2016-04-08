<?php
namespace AppBundle\Controller;

use AppBundle\Events\EmailEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use AppBundle\MyClasses\Captcha;

class ContactController extends Controller{


    /**
     * @Route("/contact",name="contact")
     */
    public function indexAction(Request $request)
    {


        $contactForm=$this->createFormBuilder()
            ->setAction($this->generateUrl("contact"))
            ->setMethod("POST")
            ->setData(array(
                "name"=>"Nume",
                "email"=>"Email",
                "subject"=>"Mesajul tau",
                "security_code"=>"Cod securitate"
            ))
            ->add('name','text',array(
                'constraints'=>array(
                    new NotBlank(),
                    new Length([
                        "min"=>2,
                        "max"=>10
                    ])
                )
            ))
            ->add('email','text',array(
                'constraints'=>array(
                    new NotBlank(),
                    new Email(),
                    new Length([
                        "max"=>30
                    ])
                )
            ))
            ->add('subject','textarea',array(
                'constraints'=>array(
                    new NotBlank(),
                    new Length([
                        "min"=>10,
                        "max"=>200
                    ])
                )
            ))
            ->add("security_code",'text',array(
                'constraints'=>array(
                    new NotBlank(),
                    new Length([
                        "max"=>4
                    ])
                )
            ))
            ->add('save','submit')

            ->getForm();

        $contactForm->handleRequest($request);

        if($contactForm->isSubmitted() and !$this->isCaptchaValid($contactForm)){
            $contactForm->get("security_code")->addError(new FormError("Sigur nu esti robot?"));
        }

        if($contactForm->isValid()){
            //send email

            $emailEvent=new EmailEvent($contactForm->getData());

            $this->get("event_dispatcher")->dispatch('email.send',$emailEvent);

        }


        return $this->render('contact/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'form'=>$contactForm->createView()
        ));

    }

    /**
     * @Route("/captcha/{random}",name="captcha_route",defaults={
     * "random"=1
     * })
     */
    public function captchaAction(Request $request)
    {
        /*$session=$this->get('session');
        $captcha= Captcha::generate($session);*/

        $captcha=$this->get("mycaptcha")->getImageIdentifier();

        ob_start();
        imagejpeg($captcha);
        imagedestroy($captcha);
        $secure_img=ob_get_clean();


        $response=new Response($secure_img);
        $response->headers->set("Content-Type","image/jpg");
        $response->send();

    }

    /**
     * Verigy if captcha text is valid
     * @param $form
     * @return bool
     */
    public function isCaptchaValid($form)
    {
        $session_code=$this->get('session')->get('captcha');
        $form_code=$form->get('security_code')->getData();
        //remove security code from session
        $this->get('session')->remove("captcha");

       // dump($form_code."-".$session_code);exit();
        return ($session_code==$form_code)?true:false;

    }

    public function testAction()
    {
        echo "test";
    }



}