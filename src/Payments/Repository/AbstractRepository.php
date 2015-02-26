<?php

//  
//  Created by Przemyslaw Kublin on 2015-02-13.
//  Copyright 2015 Story Design Sp. z o.o.. All rights reserved.
// 

namespace Payments\Repository;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractRepository implements ServiceLocatorAwareInterface
{

    protected $serviceLocator;

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return \Doctrine\ORM\EntityManager Doctrine ORM EntityManager
     */
    public function getEntityManager()
    {
        return $this->getServiceLocator()->get('doctrine');
    }
    
    
}
