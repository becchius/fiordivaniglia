<?php

/**
 * Created by PhpStorm.
 * User: andrea.becchio
 * Date: 18/04/2016
 * Time: 18:37
 */
class Zab_Storepickup_Model_Quote_Address_Rate extends Mage_Sales_Model_Quote_Address_Rate
{
    public function importShippingRate(Mage_Shipping_Model_Rate_Result_Abstract $rate){
        parent::importShippingRate($rate);
        $this->setAdditionalInfo($rate->getAdditionalInfo());
        return $this;
    }

}