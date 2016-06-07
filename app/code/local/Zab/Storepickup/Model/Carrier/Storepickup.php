<?php

/**
 * Created by PhpStorm.
 * User: andrea.becchio
 * Date: 14/04/2016
 * Time: 18:18
 */
class Zab_Storepickup_Model_Carrier_Storepickup extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{

    protected $_code = 'zabstorepickup';
    protected $_isFixed = true;

    /**
     * Enter description here...
     *
     * @param Mage_Shipping_Model_Rate_Request $data
     * @return Mage_Shipping_Model_Rate_Result
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
{
    if (!$this->getConfigFlag('active')) {
        return false;
    }

    $freeBoxes = 0;
    if ($request->getAllItems()) {
        foreach ($request->getAllItems() as $item) {

            if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                continue;
            }

            if ($item->getHasChildren() && $item->isShipSeparately()) {
                foreach ($item->getChildren() as $child) {
                    if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                        $freeBoxes += $item->getQty() * $child->getQty();
                    }
                }
            } elseif ($item->getFreeShipping()) {
                $freeBoxes += $item->getQty();
            }
        }
    }
    $this->setFreeBoxes($freeBoxes);

    $result = Mage::getModel('shipping/rate_result');
    if ($this->getConfigData('type') == 'O') { // per order
        $shippingPrice = $this->getConfigData('price');
    } elseif ($this->getConfigData('type') == 'I') { // per item
        $shippingPrice = ($request->getPackageQty() * $this->getConfigData('price')) - ($this->getFreeBoxes() * $this->getConfigData('price'));
    } else {
        $shippingPrice = false;
    }

    $shippingPrice = $this->getFinalPriceWithHandlingFee($shippingPrice);

    if ($shippingPrice !== false) {
        $method = Mage::getModel('shipping/rate_result_method');

        $method->setCarrier('zabstorepickup');
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod('storepickup');
        $method->setMethodTitle($this->getConfigData('name'));

        if ($request->getFreeShipping() === true || $request->getPackageQty() == $this->getFreeBoxes()) {
            $shippingPrice = '0.00';
        }


        $method->setPrice($shippingPrice);
        $method->setCost($shippingPrice);
        $address = $this->getAddress();
        $additional = false;
        if($address){
            /**@var $address Mage_Customer_Model_Address**/
            $address = $address->format('html');
            $additional=Mage::helper('zab_storepickup')->__("Pickup in:")."<br/>"."<address>{$address}</address><br/>";
        }
        if($this->getConfigData('instruction')){
            $additional .= $this->getConfigData('instruction');
        }
        $method->setAdditionalInfo($additional);
        $result->append($method);
    }

    return $result;
}

    public function getAddress(){
        $address = Mage::getModel('customer/address');
        $address->setFirstname(Mage::helper("zab_storepickup")->__("Store"));
        $address->setLastname($this->getConfigData('store_name'));
        $address->setStreet($this->getConfigData('store_street'));
        $address->setCity($this->getConfigData('store_city'));
        $address->setPostcode($this->getConfigData('store_postcode'));
        $address->setCountryId($this->getConfigData('country_id'));
        $address->setRegionId($this->getConfigData('region_id'));
        $address->setTelephone($this->getConfigData('store_phone'));
        $address->setEmail($this->getConfigData('store_email'));
        return $address;
    }
    public function getAllowedMethods()
{
    return array('storepickup'=>$this->getConfigData('name'));
}

}