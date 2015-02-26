<?php

namespace Payments\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Payments
 * @package Invoices\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="user_card")
 */
class UserCard
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
    protected $card_number;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $card_type;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $name_on_card;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $last_sale_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $authorization_id;
    
    /**
     * @ORM\Column(type="datetime")
    */
    protected $created_on;
    
    /**
     * @ORM\Column(type="datetime")
    */
    protected $updated_on;

    /**
     * @return integer
    */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return UserCard
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
     * @return UserCard
    */
    public function setUserId($iUserId)
    {
        $this->user_id = $iUserId;
        return $this;
    }
    
    /**
     * @return integer
     */
    public function getCardNumber()
    {
        return $this->card_number;
    }
    
    /**
     * @param integer $iCardNumber
     * @return UserCard
     */
    public function setCardNumber($iCardNumber)
    {
        $this->card_number = $iCardNumber;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getCardType()
    {
        return $this->card_type;
    }
    
    /**
     * @param integer $sCardType
     * @return UserCard
     */
    public function setCardType($sCardType)
    {
        $this->card_type = $sCardType;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getNameOnCard()
    {
        return $this->name_on_card;
    }
    
    /**
     * @param integer $sNameOnCard
     * @return UserCard
     */
    public function setNameOnCard($sNameOnCard)
    {
        $this->name_on_card = $sNameOnCard;
        return $this;
    }

    /**
     * @return integer
    */
    public function getLastSaleId()
    {
        return $this->last_sale_id;
    }

    /**
     * @param integer $iLastSaleId
     * @return UserCard
     */
    public function setLastSaleId($iLastSaleId)
    {
        $this->last_sale_id = $iLastSaleId;
        return $this;
    }

    /**
     * @return integer
     */
    public function getAuthorizationId()
    {
        return $this->authorization_id;
    }
    
    /**
     * @param integer $iAuthorizationId
     * @return UserCard
     */
    public function setAuthorizationId($iAuthorizationId)
    {
        $this->authorization_id = $iAuthorizationId;
        return $this;
    }
    
    /**
     * @return \DateTime
    */
    public function getCreatedOn()
    {
        return $this->created_on;
    }

    /**
     * @param \DateTime
     * @return UserCard
    */
    public function setCreatedOn($oDateTime)
    {
        $this->created_on = $oDateTime;
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getUpdateddOn()
    {
        return $this->updated_on;
    }
    
    /**
     * @param \DateTime
     * @return UserCard
     */
    public function setUpdatedOn($oDateTime)
    {
        $this->updated_on = $oDateTime;
        return $this;
    }

}