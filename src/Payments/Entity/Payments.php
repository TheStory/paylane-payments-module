<?php

namespace Payments\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Payments
 * @package Invoices\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="payments")
 */
class Payments
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
    protected $sale_id;

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
     * @return Payments
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
     * @return Payments
    */
    public function setUserId($iUserId)
    {
        $this->user_id = $iUserId;
        return $this;
    }

    /**
     * @return integer
    */
    public function getSaleId()
    {
        return $this->sale_id;
    }

    /**
     * @param integer $iSaleId
     * @return Payments
     */
    public function setSaleId($iSaleId)
    {
        $this->sale_id = $iSaleId;
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