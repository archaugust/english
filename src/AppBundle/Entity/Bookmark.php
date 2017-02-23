<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="lte_bookmark")
*/
class Bookmark
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $bookmark_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $teacher_id;

    /**
     * @ORM\ManyToOne(targetEntity="Teacher", inversedBy="bookmarks")
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     */
    private $teacher;

    /**
     * @ORM\Column(type="integer")
     */
    private $student_id;

    /**
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="bookmarks")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     */
    private $student;

    /**
     * Get bookmarkId
     *
     * @return integer
     */
    public function getBookmarkId()
    {
        return $this->bookmark_id;
    }

    /**
     * Set teacherId
     *
     * @param integer $teacherId
     *
     * @return Bookmark
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
     * @return Bookmark
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
     * Set teacher
     *
     * @param \AppBundle\Entity\Teacher $teacher
     *
     * @return Bookmark
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
     * @return Bookmark
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
