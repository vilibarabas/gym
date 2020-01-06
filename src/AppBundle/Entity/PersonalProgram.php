<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PersonalProgram
 *
 * @ORM\Table(name="personal_program")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonalProgramRepository")
 */
class PersonalProgram
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
     * One Product has One Shipment.
     * @ORM\OneToOne(targetEntity="\AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_ref_id", referencedColumnName="id")
     */
    private $userRefId;

    /**
     * @var \AppBundle\Entity\LifeStyle
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="LifeStyle", mappedBy="personalProgram", cascade={"remove"}, orphanRemoval=true)
     */

    private $lifeStyle;

    /**
     * @var datetime $created
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lifeStyle = new \Doctrine\Common\Collections\ArrayCollection();
        $this->updatedTimestamps();
    }

    private function updatedTimestamps()
    {
        if($this->getCreated() == null)
        {
            $this->setCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }


    /**
     * Add lifeStyle.
     *
     * @param \AppBundle\Entity\LifeStyle $lifeStyle
     *
     * @return PersonalProgram
     */
    public function addLifeStyle(\AppBundle\Entity\LifeStyle $lifeStyle)
    {
        $this->lifeStyle[] = $lifeStyle;

        return $this;
    }

    /**
     * Remove lifeStyle.
     *
     * @param \AppBundle\Entity\LifeStyle $lifeStyle
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeLifeStyle(\AppBundle\Entity\LifeStyle $lifeStyle)
    {
        return $this->lifeStyle->removeElement($lifeStyle);
    }

    /**
     * Get lifeStyle.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLifeStyle()
    {
        return $this->lifeStyle;
    }



    /**
     * Set created.
     *
     * @param \DateTime|null $created
     *
     * @return PersonalProgram
     */
    public function setCreated($created = null)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime|null
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set userRefId.
     *
     * @param \AppBundle\Entity\User|null $userRefId
     *
     * @return PersonalProgram
     */
    public function setUserRefId(\AppBundle\Entity\User $userRefId = null)
    {
        $this->userRefId = $userRefId;

        return $this;
    }

    /**
     * Get userRefId.
     *
     * @return \AppBundle\Entity\User|null
     */
    public function getUserRefId()
    {
        return $this->userRefId;
    }
}
