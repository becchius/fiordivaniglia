<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 03/09/13
 * Time: 13.16
 * To change this template use File | Settings | File Templates.
 */

class Zab_Billing_Model_Resource_Numerazione extends Mage_Core_Model_Resource_Db_Abstract{

    protected function _construct()
    {
        $this->_init("zab_billing/numerazione", "id");
    }
}