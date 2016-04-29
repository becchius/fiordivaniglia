<?php

/**
 * Created by PhpStorm.
 * User: andrea.becchio
 * Date: 20/04/2016
 * Time: 18:23
 */
class Zab_Storepickup_Model_Observer
{
    public function updateShippingAddress($observer){
        $address =$observer->getAddress();
        $shippingAddress = $observer->getOrderAddress();
        if($address->getAddressType()!='shipping'){
            return $this;
        }
        Mage::log("CIAO");
        if($shippingAddress){
            Mage::log("Ciao 1 ".$address->getShippingMethod());
            if($address->getShippingMethod()=='zabstorepickup_storepickup'){
                Mage::log("Ciao 1");
                $carrier = Mage::getSingleton('shipping/config')->getCarrierInstance('zabstorepickup');
                $storeAdd = $carrier->getAddress();
                if($storeAdd){
                    Mage::log("ciao2");
                    $address->setCustomerAddress(null);
                    $address->setCustomerAddressId(null);
                    $address->setFirstname($storeAdd->getFirstname());
                    $address->setLastname($storeAdd->getLastname());
                    $address->setStreet($storeAdd->getStreet());
                    $address->setPostcode($storeAdd->getPostcode());
                    $address->setRegion($storeAdd->getRegion());
                    $address->setRegionId($storeAdd->getRegionId());
                    $address->setCity($storeAdd->getCity());
                    $address->setEmail($storeAdd->getEmail());
                    $address->setTelephone($storeAdd->getTelephone());
                    $address->setCountryId($storeAdd->getCountryId());
                    $address->setCompany(null);
                    $address->setSameAsBilling(0);
                    $address->setSaveInAddressBook(0);
                   /* $shippingAddress->setCustomerAddress(null);
                    $shippingAddress->setCustomerAddressId(null);
                    $shippingAddress->setFistname($storeAdd->getFirstname());
                    $shippingAddress->setLastname($storeAdd->getLastname());
                    $shippingAddress->setStreet($storeAdd->getStreet());
                    $shippingAddress->setPostcode($storeAdd->getPostcode());
                    $shippingAddress->setRegion($storeAdd->getRegion());
                    $shippingAddress->setId($storeAdd->getRegionId());
                    $shippingAddress->setCity($storeAdd->getCity());
                    $shippingAddress->setEmail($storeAdd->getEmail());
                    $shippingAddress->setTelephone($storeAdd->getTelephone());
                    $shippingAddress->setCountryId($storeAdd->getCountryId());
                    $shippingAddress->setSameAsBiling(0);
                    $shippingAddress->setSaveInAddressBook(0);*/

                     Mage::helper('core')->copyFieldset('sales_convert_quote_address', 'to_order_address', $address, $shippingAddress);
                        $shippingAddress->setCustomerAddress(null);
                        $shippingAddress->setCustomerAddressId(null);

                }
            }
        }
        return $this;
    }

}