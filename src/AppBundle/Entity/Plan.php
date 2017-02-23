<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="lte_plan")
*/
class Plan
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $plan_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $student_id;

    /**
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="plans")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     */
    private $student;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $plan;

    /**
     * @ORM\Column(type="integer")
     */
    private $date_updated;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $txn_id;

    /**
     * Get planId
     *
     * @return integer
     */
    public function getPlanId()
    {
        return $this->plan_id;
    }

    /**
     * Set studentId
     *
     * @param integer $studentId
     *
     * @return Plan
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
     * Set plan
     *
     * @param string $plan
     *
     * @return Plan
     */
    public function setPlan($plan)
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * Get plan
     *
     * @return string
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * Set dateUpdated
     *
     * @param integer $dateUpdated
     *
     * @return Plan
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->date_updated = $dateUpdated;

        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return integer
     */
    public function getDateUpdated()
    {
        return $this->date_updated;
    }

    /**
     * Set txnId
     *
     * @param string $txnId
     *
     * @return Plan
     */
    public function setTxnId($txnId)
    {
        $this->txn_id = $txnId;

        return $this;
    }

    /**
     * Get txnId
     *
     * @return string
     */
    public function getTxnId()
    {
        return $this->txn_id;
    }

    /**
     * Set student
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return Plan
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
