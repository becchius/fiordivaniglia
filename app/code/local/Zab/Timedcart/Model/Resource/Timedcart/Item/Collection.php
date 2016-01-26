<?php
/**
 * Created by PhpStorm.
 * User: andrea.becchio
 * Date: 14/01/2016
 * Time: 07:37
 */ 
class Zab_Timedcart_Model_Resource_Timedcart_Item_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    protected function _construct()
    {
        $this->_init('zab_timedcart/timedcart_item');
    }

}