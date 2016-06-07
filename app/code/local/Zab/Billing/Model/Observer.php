<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 30/08/13
 * Time: 16.47
 * To change this template use File | Settings | File Templates.
 */
class Zab_Billing_Model_Observer
{


    public function beforeSaveInvoice($observer){
        $invoice = $observer->getEvent()->getInvoice();
        if(!$invoice->getIncrementId()){
            $tipo = $invoice->getTipo();
            if(!$tipo){
                $tipo = Zab_Billing_Model_Tipodoc::NDC;
            }
            if(Mage::getStoreConfig('zab_billing/general/indipendenti')==Zab_Billing_Model_Config_Indipendenti::WEBSITE){
                $store_id = $invoice->getStore()->getWebsiteId();

            }else{
                $store_id = $invoice->getStoreId();
            }
            $incId = Mage::getModel('zab_billing/numerazione')->getNextIncrementId($tipo,$store_id);
            $invoice->setIncrementId($incId);
            $invoice->setData('save_increment',true);


        }

        return $this;

    }

    public function beforeSaveCreditmemo($observer){
        $creditmemo = $observer->getEvent()->getCreditmemo();
        if(!$creditmemo->getIncrementId()){

            $tipo = $creditmemo->getTipo();
            if(!$tipo){
                $tipo = Zab_Billing_Model_Tipodoc::CREDITMEMO_NDC;
            }
            if(Mage::getStoreConfig('zab_billing/general/indipendenti')==Zab_Billing_Model_Config_Indipendenti::WEBSITE){
                $store_id = $creditmemo->getStore()->getWebsiteId();

            }else{
                $store_id = $creditmemo->getStoreId();
            }
            $incId = Mage::getModel('zab_billing/numerazione')->getNextIncrementId($tipo,$store_id);
            $creditmemo->setIncrementId($incId);
            $creditmemo->setData('save_increment',true);

        }
        return $this;
    }

    public function aggiornaNumerazione($observer){
        $object = $observer->getDataObject();
        Mage::log("Invoice n. " . $object->getIncrementId(),null,'num_documenti.log');
        Mage::log($object->getCreatedAt(),null,'num_documenti.log');
        Mage::log($object->getUpdatedAt(),null,'num_documenti.log');

        Mage::log($object->getData('save_increment'),null,'num_documenti.log');

        if($object->getCreatedAt() == $object->getUpdatedAt() && $object->getData('save_increment')){
            $object->setData('save_increment',false);
            $tipo = $object->getTipo();
            if(!$tipo){
                if($object instanceof Mage_Sales_Model_Order_Invoice){
                    $tipo = Zab_Billing_Model_Tipodoc::NDC;
                }elseif($object instanceof Mage_Sales_Model_Order_Creditmemo){
                    $tipo = Zab_Billing_Model_Tipodoc::CREDITMEMO_NDC;
                }
            }
            if(Mage::getStoreConfig('zab_billing/general/indipendenti')==Zab_Billing_Model_Config_Indipendenti::WEBSITE){
                $store_id = $object->getStore()->getWebsiteId();

            }else{
                $store_id =$object->getStoreId();
            }
            $inc_id = Mage::getModel('zab_billing/numerazione')->getNextIncrementId($tipo,$store_id,true);
            Mage::log("Numero Salvato " . $inc_id,null,'num_documenti.log');

        }
        return $this;
    }
}
