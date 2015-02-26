<?php

namespace Payments\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Refund
 * @package Invoices\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="payments_refund")
 */
class Refund
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $refund_id;

    /**
     * @ORM\Column(type="datetime")
    */
    protected $created_on;

    /**
     * @return integer
    */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return Refund
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return integer
    */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param integer $iUserId
     * @return Refund
    */
    public function setUserId($iUserId)
    {
        $this->user_id = $iUserId;
        return $this;
    }

    /**
     * @return integer
    */
    public function getRefundId()
    {
        return $this->refund_id;
    }

    /**
     * @param integer $iRefundId
     * @return Refund
     */
    public function setRefundId($iRefundId)
    {
        $this->refund_id = $iRefundId;
        return $this;
    }

    /**
     * @return \DateTime
    */
    public function getDateTime()
    {
        return $this->created_on;
    }

    /**
     * @param \DateTime $sDateTime
     * @return Payments
    */
    public function setDateTime($sDateTime)
    {

        $this->created_on = $sDateTime;
        return $this;
    }

}