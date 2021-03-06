<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LifeStyle
 *
 * @ORM\Table(name="life_style")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LifeStyleRepository")
 */
class LifeStyle
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
     * @ORM\Column(name="life_style_select", type="string", length=50, nullable=true)
     */
    private $lifeStyleSelect;

    /**
     * @var \AppBundle\Entity\PersonalProgram
     * @ORM\ManyToOne(targetEntity="PersonalProgram", inversedBy="lifeStyle")
     * @ORM\JoinColumn(name="personal_program_id", referencedColumnName="id")
     */
    private $personalProgram;

    /**
     * @var \AppBundle\Entity\Food
     * Many User have Many Phonenumbers.
     * @ORM\ManyToMany(targetEntity="Food")
     * @ORM\JoinTable(name="life_style_foods",
     *      joinColumns={@ORM\JoinColumn(name="life_style_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="food_id", referencedColumnName="id")}
     *      )
     */
    private $foods;


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
        $this->foods = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set personalProgram.
     *
     * @param \AppBundle\Entity\PersonalProgram|null $personalProgram
     *
     * @return LifeStyle
     */
    public function setPersonalProgram(\AppBundle\Entity\PersonalProgram $personalProgram = null)
    {
        $this->personalProgram = $personalProgram;

        return $this;
    }

    /**
     * Get personalProgram.
     *
     * @return \AppBundle\Entity\PersonalProgram|null
     */
    public function getPersonalProgram()
    {
        return $this->personalProgram;
    }

    /**
     * Add food.
     *
     * @param \AppBundle\Entity\Food $food
     *
     * @return LifeStyle
     */
    public function addFood(\AppBundle\Entity\Food $food)
    {
        $this->foods[] = $food;

        return $this;
    }

    /**
     * Remove food.
     *
     * @param \AppBundle\Entity\PersonalProgram $food
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeFood(\AppBundle\Entity\PersonalProgram $food)
    {
        return $this->foods->removeElement($food);
    }

    /**
     * Get foods.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFoods()
    {
        return $this->foods;
    }

    /**
     * Set lifeStyleSelect.
     *
     * @param string $lifeStyleSelect
     *
     * @return LifeStyle
     */
    public function setLifeStyleSelect($lifeStyleSelect)
    {
        $this->lifeStyleSelect = $lifeStyleSelect;

        return $this;
    }

    /**
     * Get lifeStyleSelect.
     *
     * @return string
     */
    public function getLifeStyleSelect()
    {
        return $this->lifeStyleSelect;
    }
}
