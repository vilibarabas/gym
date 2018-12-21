<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Profile
 *
 * @ORM\Table(name="profile")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProfileRepository")
 */
class Profile
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
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")

     */
    private $user_id;

    /**
     * @var \AppBundle\Entity\Evolution
     * @ORM\OneToMany(targetEntity="Evolution", mappedBy="profile")
     */
    private $evolutions;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=30)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=30)
     */
    private $lastName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birtday", type="date")
     */
    private $birtday;

    /**
     * @var int
     *
     * @ORM\Column(name="height", type="integer")
     */
    private $height;

    /**
     * @var string
     *
     * @ORM\Column(name="gen", type="string", length=1)
     */
    private $gen;


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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Profile
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Profile
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return Profile
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set gen
     *
     * @param string $gen
     *
     * @return Profile
     */
    public function setGen($gen)
    {
        $this->gen = $gen;

        return $this;
    }

    /**
     * Get gen
     *
     * @return string
     */
    public function getGen()
    {
        return $this->gen;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->evolutions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set userId
     *
     *
     * @return Profile
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Add evolution
     *
     * @param \AppBundle\Entity\Evolution $evolution
     *
     * @return Profile
     */
    public function addEvolution(\AppBundle\Entity\Evolution $evolution)
    {
        $this->evolutions[] = $evolution;

        return $this;
    }

    /**
     * Remove evolution
     *
     * @param \AppBundle\Entity\Evolution $evolution
     */
    public function removeEvolution(\AppBundle\Entity\Evolution $evolution)
    {
        $this->evolutions->removeElement($evolution);
    }

    /**
     * Get evolutions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvolutions()
    {
        return $this->evolutions;
    }

    public function __toString()
    {
        if(!$this->getUserId())
            return '';
        return (string) $this->getUserId();
    }

    /**
     * Set birtday.
     *
     * @param \DateTime $birtday
     *
     * @return Profile
     */
    public function setBirtday($birtday)
    {
        $this->birtday = $birtday;

        return $this;
    }

    /**
     * Get birtday.
     *
     * @return \DateTime
     */
    public function getBirtday()
    {
        return $this->birtday;
    }

    public function getAge() {
        $current_year = new \DateTime();
        $birtDay = $this->getBirtDay();
        $yearsOld = $current_year->diff($birtDay)->y;

        return $yearsOld;
    }
}
