<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="lte_news")
*/
class News
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $news_id;

    /**
     * @ORM\Column(type="string", length=11)
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $news;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $link;

    /**
     * Get newsId
     *
     * @return integer
     */
    public function getNewsId()
    {
        return $this->news_id;
    }

    /**
     * Set date
     *
     * @param string $date
     *
     * @return News
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set news
     *
     * @param string $news
     *
     * @return News
     */
    public function setNews($news)
    {
        $this->news = $news;

        return $this;
    }

    /**
     * Get news
     *
     * @return string
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return News
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
}
