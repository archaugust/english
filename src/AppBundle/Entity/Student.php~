<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="lte_student")
 */
class Student
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $points;

    /**
     * @ORM\Column(type="text")
     */
    private $fullname;

    /**
     * @ORM\OneToMany(targetEntity="Bookmark", mappedBy="student")
     */
    private $bookmarks;

    /**
     * @ORM\OneToMany(targetEntity="Reservation", mappedBy="student")
     */
    private $reservations;

    /**
     * @ORM\OneToMany(targetEntity="Writing", mappedBy="student")
     */
    private $writings;

    /**
     * @ORM\OneToMany(targetEntity="Plan", mappedBy="student")
     */
    private $plans;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $dob;

    public function __construct()
    {
        $this->bookmarks = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->plans = new ArrayCollection();
        $this->writings = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Student
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return Student
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     *
     * @return Student
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set dob
     *
     * @param string $dob
     *
     * @return Student
     */
    public function setDob($dob)
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     * Get dob
     *
     * @return string
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Student
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add bookmark
     *
     * @param \AppBundle\Entity\Bookmark $bookmark
     *
     * @return Student
     */
    public function addBookmark(\AppBundle\Entity\Bookmark $bookmark)
    {
        $this->bookmarks[] = $bookmark;

        return $this;
    }

    /**
     * Remove bookmark
     *
     * @param \AppBundle\Entity\Bookmark $bookmark
     */
    public function removeBookmark(\AppBundle\Entity\Bookmark $bookmark)
    {
        $this->bookmarks->removeElement($bookmark);
    }

    /**
     * Get bookmarks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBookmarks()
    {
        return $this->bookmarks;
    }

    /**
     * Add reservation
     *
     * @param \AppBundle\Entity\Reservation $reservation
     *
     * @return Student
     */
    public function addReservation(\AppBundle\Entity\Reservation $reservation)
    {
        $this->reservations[] = $reservation;

        return $this;
    }

    /**
     * Remove reservation
     *
     * @param \AppBundle\Entity\Reservation $reservation
     */
    public function removeReservation(\AppBundle\Entity\Reservation $reservation)
    {
        $this->reservations->removeElement($reservation);
    }

    /**
     * Get reservations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReservations()
    {
        return $this->reservations;
    }

    /**
     * Add writing
     *
     * @param \AppBundle\Entity\Writing $writing
     *
     * @return Student
     */
    public function addWriting(\AppBundle\Entity\Writing $writing)
    {
        $this->writings[] = $writing;

        return $this;
    }

    /**
     * Remove writing
     *
     * @param \AppBundle\Entity\Writing $writing
     */
    public function removeWriting(\AppBundle\Entity\Writing $writing)
    {
        $this->writings->removeElement($writing);
    }

    /**
     * Get writings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWritings()
    {
        return $this->writings;
    }

    /**
     * Add plan
     *
     * @param \AppBundle\Entity\Plan $plan
     *
     * @return Student
     */
    public function addPlan(\AppBundle\Entity\Plan $plan)
    {
        $this->plans[] = $plan;

        return $this;
    }

    /**
     * Remove plan
     *
     * @param \AppBundle\Entity\Plan $plan
     */
    public function removePlan(\AppBundle\Entity\Plan $plan)
    {
        $this->plans->removeElement($plan);
    }

    /**
     * Get plans
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlans()
    {
        return $this->plans;
    }
}
