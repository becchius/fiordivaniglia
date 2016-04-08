<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 03/09/13
 * Time: 11.25
 * To change this template use File | Settings | File Templates.
 */

class Zab_Billing_Model_Sales_Service_Order extends Mage_Sales_Model_Service_Order{

    public function prepareInvoice($qtys = array())
    {
        $invoice = parent::prepareInvoice($qtys);

        if($invoice){

            if($this->getOrder()->getBillingAddress()->getRichiestaFattura()){
                $invoice->setTipo(Zab_Billing_Model_Tipodoc::FATTURA);
            }else{
                $invoice->setTipo(Zab_Billing_Model_Tipodoc::NDC);
            }
        }
        return $invoice;
    }

    public function prepareInvoiceCreditmemo($invoice, $data = array())
    {
        $creditmemo = parent::prepareInvoiceCreditmemo($invoice,$data);
        if($creditmemo){
            if($invoice->getTipo() == Zab_Billing_Model_Tipodoc::FATTURA){
                $creditmemo->setTipo(Zab_Billing_Model_Tipodoc::CREDITMEMO);
            }else{
                $creditmemo->setTipo(Zab_Billing_Model_Tipodoc::CREDITMEMO_NDC);
            }
        }
        return $creditmemo;
    }

    public function prepareCreditmemo($data = array())
    {
        $creditmemo = parent::prepareCreditmemo($data);

        if($creditmemo){

            if($this->getOrder()->getBillingAddress()->getRichiestaFattura()){
                $creditmemo->setTipo(Zab_Billing_Model_Tipodoc::CREDITMEMO);
            }else{
                $creditmemo->setTipo(Zab_Billing_Model_Tipodoc::CREDITMEMO_NDC);
            }
        }
        return $creditmemo;
    }

    protected function _initCreditmemoData($creditmemo, $data)
    {
        $this->updateLocaleNumbers($data);
        if (isset($data['shipping_amount'])) {
            $creditmemo->setBaseShippingAmount((float)$data['shipping_amount']);
        }

        if (isset($data['cod_amount'])) {
            $creditmemo->setBaseCodFee((float)$data['cod_amount']);
        }

        if (isset($data['adjustment_positive'])) {
            $creditmemo->setAdjustmentPositive($data['adjustment_positive']);
        }

        if (isset($data['adjustment_negative'])) {
            $creditmemo->setAdjustmentNegative($data['adjustment_negative']);
        }
    }

}