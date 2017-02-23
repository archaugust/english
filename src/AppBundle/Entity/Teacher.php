<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="lte_teacher")
 */
class Teacher
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
     * @ORM\Column(type="string")
     *
     * @Assert\File(mimeTypes={ "image/jpg","image/jpeg","image/png" })
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $video_url;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $fullname;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $nickname;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $dob;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $gender;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $points;

    /**
     * @ORM\OneToMany(targetEntity="Bookmark", mappedBy="teacher")
     */
    private $bookmarks;

    /**
     * @ORM\OneToMany(targetEntity="Reservation", mappedBy="teacher")
     */
    private $reservations;

    /**
     * @ORM\OneToMany(targetEntity="Writing", mappedBy="teacher")
     */
    private $writings;

    /**
     * @ORM\OneToMany(targetEntity="Sked", mappedBy="teacher")
     */
    private $skeds;

    public function __construct()
    {
        $this->bookmarks = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->skeds = new ArrayCollection();
        $this->writings = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Teacher
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
     * Set avatar
     *
     * @param string $avatar
     *
     * @return Teacher
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set videoUrl
     *
     * @param string $videoUrl
     *
     * @return Teacher
     */
    public function setVideoUrl($videoUrl)
    {
        $this->video_url = $videoUrl;

        return $this;
    }

    /**
     * Get videoUrl
     *
     * @return string
     */
    public function getVideoUrl()
    {
        return $this->video_url;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     *
     * @return Teacher
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
     * Set nickname
     *
     * @param string $nickname
     *
     * @return Teacher
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Teacher
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set dob
     *
     * @param string $dob
     *
     * @return Teacher
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
     * Set gender
     *
     * @param string $gender
     *
     * @return Teacher
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Teacher
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return Teacher
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Teacher
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
     * @return Teacher
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
     * @return Teacher
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
     * @return Teacher
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
     * Add sked
     *
     * @param \AppBundle\Entity\Sked $sked
     *
     * @return Teacher
     */
    public function addSked(\AppBundle\Entity\Sked $sked)
    {
        $this->skeds[] = $sked;

        return $this;
    }

    /**
     * Remove sked
     *
     * @param \AppBundle\Entity\Sked $sked
     */
    public function removeSked(\AppBundle\Entity\Sked $sked)
    {
        $this->skeds->removeElement($sked);
    }

    /**
     * Get skeds
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSkeds()
    {
        return $this->skeds;
    }
}
