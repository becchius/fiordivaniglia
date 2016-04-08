<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 06/09/13
 * Time: 17.42
 * To change this template use File | Settings | File Templates.
 */

class Zab_Billing_Block_Adminhtml_Sales_Order_Invoice_Create extends Mage_Adminhtml_Block_Sales_Order_Invoice_Create{
    public function getHeaderText()
    {

        if($this->getInvoice()->getTipo()==Zab_Billing_Model_Tipodoc::FATTURA){
            $testo = "Nuova Fattura";
        }else{
            $testo = "Nuova Nota di Consegna";
        }
        return ($this->getInvoice()->getOrder()->getForcedDoShipmentWithInvoice())
            ? Mage::helper('sales')->__($testo.' e spedizione per ordine #%s', $this->getInvoice()->getOrder()->getRealOrderId())
            : Mage::helper('sales')->__($testo.' per ordine #%s', $this->getInvoice()->getOrder()->getRealOrderId());  }
}