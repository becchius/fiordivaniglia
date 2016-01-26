<?php
/**
 * Created by PhpStorm.
 * User: andrea.becchio
 * Date: 14/01/2016
 * Time: 07:37
 */ 
class Zab_Timedcart_Model_Resource_Timedcart_Item extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('zab_timedcart/timedcart_item', 'tc_item_id');
    }

    public function checkProductStatus($prodId,$quoteId,$qty){
        $sel = $this->getReadConnection()->select()
            ->from(array('ti'=>$this->getMainTable()),array(
                "expire_datetime"=>new Zend_Db_Expr('MIN(ti.expire_datetime)'),
                "needs"=>new Zend_Db_Expr("(SUM(ti.qty) + {$qty}) -inv.qty")))
            ->join(array('inv'=>$this->getTable('cataloginventory/stock_item')),"ti.product_id = inv.product_id",array())
            ->where("ti.product_id = ?",$prodId)
           ->where("ti.expire_datetime > now()")
            ->where('ti.is_active = ?',1)
            ->where('ti.quote_id <> ?',$quoteId)
            ->group('ti.product_id')
            ->having("needs > 0");

        $exp = $this->getReadConnection()->fetchOne($sel);

        return $exp;



    }

    public function getExpiredItems($prodId){
        $sel = $this->getReadConnection()->select()
            ->from(array('ti'=>$this->getMainTable()),array(
                "tc_item_id","quote_id","quote_item_id"))
            ->where("ti.product_id = ?",$prodId)
            ->where("ti.expire_datetime < now()")
            ->where('ti.is_active = ?',1);


        $exp = $this->getReadConnection()->fetchAll($sel);

        return $exp;



    }

    public function updateActive($quote_id,$isActive){

        $this->_getWriteAdapter()->update($this->getMainTable(),array("is_active"=>$isActive),array("quote_id = ?"=>$quote_id));

    }

    public function extendTimer($quoteId,$amount){
        $this->_getWriteAdapter()->update($this->getMainTable(),array('expire_datetime'=>new Zend_Db_Expr('DATE_ADD(expire_datetime,INTERVAL '.$amount.' SECONDS )'),"quote_id = {$quoteId}"));
        return $this;
    }

    public function revertTimer($quoteId,$amount){
        $this->_getWriteAdapter()->update($this->getMainTable(),array('expire_datetime'=>new Zend_Db_Expr('DATE_SUB(expire_datetime,INTERVAL '.$amount.' SECONDS )'),"quote_id = {$quoteId}"));
        return $this;
    }


}