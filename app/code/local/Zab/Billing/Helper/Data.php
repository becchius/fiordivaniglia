<?php
class Zab_Billing_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_PREFIX = 'global/pdf/totals';
    public function getTotalConfigPath($tipo){

        if(!$tipo){
            $tipo = Zab_Billing_Model_Tipodoc::NDC;
        }
        if($tipo==Zab_Billing_Model_Tipodoc::FATTURA || $tipo == Zab_Billing_Model_Tipodoc::CREDITMEMO){
            return self::XML_PATH_PREFIX;
        }
        return self::XML_PATH_PREFIX.'_ndc';

    }

    public function isFattura($tipo){
        if(!$tipo){
            $tipo = Zab_Billing_Model_Tipodoc::NDC;
        }
        if($tipo==Zab_Billing_Model_Tipodoc::FATTURA || $tipo == Zab_Billing_Model_Tipodoc::CREDITMEMO){
            return true;
        }
        return false;
    }
}
	 