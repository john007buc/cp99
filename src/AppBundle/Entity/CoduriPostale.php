<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class City
 * @package AdminBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="coduripostale")
 */
class CoduriPostale
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="judet", type="string",length=60)
     */
    protected $judet;

    /**
     * @var string
     * @ORM\Column(name="localitate", type="string",length=100)
     */
    protected $localitate;

    /**
     * @var string
     * @ORM\Column(name="tip_artera", type="string",length=50)
     */
    protected $tip_artera;

    /**
     * @var string
     * @ORM\Column(name="denumire_artera", type="string",length=100)
     */
    protected $denumire_artera;

    /**
     * @var string
     * @ORM\Column(name="numar", type="string",length=100)
     */

    protected $numar;

    /**
     * @var string
     * @ORM\Column(name="codpostal", type="string",length=30)
     */
    protected $codpostal;

    /**
     * @var string
     * @ORM\Column(name="slug",type="string",length=100,nullable=true)
     */
    protected $slug;

    /**
     * @var string
     * @ORM\Column(name="street_slug",type="string",nullable=true)
     */
    protected $street_slug;




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
     * Set judet
     *
     * @param string $judet
     *
     * @return CoduriPostale
     */
    public function setJudet($judet)
    {
        $this->judet = $judet;

        return $this;
    }

    /**
     * Get judet
     *
     * @return string
     */
    public function getJudet()
    {
        return $this->judet;
    }

    /**
     * Set localitate
     *
     * @param string $localitate
     *
     * @return CoduriPostale
     */
    public function setLocalitate($localitate)
    {
        $this->localitate = $localitate;

        return $this;
    }

    /**
     * Get localitate
     *
     * @return string
     */
    public function getLocalitate()
    {
        return $this->localitate;
    }

    /**
     * Set tipArtera
     *
     * @param string $tipArtera
     *
     * @return CoduriPostale
     */
    public function setTipArtera($tipArtera)
    {
        $this->tip_artera = $tipArtera;

        return $this;
    }

    /**
     * Get tipArtera
     *
     * @return string
     */
    public function getTipArtera()
    {
        return $this->tip_artera;
    }

    /**
     * Set denumireArtera
     *
     * @param string $denumireArtera
     *
     * @return CoduriPostale
     */
    public function setDenumireArtera($denumireArtera)
    {
        $this->denumire_artera = $denumireArtera;

        return $this;
    }

    /**
     * Get denumireArtera
     *
     * @return string
     */
    public function getDenumireArtera()
    {
        return $this->denumire_artera;
    }

    /**
     * Set numar
     *
     * @param string $numar
     *
     * @return CoduriPostale
     */
    public function setNumar($numar)
    {
        $this->numar = $numar;

        return $this;
    }

    /**
     * Get numar
     *
     * @return string
     */
    public function getNumar()
    {
        return $this->numar;
    }

    /**
     * Set codpostal
     *
     * @param string $codpostal
     *
     * @return CoduriPostale
     */
    public function setCodpostal($codpostal)
    {
        $this->codpostal = $codpostal;

        return $this;
    }

    /**
     * Get codpostal
     *
     * @return string
     */
    public function getCodpostal()
    {
        return $this->codpostal;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return CoduriPostale
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function __toString()
    {

        //slug with street number
        //$address=[$this->getTipArtera(),$this->getDenumireArtera(),$this->getNumar(),$this->getLocalitate(),$this->getJudet()];

        //street slug without number
        $address=[$this->getTipArtera(),$this->getDenumireArtera(),$this->getLocalitate(),$this->getJudet()];
        $address=implode("-",$address);
        $address=trim($address,"- ");
        $address=str_replace([",",".",";"," "],["","","","-"],$address);
        return $address;
    }

    /**
     * Set streetSlug
     *
     * @param string $streetSlug
     *
     * @return CoduriPostale
     */
    public function setStreetSlug($streetSlug)
    {
        $this->street_slug = $streetSlug;

        return $this;
    }

    /**
     * Get streetSlug
     *
     * @return string
     */
    public function getStreetSlug()
    {
        return $this->street_slug;
    }
}
