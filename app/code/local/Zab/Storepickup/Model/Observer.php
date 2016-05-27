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
       if($shippingAddress){
            if($address->getShippingMethod()=='zabstorepickup_storepickup'){
                $carrier = Mage::getSingleton('shipping/config')->getCarrierInstance('zabstorepickup');
                $storeAdd = $carrier->getAddress();
                if($storeAdd){
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

                     Mage::helper('core')->copyFieldset('sales_convert_quote_address', 'to_order_address', $address, $shippingAddress);
                        $shippingAddress->setCustomerAddress(null);
                        $shippingAddress->setCustomerAddressId(null);

                }
            }
        }
        return $this;
    }

}