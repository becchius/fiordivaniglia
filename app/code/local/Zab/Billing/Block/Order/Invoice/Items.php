<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 11/09/13
 * Time: 16.25
 * To change this template use File | Settings | File Templates.
 */

class Zab_Billing_Block_Order_Invoice_Items extends Mage_Sales_Block_Order_Invoice_Items{


    public function getPrintInvoiceUrl($invoice)
    {
        $id= $invoice->getOrder()->getId().':'.$invoice->getId();
        $id = base64_encode($id);
        return Mage::getUrl('zab_billing/stampa/printInvoice', array('id' => $id));
    }

    public function getPrintAllInvoicesUrl($order)
    {
        $id= $order->getId().':'.$order->getCustomerEmail();
        $id = base64_encode($id);
        return Mage::getUrl('zab_billing/stampa/printAllInvoice', array('id' => $id));
    }
}