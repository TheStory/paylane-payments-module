<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * 
 */
namespace Payments;

return array(
    'service_manager' => array(
        'invokables' => array(
            'payments.processor' => __NAMESPACE__.'\Service\Payments',
            'payments.reporter' => __NAMESPACE__.'\Service\Reporter',
            'payments.cards' => __NAMESPACE__.'\Service\UserCard',
            'payments.taxes' => __NAMESPACE__.'\Service\Taxes',
            'repo.payments' => 'Payments\Repository\Payments',
            'repo.user_card' => 'Payments\Repository\UserCard',
            'entity.user_card' => 'Payments\Entity\UserCard',
        ),
        'aliases' => array(
            'doctrine' => 'Doctrine\ORM\EntityManager',
        ),
    ),
    // Doctrine entities configuration
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ),
            ),
        ),
    ),
    'paylane' => array(
        'login' => 'thestorytest',
        'password' => 'dro8lu7w'
    ),
);