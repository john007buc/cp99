<?php
namespace AppBundle\Events;


use Symfony\Component\EventDispatcher\Event;

class EmailEvent extends Event
{
    protected $name;
    protected $email;
    protected $subject;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->name=$data["name"];
        $this->email=$data["email"];
        $this->subject=$data["subject"];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;

    }

    /**
     * @return string
     */

    public function getSubject()
    {
        return $this->subject;
    }




}