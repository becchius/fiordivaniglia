<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 03/09/13
 * Time: 14.47
 * To change this template use File | Settings | File Templates.
 */

class Zab_Billing_Model_Config_Formato extends Mage_Core_Model_Config_Data{
    protected function _beforeSave()
    {
        $val = $this->getValue();
        if(stripos($val,'$A')===false  || stripos($val,'$N')===false){
            throw new Mage_Core_Exception(Mage::helper("zab_billing")->__('Formato numero non valido!'));
        }
        return $this;
    }
}