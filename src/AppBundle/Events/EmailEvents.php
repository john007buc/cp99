<?php
namespace AppBundle\Events;

final class EmailEvents
{

    /**
     * email.send is called every time a user press "send" in contact form
     * Listener will receive an instance of EmailEvent
     */
    const SEND_EMAIL="email.send";

}