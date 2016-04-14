<?php
/**
 * Created by PhpStorm.
 * User: andrea.becchio
 * Date: 13/01/2016
 * Time: 18:09
 */
class Zab_Timedcart_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getExpireDatetime($now){
        $diff = intval(Mage::getStoreConfig('zab_timedcart/general/timeout_product'));
        if(!$diff){
            return false;
        }
        $nowDate = new DateTime($now);
        $exp = $nowDate->add(new DateInterval('PT'.$diff.'S'));
        return $exp->format("Y-m-d H:i:s");


    }

    public function isActiveTimeout(){
        return Mage::getStoreConfig('zab_timedcart/general/active');
    }

    public function checkDeletedItemd($quote){

        if($deleted = Mage::getModel('zab_timedcart/timedcart_item')->getDeletedItems($quote->getId())){
            if(count($deleted)==1){
                $message = Mage::helper('zab_timedcart')->__("%s has been removed from cart because your reservation expired");
            }else{
                $message = Mage::helper('zab_timedcart')->__("%s have been removed from cart because your reservation expired");

            }
            Mage::getSingleton('checkout/session')->addError(sprintf($message,implode(", ",$deleted)));
        }

        return $this;
    }

    public function getProductStatus($prod){
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        if($quote->getId()){
            if($quote->getItemByProduct($prod)){
                return array('status'=>Zab_Timedcart_Model_Timedcart_Item::RESERVATION_STATUS_IN_CART);
            }

        }
        return Mage::getModel('zab_timedcart/timedcart_item')->checkProductStatusByProdId($prod->getId());
    }

    public function getMinExpDate(){
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        if($quote->getId()){
            $items = Mage::getModel('zab_timedcart/timedcart_item')->getCollection();
            $items->addFieldToFilter('quote_id',$quote->getId())
                ->addFieldToFilter('expire_datetime',array('gt'=>now()))
                ->addOrder('expire_datetime','ASC');
            $item = $items->getFirstItem();
            if($item->getId()){
                $date = new DateTime($item->getExpireDatetime(),new DateTimeZone('UTC'));
                $date->setTimezone(new DateTimeZone( Mage::app()->getStore()->getConfig('general/locale/timezone')));
                $toRet =$date->format('Y/m/d H:i:s');

                return $toRet;


            }
        }
        return false;
    }
}