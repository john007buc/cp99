<?php
namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as  ORM;
use Symfony\Component\Validator\Constraint as Assert;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Class County
 * @ORM\Entity
 * @ORM\Table(name="county")
 */
class County
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $code;


    /**
     * @var string
     * @ORM\Column(type="string",length=50)
     *
     */
    protected $name;



    /**
     * @var AppBundle\Entity\City
     * @ORM\OneToMany(targetEntity="City", mappedBy="county")
     */
    protected $cities;



    public function __construct()
    {

        $this->cities=new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return County
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



    public function __toString()
    {
        return $this->name;
    }



    /**
     * Set code
     *
     * @param string $code
     *
     * @return County
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Add city
     *
     * @param \AppBundle\Entity\City $city
     *
     * @return County
     */
    public function addCity(\AppBundle\Entity\City $city)
    {
        $this->cities[] = $city;

        return $this;
    }

    /**
     * Remove city
     *
     * @param \AppBundle\Entity\City $city
     */
    public function removeCity(\AppBundle\Entity\City $city)
    {
        $this->cities->removeElement($city);
    }

    /**
     * Get cities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCities()
    {
        return $this->cities;
    }

}
