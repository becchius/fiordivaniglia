<?php
/**
 * Created by PhpStorm.
 * User: Andrea Becchio
 * Date: 16/02/15
 * Time: 11.31
 */

class Zab_Billing_Model_Sales_Pdf_Grandtotal extends Mage_Sales_Model_Order_Pdf_Total_Default
{
    /**
     * Check if tax amount should be included to grandtotals block
     * array(
     *  $index => array(
     *      'amount'   => $amount,
     *      'label'    => $label,
     *      'font_size'=> $font_size
     *  )
     * )
     * @return array
     */
    public function getTotalsForDisplay()
    {
        $store = $this->getOrder()->getStore();
        $config= Mage::getSingleton('tax/config');

        $amount = $this->getOrder()->formatPriceTxt($this->getAmount());
        $amountExclTax = $this->getAmount() - $this->getSource()->getTaxAmount();
        $amountExclTax = ($amountExclTax > 0) ? $amountExclTax : 0;
        $amountExclTax = $this->getOrder()->formatPriceTxt($amountExclTax);
        $tax = $this->getOrder()->formatPriceTxt($this->getSource()->getTaxAmount());
        $fontSize = $this->getFontSize() ? $this->getFontSize() : 7;

        $totals = array(array(
            'amount'    => $this->getAmountPrefix().$amountExclTax,
            'label'     => Mage::helper('tax')->__('Totale Iva Escl.') . ':',
            'font_size' => $fontSize,
             'small'=>true
        ));

        if ($config->displaySalesFullSummary($store)) {
            $totals = array_merge($totals, $this->getFullTaxInfo());
        }
        $taxs = $this->getOrder()->getFullTaxInfo();
        if(count($tax)){
            $taxs = array_pop($taxs);
            $percent = $taxs['percent'];
        }else{
            $percent = 0;
        }

        $iva = intval($percent)."%";
        $totals[] = array(
            'amount'    => $this->getAmountPrefix().$tax,
            'label'     => Mage::helper('tax')->__('IVA '.$iva) . ':',
            'font_size' => $fontSize,
             'small'=>true
        );
        $totals[] = array(
            'amount'    => $this->getAmountPrefix().$amount,
            'label'     => Mage::helper('tax')->__('Totale IVA Incl.') . ':',
            'font_size' => $fontSize,

        );
        return $totals;
    }

} 