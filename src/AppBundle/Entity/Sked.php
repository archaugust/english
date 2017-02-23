<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="lte_sked")
*/
class Sked
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $sked_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $teacher_id;

    /**
     * @ORM\ManyToOne(targetEntity="Teacher", inversedBy="skeds")
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     */
    private $teacher;

    /**
     * @ORM\Column(type="integer")
     */
    private $date_sked;

    /**
     * @ORM\Column(type="string", length=191, options={"default":"0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0"})
     */
    private $time_sked;

    /**
     * @ORM\Column(type="smallint")
     */
    private $absent;

    /**
     * Get skedId
     *
     * @return integer
     */
    public function getSkedId()
    {
        return $this->sked_id;
    }

    /**
     * Set teacherId
     *
     * @param integer $teacherId
     *
     * @return Sked
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
     * Set dateSked
     *
     * @param integer $dateSked
     *
     * @return Sked
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
     * @return Sked
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
     * Set absent
     *
     * @param integer $absent
     *
     * @return Sked
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
     * @return Sked
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
