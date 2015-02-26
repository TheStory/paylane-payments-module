<?php 
//  
//  Created by Przemysław Kublin on 2015-01-30.
//  Copyright 2015 Story Design Sp. z o.o. All rights reserved.
// 

namespace Payments\Repository;

class UserCard extends AbstractRepository
{
    /**
     * Return basic user's card data.
     * Like: id, card_number (only last 4 digits), card_type (Visa, MasterCard etc.), name on card, last sale id,
     * If row does't exist return null.
     *
     * @param integer $iUserId user's id
     * @return array | null
     */
    public function getUserCard($iUserId) {
    
        $em = $this->getEntityManager();
        return $em->getRepository('Payments\Entity\UserCard')->findOneBy(array(
                'user_id' => $iUserId
        ));
    }
    
    
    /**
     * @param array $aParams
     * @param integer $iUserId
     */
    public function updateUserCard($aParams, $iUserId) {
        
        $dql = 'update Payments\Entity\UserCard uc set uc.card_number = :card_number, uc.card_type = :card_type, uc.name_on_card = :name_on_card, uc.last_sale_id = :last_sale_id, uc.authorization_id = :authorization_id, uc.updated_on = :updated_on  '
                . 'where uc.user_id = :user_id';
        $em = $this->getEntityManager();
        $query = $em->createQuery($dql);
        $query->setParameter('user_id', $iUserId)
            ->setParameter('card_number', $aParams['card_number'])
            ->setParameter('card_type', $aParams['card_type'])
            ->setParameter('name_on_card', $aParams['name_on_card'])
            ->setParameter('last_sale_id', $aParams['last_sale_id'])
            ->setParameter('authorization_id', $aParams['authorization_id'])
            ->setParameter('updated_on', new \DateTime())
            ;
        $query->execute();
        $em->flush();
    }
}