<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="lte_modules_item")
*/
class ModulesItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $module_id;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="ModulesCat", inversedBy="modules")
     * @ORM\JoinColumn(name="cat_id", referencedColumnName="cat_id")
     */
    private $cat_id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $file2;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $url;

    /**
     * @ORM\Column(type="integer")
     */
    private $sort;

    /**
     * Get moduleId
     *
     * @return integer
     */
    public function getModuleId()
    {
        return $this->module_id;
    }

    /**
     * Set catId
     *
     * @param integer $catId
     *
     * @return ModulesItem
     */
    public function setCatId($catId)
    {
        $this->cat_id = $catId;

        return $this;
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
     * Set title
     *
     * @param string $title
     *
     * @return ModulesItem
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set file
     *
     * @param string $file
     *
     * @return ModulesItem
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set file2
     *
     * @param string $file2
     *
     * @return ModulesItem
     */
    public function setFile2($file2)
    {
        $this->file2 = $file2;

        return $this;
    }

    /**
     * Get file2
     *
     * @return string
     */
    public function getFile2()
    {
        return $this->file2;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return ModulesItem
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     *
     * @return ModulesItem
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
}
