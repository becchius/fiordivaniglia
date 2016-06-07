<?php
/**
 * Created by PhpStorm.
 * User: Andrea Becchio
 * Date: 16/02/15
 * Time: 11.31
 */

class Zab_Billing_Model_Sales_Pdf_Discount extends Mage_Sales_Model_Order_Pdf_Total_Default
{

    public function getAmount()
    {
        $disc = parent::getAmount();
        $hidden = $this->getSource()->getHiddenTaxAmount();
        if($disc > 0) {
             $disc -= $hidden;
         }else {
             $disc += $hidden;
         }
        return $disc;
    }


} 