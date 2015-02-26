<?php
/**
 * @author Przemyslaw Kublin
*/

namespace Payments\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractService implements ServiceLocatorAwareInterface
{
    private $serviceLocator;
    
    /**
     * Login paylane account.
     * @var string
     */
    private $sPLogin;
    
    /**
     * Password paylane account.
     * @var string
     */
    private $sPPassword;
    
    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
    
    /**
     * @return \Doctrine\ORM\EntityManager Doctrine ORM EntityManager
     */
    public function getEntityManager()
    {
        return $this->serviceLocator->get('doctrine');
    }    
    
    /**
     * @see payments/config/module.config.php
     * @return array, Login and password of paylane account.
    */
    private function getPayLane() {
        $aConf = $this->serviceLocator->get('config');
        
        return $aConf['paylane'];
    }
    
    /**
     * @see payments/config/module.config.php
     * @return string Paylane account login. 
    */
    private function getPayLaneLogin() {
        $aPayLane = $this->getPayLane();
        
        $this->sPLogin = $aPayLane['login'];
        
        return $this->sPLogin;
    }
    
    /**
     * @see payments/config/module.config.php
     * @return string Paylane account password. 
    */
    private function getPayLanePassword() {
        $aPayLane = $this->getPayLane();
    
        $this->sPPassword = $aPayLane['password'];
        
        return $this->sPPassword;
    }
    
    /**
     * Instance of PayLaneRestClient class.
     * @see PayLaneRestClient.php
     * @return \Payments\Service\PayLaneRestClient
    */
    protected function getPayLaneRestClient() {
        $oPayLaneRestClient = new PayLaneRestClient($this->getPayLaneLogin(), $this->getPayLanePassword());
        
        return $oPayLaneRestClient;
    }
}