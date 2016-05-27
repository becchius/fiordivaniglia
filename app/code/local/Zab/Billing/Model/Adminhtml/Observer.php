<?php

/**
 * Created by PhpStorm.
 * User: andrea.becchio
 * Date: 08/03/2016
 * Time: 18:31
 */
class Zab_Billing_Model_Adminhtml_Observer
{
    public function appendCustomColumn(Varien_Event_Observer $observer)
    {
        $block = $observer->getBlock();
        if (!isset($block)) {
            return $this;
        }

        if ($block instanceof Mage_Adminhtml_Block_Sales_Invoice_Grid) {

            $block->addColumnAfter('tipo', array(
                'header'    => Mage::helper('sales')->__('Tipo'),
                'index'     => 'tipo',
                'type'      => 'options',
                'options'   =>Mage::getSingleton('zab_billing/tipodoc')->getAsOptionInvoice()
            ), 'increment_id');
        }
        if ($block instanceof Mage_Adminhtml_Block_Sales_Creditmemo_Grid) {

            $block->addColumnAfter('tipo', array(
                'header'    => Mage::helper('sales')->__('Tipo'),
                'index'     => 'tipo',
                'type'      => 'options',
                'options'   =>Mage::getSingleton('zab_billing/tipodoc')->getAsOptionCreditmemo()
            ), 'increment_id');
        }
        return $this;
    }
}