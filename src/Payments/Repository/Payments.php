<?php 
//  
//  Created by Przemysław Kublin on 2015-01-30.
//  Copyright 2015 Story Design Sp. z o.o. All rights reserved.
// 

namespace Payments\Repository;

class Payments extends AbstractRepository
{
    
    public function getAll() {
    
        $em = $this->getEntityManager();
        
        $dql = 'select p from Payments\Entity\Payments p order by p.created_on DESC';
        $query = $em->createQuery($dql);
        
        return $query->getResult();
    }
    
    public function getById($iId)
    {
        return $this->getEntityManager()->find('Payments\Entity\Payments', $iId);
    }
    
    public function  getRowsByUserId($iId) {
        
        $em = $this->getEntityManager();
        
        $dql = 'select p from Payments\Entity\Payments p where p.user_id = :user_id';
        $query = $em->createQuery($dql);
        $query->setParameter('user_id', $iId);
        
        return $query->getResult();
    }
}