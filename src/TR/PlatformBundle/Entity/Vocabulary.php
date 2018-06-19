<?php

namespace TR\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Vocabulary
 *
 * @ORM\Table(name="vocabulary")
 * @ORM\Entity(repositoryClass="TR\PlatformBundle\Repository\VocabularyRepository")
 */
class Vocabulary
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="english", type="string", length=255, unique=true)
     */
    private $english;

    /**
     * @var string
     *
     * @ORM\Column(name="french", type="string", length=255, unique=true)
     */
    private $french;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var boolean
     *
     * @ORM\Column(name="favorite", type="boolean")
     */
    private $favorite = false;

    /**
     * @var string
     *
     * @ORM\Column(name="examples", type="string", length=255, nullable=true)
     */
    private $examples;



    public function __construct($english, $french, $examples=null)
    {
        $this->dateCreation = new \DateTime();
        $this->english = $english;
        $this->french = $french;
        $this->examples = $examples;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set english
     *
     * @param string $english
     *
     * @return Vocabulary
     */
    public function setEnglish($english)
    {
        $this->english = $english;

        return $this;
    }

    /**
     * Get english
     *
     * @return string
     */
    public function getEnglish()
    {
        return $this->english;
    }

    /**
     * Set french
     *
     * @param string $french
     *
     * @return Vocabulary
     */
    public function setFrench($french)
    {
        $this->french = $french;

        return $this;
    }

    /**
     * Get french
     *
     * @return string
     */
    public function getFrench()
    {
        return $this->french;
    }

    /**
     * Set examples
     *
     * @param string $examples
     *
     * @return Vocabulary
     */
    public function setExamples($examples)
    {
        $this->examples = $examples;

        return $this;
    }

    /**
     * Get examples
     *
     * @return string
     */
    public function getExamples()
    {
        return $this->examples;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Vocabulary
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set favorite
     *
     * @param boolean $favorite
     *
     * @return Vocabulary
     */
    public function setFavorite($favorite)
    {
        $this->favorite = $favorite;

        return $this;
    }

    /**
     * Get favorite
     *
     * @return boolean
     */
    public function getFavorite()
    {
        return $this->favorite;
    }
}
