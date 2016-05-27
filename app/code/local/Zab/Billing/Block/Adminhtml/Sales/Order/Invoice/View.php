<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 06/09/13
 * Time: 17.42
 * To change this template use File | Settings | File Templates.
 */

class Zab_Billing_Block_Adminhtml_Sales_Order_Invoice_View extends Mage_Adminhtml_Block_Sales_Order_Invoice_View{
    public function getHeaderText()
    {
        if ($this->getInvoice()->getEmailSent()) {
            $emailSent = Mage::helper('sales')->__('the invoice email was sent');
        }
        else {
            $emailSent = Mage::helper('sales')->__('the invoice email is not sent');
        }
        if($this->getInvoice()->getTipo()==Zab_Billing_Model_Tipodoc::FATTURA){
            $testo = "Fattura";
        }else{
            $testo = "Nota di Consegna";
        }
        return Mage::helper('sales')->__($testo.' n. %1$s | %2$s | %4$s (%3$s)', $this->getInvoice()->getIncrementId(), $this->getInvoice()->getStateName(), $emailSent, $this->formatDate($this->getInvoice()->getCreatedAtDate(), 'medium', true));
    }


}