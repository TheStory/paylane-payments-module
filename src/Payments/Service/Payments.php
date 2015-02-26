<?php

/**
 * @author Przemyslaw Kublin
 * 
 * This class connect with PayLane service and allow payment.
 * More info:
 * @see http://devzone.paylane.pl/
 *
*/

namespace Payments\Service;

use Payments\Entity\Payments as PaymentsEntity;
use Payments\Entity\UserCard;
use Payments\Entity\Refund;
use Payments\Service\Reporter;

class Payments extends AbstractService //implements PaymentsInterface 
{		
		
	/**
	 * Single payment.
	 * Method try connect with PayLane service and perform single transaction.
	 * If payment is true then get transaction details form PayLane service using getSaleInfo() (@see \Payments\Service\Reporter) 
	 * and save card data into user_card table.
	 * 
	 * More info @see http://devzone.paylane.pl/api/karty/pojedyncza-transakcja/
	 * @param array $aCardParams = array(
     *				'sale'     => array('amount'=>1.00,'currency'=>'EUR','description'=>''),
     *				'customer' => array('email'=>'','ip'=>'Remote ip address', 'address' => array ('street_house' => '', 'city' => '','zip'=> '','country_code' => '')),
     *				'card' => array('card_number' => '','expiration_month' => 12,'expiration_year' => 2015,'name_on_card' => '','card_code' => 'CVV2/CVC2V number'));
	 * @param integer $iUserId Local user id
	 * @param bool $bIsNew transaction with old card data or new data 
	 * @throws Exception
	 * @return Array|boolean
	*/
	public function cardSale($aCardParams, $iUserId, $bIsNew = false) {
	    
	    $oPayLaneRestClient = $this->getPayLaneRestClient();
	    //Try transaction
	    try {
	        $aStatus = $oPayLaneRestClient->cardSale($aCardParams);
	    }
	    catch (\Exception $e) {
	        try {
	            $aStatus = $oPayLaneRestClient->cardSale($aCardParams);
	        } 
	        catch (\Exception $e) {
	            try {
	                $aStatus = $oPayLaneRestClient->cardSale($aCardParams);
	            }
	            catch (\Exception $e) {
	                throw new \Exception($e->getMessage());
	            }
	        }
	    }
	    
		if ($oPayLaneRestClient->isSuccess()) {
		    $em = $this->getEntityManager();
		    
		    //Save payment into "payments" table.
		    $oPaymentsEntity = new PaymentsEntity();
		    
		    $oDate = new \DateTime();
		    $oPaymentsEntity->setUserId($iUserId)
                ->setSaleId($aStatus['id_sale'])
		        ->setDateTime($oDate);
		     
		    $em->persist($oPaymentsEntity);
		    $em->flush($oPaymentsEntity);
			
		    //Get card info
		    $aParam = array('id_sale' => $aStatus['id_sale']);
		    try {
		        $oReport = $this->getServiceLocator()->get('payments.reporter');
		        $aRes = $oReport->getSaleInfo($aParam);
		        
		    } catch (\Exception $e) {
		        throw new \Exception($e->getMessage());
		    }		    
		    
		    if($bIsNew == true) {
		        //Save new card data
		        $oUserCardEntity = new UserCard();
		        $oUserCardEntity->setCreatedOn($oDate);
		    }
		    else {
		        //Update card data
		        $oUserCardEntity = $this->getServiceLocator()->get('repo.user_card')->getUserCard($iUserId);
		        $oUserCardEntity->setUpdatedOn($oDate);
		    }
		    
            $oUserCardEntity->setUserId($iUserId)
                ->setCardNumber(substr($aRes['card']['number'], -4))
                ->setCardType($aRes['payment_method'])
                ->setNameOnCard($aRes['card']['name'])
                ->setLastSaleId($aStatus['id_sale'])
                ->setAuthorizationId(null)
            ;
            
            if($bIsNew == true) {
                $em->persist($oUserCardEntity);
            }
            $em->flush($oUserCardEntity);
            
			return $aStatus;
		} else {
		    $iErrorNumber = $aStatus['error']['error_number'];
		    $sErrorDescription = $aStatus['error']['error_description'];
		    
		    throw new \Exception('Error number: ' . $iErrorNumber. ', Error description: ' . $sErrorDescription);
		    return false;
		}
	}
	
