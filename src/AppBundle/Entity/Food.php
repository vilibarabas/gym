<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Food
 *
 * @ORM\Table(name="food")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FoodRepository")
 */
class Food
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
     * @ORM\Column(name="calories", type="integer")
     */
    private $calories;

    /**
     * @var int
     *
     * @ORM\Column(name="fat", type="integer")
     */
    private $fat;

    /**
     * @var int
     *
     * @ORM\Column(name="protein", type="integer")
     */
    private $protein;

    /**
     * @var int
     *
     * @ORM\Column(name="carbo", type="integer")
     */
    private $carbo;

    /**
     * @var \AppBundle\Entity\FoodCategory
     * @ORM\ManyToOne(targetEntity="FoodCategory", inversedBy="foods")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;


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
     * @return Food
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
     * Set calories.
     *
     * @param int $calories
     *
     * @return Food
     */
    public function setCalories($calories)
    {
        $this->calories = $calories;

        return $this;
    }

    /**
     * Get calories.
     *
     * @return int
     */
    public function getCalories()
    {
        return $this->calories;
    }

    /**
     * Set fat.
     *
     * @param int $fat
     *
     * @return Food
     */
    public function setFat($fat)
    {
        $this->fat = $fat;

        return $this;
    }

    /**
     * Get fat.
     *
     * @return int
     */
    public function getFat()
    {
        return $this->fat;
    }

    /**
     * Set protein.
     *
     * @param int $protein
     *
     * @return Food
     */
    public function setProtein($protein)
    {
        $this->protein = $protein;

        return $this;
    }

    /**
     * Get protein.
     *
     * @return int
     */
    public function getProtein()
    {
        return $this->protein;
    }

    /**
     * Set carbo.
     *
     * @param int $carbo
     *
     * @return Food
     */
    public function setCarbo($carbo)
    {
        $this->carbo = $carbo;

        return $this;
    }

    /**
     * Get carbo.
     *
     * @return int
     */
    public function getCarbo()
    {
        return $this->carbo;
    }

    /**
     * Set category.
     *
     * @param \AppBundle\Entity\FoodCategory|null $category
     *
     * @return Food
     */
    public function setCategory(\AppBundle\Entity\FoodCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return \AppBundle\Entity\FoodCategory|null
     */
    public function getCategory()
    {
        return $this->category;
    }
}
