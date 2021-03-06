<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FoodCategory
 *
 * @ORM\Table(name="food_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FoodCategoryRepository")
 */
class FoodCategory
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
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="sort", type="integer")
     */
    private $sort;

    /**
     * @var \AppBundle\Entity\Food
     * @ORM\OneToMany(targetEntity="Food", mappedBy="category")
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
     * Set name.
     *
     * @param string $name
     *
     * @return FoodCategory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->foods = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add food.
     *
     * @param \AppBundle\Entity\Food $food
     *
     * @return FoodCategory
     */
    public function addFood(\AppBundle\Entity\Food $food)
    {
        $this->foods[] = $food;

        return $this;
    }

    /**
     * Remove food.
     *
     * @param \AppBundle\Entity\Food $food
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeFood(\AppBundle\Entity\Food $food)
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

    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * Set sort.
     *
     * @param int $sort
     *
     * @return FoodCategory
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort.
     *
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }
}
