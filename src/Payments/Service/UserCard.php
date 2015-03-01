<?php

namespace Payments\Service;

class UserCard extends AbstractService
{
    /**
     * Card authorization and save user card details into user_card.
     * 
     * More info @see http://devzone.paylane.pl/opis-funkcji/#cards-authorization
     * 
     * @param array $aParams = array(
     *     'sale'     => array('amount'=>1.00,'currency'=>'EUR','description'=>''),
     *     'customer' => array('email'=>'','ip'=>'Remote ip address', 'address' => array ('street_house' => '', 'city' => '','zip'=> '','country_code' => '')),
     *     'card' => array('card_number' => '','expiration_month' => 12,'expiration_year' => 2015,'name_on_card' => '','card_code' => 'CVV2/CVC2V number'));
     * @param integer $iUserId 
     * @throws \Exception
     * @return array|boolean
    */
    public function updateUserCard($aParams, $iUserId) {
        $oPayLaneRestClient = $this->getPayLaneRestClient();
        
        try {
            $aStatus = $oPayLaneRestClient->cardAuthorization($aParams);
        }
        catch (\Exception $e) {
            try {
                $aStatus = $oPayLaneRestClient->cardAuthorization($aParams);
            }
            catch (\Exception $e) {
                try {
                    $aStatus = $oPayLaneRestClient->cardAuthorization($aParams);
                }
                catch (\Exception $e) {
                    throw new \Exception($e->getMessage());
                }
            }
        }
        
        if ($oPayLaneRestClient->isSuccess()) {
            //Get card info
            $aCardParam = array('card_number' => $aParams['card']['card_number']);
            try {
                $oReport = $this->getServiceLocator()->get('payments.reporter');
                $aCardDetails = $oReport->checkCard($aCardParam);
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
            
            //update user card
            $aUserData = array(
                'user_id' => $iUserId,
                'card_number' => substr($aParams['card']['card_number'], -4),
                'card_type' => $aCardDetails['card_type'],
                'authorization_id' => $aStatus['id_authorization'],
                'name_on_card' => $aParams['card']['name_on_card'],
                'last_sale_id' => null,
            );
            $oUserCardRepository = $this->getServiceLocator()->get('repo.user_card');
            $oUserCardRepository->updateUserCard($aUserData, $iUserId);
            
            return $aStatus;
        } else {
            $iErrorNumber = $aStatus['error']['error_number'];
            $sErrorDescription = $aStatus['error']['error_description'];
        
            throw new \Exception('Error number: ' . $iErrorNumber. ', Error description: ' . $sErrorDescription, $iErrorNumber);
            return false;
        }
    }
}