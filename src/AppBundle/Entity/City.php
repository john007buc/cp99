<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class City
 * @package AdminBundle\Entity
 * @ORM\Entity
 * @ORM\Table
 */
class City
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     *
     * @ORM\ManyToOne(targetEntity="County", inversedBy="cities")
     *
     */
    protected $county;


    /**
     * @var bigint
     * @ORM\Column(type="bigint",nullable=true)
     */
    protected $siruta;

    /**
     * @var decimal(18,16)
     * @ORM\Column(type="decimal", precision=18, scale=16, nullable=true)
     */
    protected $longitude;


    /**
     * @var decimal(18,16)
     * @ORM\Column(type="decimal", precision=18, scale=16, nullable=true)
     */
    protected $latitude;


    /**
     * @var string
     * @Assert\Length(max=264)
     * @ORM\Column(type="string", length=64)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $region;

    

   public function __construct()
   {

   }

   public function __toString()
   {
       return $this->getName();
   }




    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set siruta
     *
     * @param integer $siruta
     *
     * @return City
     */
    public function setSiruta($siruta)
    {
        $this->siruta = $siruta;

        return $this;
    }

    /**
     * Get siruta
     *
     * @return integer
     */
    public function getSiruta()
    {
        return $this->siruta;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return City
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return City
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return City
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return City
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set county
     *
     * @param \AppBundle\Entity\County $county
     *
     * @return City
     */
    public function setCounty(\AppBundle\Entity\County $county = null)
    {
        $this->county = $county;

        return $this;
    }

    /**
     * Get county
     *
     * @return \AppBundle\Entity\County
     */
    public function getCounty()
    {
        return $this->county;
    }


}
