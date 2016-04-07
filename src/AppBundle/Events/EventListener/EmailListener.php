<?php
namespace AppBundle\Events\EventListener;

use AppBundle\Events\EmailEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EmailListener
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
         $this->container=$container;
    }

    public function sendEmail(EmailEvent $evt)
    {


        $message = \Swift_Message::newInstance()
            ->setSubject('Mesaj coduripostale99.ro')
            ->setFrom('admin@coduripostale99.ro')
            ->setTo('john007buc@yahoo.com')
            ->setBody(
                $this->container->get('templating')->render("email/email.html.twig",array(
                    'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
                    'user'=>$evt->getName(),
                    'email'=>$evt->getEmail(),
                    'message'=>$evt->getSubject()
                )),'text/html'
            );

        $this->container->get("mailer")->send($message);

    }



}