<?php
/**
 * Created by PhpStorm.
 * User: Andrea Becchio
 * Date: 26/03/15
 * Time: 12.28
 */

class Zab_Billing_Model_Sales_Order_Creditmemo_Total_Tax extends Mage_Sales_Model_Order_Creditmemo_Total_Tax{
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
           $order = $creditmemo->getOrder();

        $orderTaxPerc = 0;
        $taxDetails = $order->getFullTaxInfo();

        if(count($taxDetails)){
            $det = array_pop($taxDetails);
            $orderTaxPerc = $det['percent'];
        }
        // Adding adjustment tax amounts to total tax
        $totalAdjusment = $creditmemo->getAdjustmentPositive()-$creditmemo->getAdjustmentNegative();
        $baseTotalAdjusment = $creditmemo->getBaseAdjustmentPositive()-$creditmemo->getBaseAdjustmentNegative();

        // Adjustment values already include tax in my case. Modify calculation if you're entering values without tax
        $totalAdjusmentTax = $totalAdjusment-($totalAdjusment*100) / (100 + $orderTaxPerc);
        $baseTotalAdjusmentTax = $baseTotalAdjusment-($baseTotalAdjusment*100) / (100 + $orderTaxPerc);


        parent::collect($creditmemo);
        $creditmemo->setTaxAmount($creditmemo->getTaxAmount()+$totalAdjusmentTax);
        $creditmemo->setBaseTaxAmount($creditmemo->getBaseTaxAmount()+$baseTotalAdjusmentTax);
        return $this;

    }
} 