<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evolution
 *
 * @ORM\Table(name="evolution")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EvolutionRepository")
 */
class Evolution
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
     * @ORM\Column(name="weight", type="string", length=5)
     */
    private $weight;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="date")
     */
    private $time;

    /**
     * @var \AppBundle\Entity\Profile
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="evolutions")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    private $profile;


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
     * Set weight
     *
     * @param string $weight
     *
     * @return Evolution
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set time
     *
     * @param \Date $time
     *
     * @return Evolution
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \Date
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set profile
     *
     * @param \AppBundle\Entity\Profile $profile
     *
     * @return Evolution
     */
    public function setProfile(\AppBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \AppBundle\Entity\Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
