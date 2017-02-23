<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @ORM\Entity
* @ORM\Table(name="lte_modules_cat")
*/
class ModulesCat
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $cat_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $parent_id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $cat_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $sort;

    /**
     * @ORM\Column(type="text")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="ModulesItem", mappedBy="cat_id")
     */
    private $modules;

    public function __construct()
    {
        parent::__construct();
        $this->modules = new ArrayCollection();
    }

    /**
     * Get catId
     *
     * @return integer
     */
    public function getCatId()
    {
        return $this->cat_id;
    }

    /**
     * Set parentId
     *
     * @param integer $parentId
     *
     * @return ModulesCat
     */
    public function setParentId($parentId)
    {
        $this->parent_id = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * Set catName
     *
     * @param string $catName
     *
     * @return ModulesCat
     */
    public function setCatName($catName)
    {
        $this->cat_name = $catName;

        return $this;
    }

    /**
     * Get catName
     *
     * @return string
     */
    public function getCatName()
    {
        return $this->cat_name;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     *
     * @return ModulesCat
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set comments
     *
     * @param string $comments
     *
     * @return ModulesCat
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add module
     *
     * @param \AppBundle\Entity\ModulesItem $module
     *
     * @return ModulesCat
     */
    public function addModule(\AppBundle\Entity\ModulesItem $module)
    {
        $this->modules[] = $module;

        return $this;
    }

    /**
     * Remove module
     *
     * @param \AppBundle\Entity\ModulesItem $module
     */
    public function removeModule(\AppBundle\Entity\ModulesItem $module)
    {
        $this->modules->removeElement($module);
    }

    /**
     * Get modules
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getModules()
    {
        return $this->modules;
    }
}
