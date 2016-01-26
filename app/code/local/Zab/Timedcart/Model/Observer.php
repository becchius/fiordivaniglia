<?php

/**
 * Created by PhpStorm.
 * User: andrea.becchio
 * Date: 15/01/2016
 * Time: 18:16
 */
class Zab_Timedcart_Model_Observer extends Varien_Object
{

    public function checkTimedItem($observer){

        /**@var Mage_Sales_Model_Quote_Item $item**/
        $items = $observer->getItems();
        $errors = array();
        foreach($items as $item) {
            if($item->getProductType()!= 'simple'){
                continue;
            }
            //check if object is reserved
            $ti = Mage::getModel('zab_timedcart/timedcart_item')->setQuoteItem($item);
            //remove expired
            $ti->deleteExpiredItem();
            $stat = $ti->checkProductStatus();
            if ($stat['status'] != Zab_Timedcart_Model_Timedcart_Item::RESERVATION_STATUS_FREE) {
                $message = "Product " . $item->getProduct()->getName() . " reserved.";
                $item->setMessage($message);
                $errors[] = $message;
            }
        }

        if (!empty($errors)) {
            Mage::throwException(implode("\n", $errors));
        }
    }

    public function saveReservation($observer){
        $item = $observer->getItem();
        if($item->getProductType()!= 'simple'){
            return $this;
        }
        $ti =  Mage::getModel('zab_timedcart/timedcart_item')->load($item->getId(),'quote_item_id');
        if(!$ti->getId()){
            $ti->setQuoteItem($item);
            $ti->save();
        }
        return $this;



    }

    public function checkExpires($observer){
        /**@var Mage_Sales_Model_Quote $quote**/
        $quote  = $observer->getQuote();
        if($quote->getSkipAfterLoad()){
            $quote->unsSkipAfterLoad();
            return $this;
        }
        foreach($quote->getAllItems() as $item){
            if($item->getProductType()!= 'simple'){
                continue;
            }
            //check if object is reserved
            $ti = Mage::getModel('zab_timedcart/timedcart_item')->load($item->getId(),'quote_item_id');
            if(!$ti->getId()){
                continue;
            }
            if($ti->isExpired()){
                $message = "Product " . $item->getProduct()->getName() . " has expired.";


            }else {

                $message = "Product " . $item->getProduct()->getName() . "expires at ".$ti->getFormattedExpireDate();

            }

            $item->setMessage($message);
            if($item->getParentItem()){
                $item->getParentItem()->setMessage($message);
            }

        }


        return $this;
    }

    public function extendTimer($observer){
        $checkout  = $observer->getControllerAction();
        $quote = $checkout->getOnepage()->getQuote();
        foreach($quote->getAllItems() as $item) {
            if ($item->getProductType() != 'simple') {
                continue;
            }
            //check if object is reserved
            $ti = Mage::getModel('zab_timedcart/timedcart_item')->load($item->getId(), 'quote_item_id');
            if (!$ti->getId()) {
                continue;
            }
            $ti->extendTimer();
            $ti->save();
        }
    }
    public function revertTimer($observer){
        $quote = $observer->getQuote();
        foreach($quote->getAllItems() as $item) {
            if ($item->getProductType() != 'simple') {
                continue;
            }
            //check if object is reserved
            $ti = Mage::getModel('zab_timedcart/timedcart_item')->load($item->getId(), 'quote_item_id');
            if (!$ti->getId()) {
                continue;
            }
            $ti->revertTimer();
            $ti->save();
        }
    }

    public function setTimerActive($observer){
        $quote = $observer->getQuote();
        Mage::getModel('zab_timedcart/timedcart_item')->updateActive($quote);
        return $this;
    }

    public function checkDeletedItems($observer){
       $quote = Mage::getSingleton('checkout/cart')->getQuote();
        if($quote && $quote->getId()){
            Mage::helper('zab_timedcart')->checkDeletedItemd($quote);
        }


    }

    public function checkProductStatus($observer){

    }

    public function __call($method, $args)
    {
       if(!Mage::helper('zab_timedcart')->isActiveTimeout()){
            return $this;
        }

        return parent::__call($method,$args);
    }

}