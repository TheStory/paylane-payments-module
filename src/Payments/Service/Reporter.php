<?php

namespace Payments\Service;

class Reporter extends AbstractService
{
    /**
     * This method returns transaction’s data that allow to identify its status (including fund returns).
     * More info:
     * @see http://devzone.paylane.com/function-reference/#sales-info
     * @param array $aParams = array('id_sale' => 2345), Array with id_sale param. 
     * @return array
    */
    public function getSaleInfo($aParams) {
        $oPayLaneRestClient = $this->getPayLaneRestClient();
        
        try {
            $aStatus = $oPayLaneRestClient->getSaleInfo($aParams);
        }
        catch (\Exception $e) {
            try {
                $aStatus = $oPayLaneRestClient->getSaleInfo($aParams);
            }
            catch (\Exception $e) {
                try {
                    $aStatus = $oPayLaneRestClient->getSaleInfo($aParams);
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
     * This method returns card's details data .
     * More info:
     * @see http://devzone.paylane.com/function-reference/#cards-check
     * @param array $aParams = array('card_number' => 4200000000000000), Array.
     * @return array
     */
    public function checkCard($aParams) {
        $oPayLaneRestClient = $this->getPayLaneRestClient();
    
        try {
            $aStatus = $oPayLaneRestClient->checkCard($aParams);
        }
        catch (\Exception $e) {
            try {
                $aStatus = $oPayLaneRestClient->checkCard($aParams);
            }
            catch (\Exception $e) {
                try {
                    $aStatus = $oPayLaneRestClient->checkCard($aParams);
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