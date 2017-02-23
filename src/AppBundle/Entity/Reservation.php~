<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="lte_reservation")
*/
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $reservation_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $teacher_id;

    /**
     * @ORM\ManyToOne(targetEntity="Teacher", inversedBy="reservations")
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     */
    private $teacher;

    /**
     * @ORM\Column(type="integer")
     */
    private $student_id;

    /**
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="reservations")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     */
    private $student;

    /**
     * @ORM\Column(type="integer")
     */
    private $date_sked;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $time_sked;

    /**
     * @ORM\Column(type="text")
     */
    private $notes;

    /**
     * @ORM\Column(type="text")
     */
    private $mode;

    /**
     * @ORM\Column(type="integer")
     */
    private $commendation;

    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @ORM\Column(type="smallint")
     */
    private $point_cost;

    /**
     * @ORM\Column(type="smallint")
     */
    private $absent;

    /**
     * Get reservationId
     *
     * @return integer
     */
    public function getReservationId()
    {
        return $this->reservation_id;
    }

    /**
     * Set teacherId
     *
     * @param integer $teacherId
     *
     * @return Reservation
     */
    public function setTeacherId($teacherId)
    {
        $this->teacher_id = $teacherId;

        return $this;
    }

    /**
     * Get teacherId
     *
     * @return integer
     */
    public function getTeacherId()
    {
        return $this->teacher_id;
    }

    /**
     * Set studentId
     *
     * @param integer $studentId
     *
     * @return Reservation
     */
    public function setStudentId($studentId)
    {
        $this->student_id = $studentId;

        return $this;
    }

    /**
     * Get studentId
     *
     * @return integer
     */
    public function getStudentId()
    {
        return $this->student_id;
    }

    /**
     * Set dateSked
     *
     * @param integer $dateSked
     *
     * @return Reservation
     */
    public function setDateSked($dateSked)
    {
        $this->date_sked = $dateSked;

        return $this;
    }

    /**
     * Get dateSked
     *
     * @return integer
     */
    public function getDateSked()
    {
        return $this->date_sked;
    }

    /**
     * Set timeSked
     *
     * @param string $timeSked
     *
     * @return Reservation
     */
    public function setTimeSked($timeSked)
    {
        $this->time_sked = $timeSked;

        return $this;
    }

    /**
     * Get timeSked
     *
     * @return string
     */
    public function getTimeSked()
    {
        return $this->time_sked;
    }

    /**
     * Set notes
     *
     * @param string $notes
     *
     * @return Reservation
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set mode
     *
     * @param string $mode
     *
     * @return Reservation
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Get mode
     *
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set commendation
     *
     * @param integer $commendation
     *
     * @return Reservation
     */
    public function setCommendation($commendation)
    {
        $this->commendation = $commendation;

        return $this;
    }

    /**
     * Get commendation
     *
     * @return integer
     */
    public function getCommendation()
    {
        return $this->commendation;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Reservation
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set pointCost
     *
     * @param integer $pointCost
     *
     * @return Reservation
     */
    public function setPointCost($pointCost)
    {
        $this->point_cost = $pointCost;

        return $this;
    }

    /**
     * Get pointCost
     *
     * @return integer
     */
    public function getPointCost()
    {
        return $this->point_cost;
    }

    /**
     * Set absent
     *
     * @param integer $absent
     *
     * @return Reservation
     */
    public function setAbsent($absent)
    {
        $this->absent = $absent;

        return $this;
    }

    /**
     * Get absent
     *
     * @return integer
     */
    public function getAbsent()
    {
        return $this->absent;
    }

    /**
     * Set teacher
     *
     * @param \AppBundle\Entity\Teacher $teacher
     *
     * @return Reservation
     */
    public function setTeacher(\AppBundle\Entity\Teacher $teacher = null)
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * Get teacher
     *
     * @return \AppBundle\Entity\Teacher
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * Set student
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return Reservation
     */
    public function setStudent(\AppBundle\Entity\Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return \AppBundle\Entity\Student
     */
    public function getStudent()
    {
        return $this->student;
    }
}
