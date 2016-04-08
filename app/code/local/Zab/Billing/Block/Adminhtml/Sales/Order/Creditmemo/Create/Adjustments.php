<?php
/**
 * Created by PhpStorm.
 * User: Andrea Becchio
 * Date: 13/01/15
 * Time: 16.05
 */


class Zab_Billing_Block_Adminhtml_Sales_Order_Creditmemo_Create_Adjustments extends Mage_Adminhtml_Block_Sales_Order_Creditmemo_Create_Adjustments{

    protected function _prepareLayout()
    {
        $onclick = "submitAndReloadArea($('creditmemo_item_container'),'".$this->getUpdateUrl()."')";
        $this->setChild(
            'update_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
                'label'     => Mage::helper('zab_billing')->__('Aggiorna totali'),
                'class'     => 'update-button',
                'onclick'   => $onclick,
            ))
        );
        return parent::_prepareLayout();
    }
    protected function _getCreditmemo()
    {
        return Mage::registry('current_creditmemo');
    }

    public function getUpdateUrl()
    {
        return $this->getUrl('*/*/updateQty', array(
            'order_id'=>$this->_getCreditmemo()->getOrderId(),
            'invoice_id'=>$this->getRequest()->getParam('invoice_id', null),
        ));
    }
    public function getUpdateButtonHtml()
    {
        return $this->getChildHtml('update_button');
    }

    public function initTotals()
    {
        $parent = $this->getParentBlock();

        $parent->removeTotal('phoenix_cashondelivery');
        parent::initTotals();



        return $this;
    }

    public function getCodLabel(){
        return "Rimborso Contrassegno";
    }

    public function getCodAmount(){
        $source = $this->getSource();
        return Mage::app()->getStore()->roundPrice($source->getCodFee() + $source->getCodTaxAmount()) * 1;
    }
} 