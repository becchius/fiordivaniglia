<?php
/**
 * Created by PhpStorm.
 * User: andrea.becchio
 * Date: 14/01/2016
 * Time: 07:37
 */
class Zab_Timedcart_Model_Timedcart_Item extends Mage_Core_Model_Abstract
{
    const RESERVATION_STATUS_FREE = 0;
    const RESERVATION_STATUS_RESERVED = 1;
    const RESERVATION_STATUS_EXPIRED = 2;
    const RESERVATION_STATUS_IN_CART = 3;
    protected function _construct()
    {
        $this->_init('zab_timedcart/timedcart_item');
    }

    public function setQuoteItem(Mage_Sales_Model_Quote_Item $item){
        $this->setQuoteItemId($item->getId());
        $this->setQuoteId($item->getQuoteId());
        $this->setProductId($item->getProductId());
        $this->setStoreId($item->getStoreId());
        $this->setQty($item->getQty());
        $this->setIsActive(true);
        $expireDateTime = Mage::helper('zab_timedcart')->getExpireDatetime(now());
        $this->setExpireDatetime($expireDateTime);



        return $this;
    }

    public function checkProductStatus(){

        $status = $this->_getResource()->checkProductStatus($this->getProductId(),$this->getQuoteId(),$this->getQty());
        $toRet = array();
        if($status){
            $toRet['status']=self::RESERVATION_STATUS_RESERVED;
            $toRet['exp_date']=$status;
        }else{
            $toRet['status']=self::RESERVATION_STATUS_FREE;

        }

        return $status;
    }

    public function deleteExpiredItem(){
        $prodId = $this->getProductId();
        $toDel = $this->getResource()->getExpiredItems($prodId);
        foreach($toDel as $rec) {
            $quoteId = $rec['quote_id'];
            $itemId = $rec['quote_item_id'];
            $timedId = $rec['tc_item_id'];

            /**@var Mage_Sales_Model_Quote $quote * */
            $quote = Mage::getModel('sales/quote')->setSkipAfterLoad(true)->load($quoteId);
            $name = "";
            if ($quote->getId()) {
                $quote->removeItem($itemId);

                $quote->addErrorInfo('error', 'timed_cart', 0, Mage::getStoreConfig('zab_timedcart/general/expired_message'));
                $item = $quote->getItemById($itemId);
                if($item){
                    if($item->getParentItem()){
                        $name =  $item->getParentItem()->getName();
                    }else{
                        $name = $item->getName();
                    }
                }
                $quote->save();

            }
            $ti = Mage::getModel('zab_timedcart/timedcart_item')->load($timedId);
            $ti->setIsDeleted(1);
            $ti->setError($name);
            $ti->save();
        }
        return $this;


    }

    public function isExpired(){
        $time = time();
        $exp = strtotime($this->getExpireDatetime());
        return $exp < $time;
    }

    public function getFormattedExpireDate(){

        if(!$this->getExpireStoreDate()){
            return "";
        }
        $data = $this->getExpireStoreDate();

        return $data;


    }

    public function getExpireStoreDate()
    {
        return Mage::app()->getLocale()->storeDate(
            $this->getStore(),
            Varien_Date::toTimestamp($this->getExpireDatetime()),
            true
        );
    }
    public function getStore()
    {
        $storeId = $this->getStoreId();
        if ($storeId) {
            return Mage::app()->getStore($storeId);
        }
        return Mage::app()->getStore();
    }

    public function updateActive(Mage_Sales_Model_Quote $quote){

        $this->getResource()->updateActive($quote->getId(),$quote->getIsActive());
        return $this;
    }

    public function extendTimer($amount = null){
        if(!$amount){
            $amount = Mage::getStoreConfig('zab_timedcart/general/time_extension');
        }

        $date = new DateTime($this->getExpireDatetime());
        if($date->getTimestamp() < time()){
            $date = new DateTime();
        }
        $date = $date->add(new DateInterval("PT{$amount}S"));
        $this->setExpireDatetime($date->format("Y-m-d H:i:s"));
        return $this;

    }

    public function revertTimer($amount = null){
        if(!$amount){
            $amount = Mage::getStoreConfig('zab_timedcart/general/time_extension');
        }
        $date = new DateTime($this->getExpireDatetime());
        $date = $date->sub(new DateInterval("PT{$amount}S"));
        $this->setExpireDatetime($date->format("Y-m-d H:i:s"));
        return $this;

    }

    public function getDeletedItems($quoteId){

        $coll = $this->getCollection()->addFieldToFilter('quote_id',$quoteId)
            ->addFieldToFilter('is_deleted',1);
        $toRet = array();
        foreach($coll as $i){
            $toRet[] = $i->getError();
            $i->delete();
        }
        return $toRet;
    }

    public function checkProductStatusByProdId($prodId){
        $status = $this->_getResource()->checkProductStatus($prodId,0,1);
        $toRet = array();
        if($status){
            $toRet['status']=self::RESERVATION_STATUS_RESERVED;
           $date = new DateTime($status,new DateTimeZone('UTC'));
            $date->setTimezone(new DateTimeZone( Mage::app()->getStore()->getConfig('general/locale/timezone')));
            $toRet['exp_date']=$date->format('Y/m/d H:i:s');
        }else{
            $toRet['status']=self::RESERVATION_STATUS_FREE;

        }


        return $toRet;

    }

}