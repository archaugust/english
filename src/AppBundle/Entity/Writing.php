<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="lte_writing")
 */
class Writing
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $writing_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $reservation_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $student_id;

    /**
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="writings")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     */
    private $student;

    /**
     * @ORM\Column(type="integer")
     */
    private $teacher_id;

    /**
     * @ORM\ManyToOne(targetEntity="Teacher", inversedBy="writings")
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     */
    private $teacher;

    /**
     * @ORM\Column(type="text")
     */
    private $type;

    /**
     * @ORM\Column(type="text")
     */
    private $mode;

    /**
     * @ORM\Column(type="text")
     */
    private $instructions;

    /**
     * @ORM\Column(type="text")
     */
    private $article;

    /**
     * @ORM\Column(type="text")
     */
    private $remarks;

    /**
     * @ORM\Column(type="text")
     */
    private $comments;

    /**
     * @ORM\Column(type="integer")
     */
    private $date_offered;

    /**
     * @ORM\Column(type="integer")
     */
    private $date_submitted;

    /**
     * @ORM\Column(type="integer")
     */
    private $date_reviewed;

    /**
     * @ORM\Column(type="integer")
     */
    private $point_cost;

    /**
     * @ORM\Column(type="string",length=10)
     */
    private $status;

    /**
     * @ORM\Column(type="text")
     */
    private $student_explanation;

    /**
     * @ORM\Column(type="text")
     */
    private $teacher_message;

    /**
     * Get writingId
     *
     * @return integer
     */
    public function getWritingId()
    {
        return $this->writing_id;
    }

    /**
     * Set reservationId
     *
     * @param integer $reservationId
     *
     * @return Writing
     */
    public function setReservationId($reservationId)
    {
        $this->reservation_id = $reservationId;

        return $this;
    }

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
     * Set studentId
     *
     * @param integer $studentId
     *
     * @return Writing
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
     * Set teacherId
     *
     * @param integer $teacherId
     *
     * @return Writing
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
     * Set type
     *
     * @param string $type
     *
     * @return Writing
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set mode
     *
     * @param string $mode
     *
     * @return Writing
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
     * Set instructions
     *
     * @param string $instructions
     *
     * @return Writing
     */
    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;

        return $this;
    }

    /**
     * Get instructions
     *
     * @return string
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * Set article
     *
     * @param string $article
     *
     * @return Writing
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return string
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set remarks
     *
     * @param string $remarks
     *
     * @return Writing
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;

        return $this;
    }

    /**
     * Get remarks
     *
     * @return string
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * Set comments
     *
     * @param string $comments
     *
     * @return Writing
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
     * Set dateOffered
     *
     * @param integer $dateOffered
     *
     * @return Writing
     */
    public function setDateOffered($dateOffered)
    {
        $this->date_offered = $dateOffered;

        return $this;
    }

    /**
     * Get dateOffered
     *
     * @return integer
     */
    public function getDateOffered()
    {
        return $this->date_offered;
    }

    /**
     * Set dateSubmitted
     *
     * @param integer $dateSubmitted
     *
     * @return Writing
     */
    public function setDateSubmitted($dateSubmitted)
    {
        $this->date_submitted = $dateSubmitted;

        return $this;
    }

    /**
     * Get dateSubmitted
     *
     * @return integer
     */
    public function getDateSubmitted()
    {
        return $this->date_submitted;
    }

    /**
     * Set dateReviewed
     *
     * @param integer $dateReviewed
     *
     * @return Writing
     */
    public function setDateReviewed($dateReviewed)
    {
        $this->date_reviewed = $dateReviewed;

        return $this;
    }

    /**
     * Get dateReviewed
     *
     * @return integer
     */
    public function getDateReviewed()
    {
        return $this->date_reviewed;
    }

    /**
     * Set pointCost
     *
     * @param integer $pointCost
     *
     * @return Writing
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
     * Set status
     *
     * @param string $status
     *
     * @return Writing
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set studentExplanation
     *
     * @param string $studentExplanation
     *
     * @return Writing
     */
    public function setStudentExplanation($studentExplanation)
    {
        $this->student_explanation = $studentExplanation;

        return $this;
    }

    /**
     * Get studentExplanation
     *
     * @return string
     */
    public function getStudentExplanation()
    {
        return $this->student_explanation;
    }

    /**
     * Set teacherMessage
     *
     * @param string $teacherMessage
     *
     * @return Writing
     */
    public function setTeacherMessage($teacherMessage)
    {
        $this->teacher_message = $teacherMessage;

        return $this;
    }

    /**
     * Get teacherMessage
     *
     * @return string
     */
    public function getTeacherMessage()
    {
        return $this->teacher_message;
    }

    /**
     * Set student
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return Writing
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

    /**
     * Set teacher
     *
     * @param \AppBundle\Entity\Teacher $teacher
     *
     * @return Writing
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
}
