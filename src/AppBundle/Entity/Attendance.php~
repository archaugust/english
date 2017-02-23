<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="lte_attendance")
*/
class Attendance
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $attendance_id;
    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;
    /**
     * @ORM\Column(type="integer")
     */
    private $date_id;
    /**
     * @ORM\Column(type="integer")
     */
    private $time_id;

    /**
     * Get attendanceId
     *
     * @return integer
     */
    public function getAttendanceId()
    {
        return $this->attendance_id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Attendance
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
     * Set dateId
     *
     * @param integer $dateId
     *
     * @return Attendance
     */
    public function setDateId($dateId)
    {
        $this->date_id = $dateId;

        return $this;
    }

    /**
     * Get dateId
     *
     * @return integer
     */
    public function getDateId()
    {
        return $this->date_id;
    }

    /**
     * Set timeId
     *
     * @param integer $timeId
     *
     * @return Attendance
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
}
