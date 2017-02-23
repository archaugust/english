<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="lte_activity")
*/
class Activity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $activity_id;
    /**
     * @ORM\Column(type="integer")
     */
    private $time_id;
    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;
    /**
     * @ORM\Column(type="integer")
     */
    private $activity_by;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="activities")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $map_user_id;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="activities_by")
     * @ORM\JoinColumn(name="activity_by", referencedColumnName="id")
     */
    private $map_activity_by;
    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * Get activityId
     *
     * @return integer
     */
    public function getActivityId()
    {
        return $this->activity_id;
    }

    /**
     * Set timeId
     *
     * @param integer $timeId
     *
     * @return Activity
     */
    public function setTimeId($timeId)
    {
        $this->time_id = $timeId;

        return $this;
    }

    /**
     * Get timeId
     *
     * @return integer
     */
    public function getTimeId()
    {
        return $this->time_id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Activity
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set activityBy
     *
     * @param integer $activityBy
     *
     * @return Activity
     */
    public function setActivityBy($activityBy)
    {
        $this->activity_by = $activityBy;

        return $this;
    }

    /**
     * Get activityBy
     *
     * @return integer
     */
    public function getActivityBy()
    {
        return $this->activity_by;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Activity
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set mapUserId
     *
     * @param \AppBundle\Entity\User $mapUserId
     *
     * @return Activity
     */
    public function setMapUserId(\AppBundle\Entity\User $mapUserId = null)
    {
        $this->map_user_id = $mapUserId;

        return $this;
    }

    /**
     * Get mapUserId
     *
     * @return \AppBundle\Entity\User
     */
    public function getMapUserId()
    {
        return $this->map_user_id;
    }

    /**
     * Set mapActivityBy
     *
     * @param \AppBundle\Entity\User $mapActivityBy
     *
     * @return Activity
     */
    public function setMapActivityBy(\AppBundle\Entity\User $mapActivityBy = null)
    {
        $this->map_activity_by = $mapActivityBy;

        return $this;
    }

    /**
     * Get mapActivityBy
     *
     * @return \AppBundle\Entity\User
     */
    public function getMapActivityBy()
    {
        return $this->map_activity_by;
    }
}