	/**
	 * Allows admin to refund a certain amount
	 * More info @see http://devzone.paylane.pl/api/karty/zwrot-srodkow/
	 * @param array $aParams Array with params like: "id_sale", "amount", "reason"
	 * @param integer $iUserId Local user id
	 * @throws Exception
	 * @return Array|boolean
	*/
	public function refund($aParams, $iUserId) {
	    
	    $oPayLaneRestClient = $this->getPayLaneRestClient();
	    //Try transaction
	    try {
	        $aStatus = $oPayLaneRestClient->refund($aParams);
	    }
	    catch (\Exception $e) {
	        try {
	            $aStatus = $oPayLaneRestClient->refund($aParams);
	        }
	        catch (\Exception $e) {
	            try {
	                $aStatus = $oPayLaneRestClient->refund($aParams);
	            }
	            catch (\Exception $e) {
	                throw new \Exception($e->getMessage());
	            }
	        }
	    }
	     
	    if ($oPayLaneRestClient->isSuccess()) {
	        $em = $this->getEntityManager();
	    
	        //Save payment into local db
	        $oRefund = new Refund();
	    
	        $oDate = new \DateTime();
	        $oRefund->setUserId($iUserId)
                	->setRefundId($aStatus['id_refund'])
                	->setDateTime($oDate);
	         
	        $em->persist($oRefund);
	        $em->flush($oRefund);
	        	
	        return $aStatus;
	    } else {
	        $iErrorNumber = $aStatus['error']['error_number'];
	        $sErrorDescription = $aStatus['error']['error_description'];
	    
	        throw new \Exception('Error number: ' . $iErrorNumber. ', Error description: ' . $sErrorDescription);
	        return false;
	    }     
	}
	
	
	/**
	 * @param UserCard $card
	 * @param array $aParams = array('amount'=>99.99,'currency'=>'EUR','description'=>'Recurring billing product #1',);
	 * @see resaleBySale() or resaleByAuthorization();
	 * @throws \Exception
	 * @return Array
	*/
	public function resale(UserCard $card, $aParams) {
	    if($card->getLastSaleId() == null) {
	        try {
	            $aParams['id_authorization'] = $card->getAuthorizationId();
	            $aRes = $this->resaleByAuthorization($aParams);
	        }
	        catch (\Exception $e) {
	            throw new \Exception($e->getMessage());
	        }
	    } else {
	        try {
	            $aParams['id_sale'] = $card->getLastSaleId();
	            $aRes = $this->resaleBySale($aParams);
	        }
	        catch (\Exception $e) {
	            throw new \Exception($e->getMessage());
	        }
	    }
	    
	    $em = $this->getEntityManager();
	    
	    //Save payment into "payments" table
	    $oPaymentsEntity = new PaymentsEntity();
	    
	    $oDate = new \DateTime();
	    $oPaymentsEntity->setUserId($card->getUserId())
	       ->setSaleId($aRes['id_sale'])
	       ->setDateTime($oDate);
	     
	    $em->persist($oPaymentsEntity);
	    $em->flush($oPaymentsEntity);
	    
	    //Update card data by user_id
	    $card->setLastSaleId($aRes['id_sale'])
	         ->setUpdatedOn($oDate)
	         ->setAuthorizationId(null)
	    ;
	    
	    $em->persist($card);
	    $em->flush($card);
	    
	    return $aRes;
	}
	
	/**
	 * Single payment using prev payment's id
	 * Method try connect with PayLane service and perform single transaction.
	 * More info @see http://devzone.paylane.pl/api/karty/platnosci-cykliczne/#resale-transakcje
	 *
	 * @param array $aCardParams = array('id_sale'=>'','amount'=>99.99,'currency'=>'EUR','description'=>'Recurring billing product #1',);
	 * @throws Exception
	 * @return Array|boolean
	*/
	private function resaleBySale($aResaleParams) {
        $oPayLaneRestClient = $this->getPayLaneRestClient();
	    //Try transaction
        try {
            $aStatus = $oPayLaneRestClient->resaleBySale($aResaleParams);
        }
	    catch (\Exception $e) {
	        try {
	            $aStatus = $oPayLaneRestClient->resaleBySale($aResaleParams);
	        } 
	        catch (\Exception $e) {
	            try {
	                $aStatus = $oPayLaneRestClient->resaleBySale($aResaleParams);
	            }
	            catch (\Exception $e) {
	                throw new \Exception($e->getMessage());
	            }
	        }
	    }
	    
		if ($oPayLaneRestClient->isSuccess()) {
		    return $aStatus;
		} else {
		    $iErrorNumber = $aStatus['error']['error_number'];
		    $sErrorDescription = $aStatus['error']['error_description'];
		    
		    throw new \Exception('Error number: ' . $iErrorNumber. ', Error description: ' . $sErrorDescription);
		    return false;
		}
	}
	
	/**
	 * Single payment using authorisation id.
	 * Method try connect with PayLane service and perform single transaction.
	 * More info @see http://devzone.paylane.pl/api/karty/platnosci-cykliczne/#resale-autoryzacja
	 *
	 * @param array $aCardParams = array('id_authorization' => '','amount'=>99.99,'currency'=>'EUR','description'=>'Recurring billing product #1',);
	 * @throws Exception
	 * @return Array|boolean
	 */
	private function resaleByAuthorization($aResaleParams) {
	    $oPayLaneRestClient = $this->getPayLaneRestClient();
	    
	    try {
	        $aStatus = $oPayLaneRestClient->resaleByAuthorization($aResaleParams);
	    }
	    catch (\Exception $e) {
	        try {
	            $aStatus = $oPayLaneRestClient->resaleByAuthorization($aResaleParams);
	        }
	        catch (\Exception $e) {
	            try {
	                $aStatus = $oPayLaneRestClient->resaleByAuthorization($aResaleParams);
	            }
	            catch (\Exception $e) {
	                throw new \Exception($e->getMessage());
	            }
	        }
	    }
	     
	    if ($oPayLaneRestClient->isSuccess()) {
	        return $aStatus;
	    } else {
	        $iErrorNumber = $aStatus['error']['error_number'];
	        $sErrorDescription = $aStatus['error']['error_description'];
	
	        throw new \Exception('Error number: ' . $iErrorNumber. ', Error description: ' . $sErrorDescription);
	        return false;
	    }
	}	
}