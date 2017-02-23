<?php
namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="lte_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $nickname;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $uid;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank(message="Registration type error.")
     */
    protected $account_type;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $approved = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $gender;

    /**
     * @ORM\Column(type="integer")
     */
    private $date_registered;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="map_user_id")
     */
    private $activities;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="map_activity_by")
     */
    private $activities_by;

    /**
     * @ORM\OneToMany(targetEntity="Sked", mappedBy="teacher")
     */
    private $skeds;

    public function __construct()
    {
        parent::__construct();
        $this->activities = new ArrayCollection();
        $this->activities_by = new ArrayCollection();
        $this->skeds = new ArrayCollection();
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     *
     * @return User
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
     * Set uid
     *
     * @param string $uid
     *
     * @return User
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set accountType
     *
     * @param string $accountType
     *
     * @return User
     */
    public function setAccountType($accountType)
    {
        $this->account_type = $accountType;

        return $this;
    }

    /**
     * Get accountType
     *
     * @return string
     */
    public function getAccountType()
    {
        return $this->account_type;
    }

    /**
     * Set approved
     *
     * @param boolean $approved
     *
     * @return User
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * Get approved
     *
     * @return boolean
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return User
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return User
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
     * Set dateRegistered
     *
     * @param integer $dateRegistered
     *
     * @return User
     */
    public function setDateRegistered($dateRegistered)
    {
        $this->date_registered = $dateRegistered;

        return $this;
    }

    /**
     * Get dateRegistered
     *
     * @return integer
     */
    public function getDateRegistered()
    {
        return $this->date_registered;
    }

    /**
     * Add activity
     *
     * @param \AppBundle\Entity\Activity $activity
     *
     * @return User
     */
    public function addActivity(\AppBundle\Entity\Activity $activity)
    {
        $this->activities[] = $activity;

        return $this;
    }

    /**
     * Remove activity
     *
     * @param \AppBundle\Entity\Activity $activity
     */
    public function removeActivity(\AppBundle\Entity\Activity $activity)
    {
        $this->activities->removeElement($activity);
    }

    /**
     * Get activities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivities()
    {
        return $this->activities;
    }

    /**
     * Add activitiesBy
     *
     * @param \AppBundle\Entity\Activity $activitiesBy
     *
     * @return User
     */
    public function addActivitiesBy(\AppBundle\Entity\Activity $activitiesBy)
    {
        $this->activities_by[] = $activitiesBy;

        return $this;
    }

    /**
     * Remove activitiesBy
     *
     * @param \AppBundle\Entity\Activity $activitiesBy
     */
    public function removeActivitiesBy(\AppBundle\Entity\Activity $activitiesBy)
    {
        $this->activities_by->removeElement($activitiesBy);
    }

    /**
     * Get activitiesBy
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivitiesBy()
    {
        return $this->activities_by;
    }

    /**
     * Add sked
     *
     * @param \AppBundle\Entity\Sked $sked
     *
     * @return User
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
