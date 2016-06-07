<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 09/09/13
 * Time: 14.41
 * To change this template use File | Settings | File Templates.
 */

class Zab_Billing_Model_Pdf_Invoice extends Zab_Billing_Model_Pdf_Abstract{
    protected function _drawHeader(Zend_Pdf_Page $page,$isFattura  = true)
    {
        /* Add table head */
        $this->_setFontRegular($page, 10);
        $page->setFillColor(new Zend_Pdf_Color_RGB(0.93, 0.92, 0.92));
        $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $this->y, 570, $this->y -15);
        $this->y -= 10;
        $page->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
        $skuPos = 290;
        $pricePos = 375;
        $qtyPos =435;
        if($isFattura){
            $skuPos = 255;
            $pricePos = 330;
            $qtyPos =380;
        }

        //columns headers
        $lines[0][] = array(
            'text' => Mage::helper('sales')->__('Products'),
            'feed' => 35
        );

        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('SKU'),
            'feed'  => $skuPos,
            'align' => 'right'
        );

        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('Price'),
            'feed'  => $pricePos,
            'align' => 'right'
        );
        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('Qty'),
            'feed'  => $qtyPos,
            'align' => 'right'
        );
        if($isFattura) {
            $lines[0][] = array(
                'text' => Mage::helper('sales')->__('Discount'),
                'feed' => 435,
                'align' => 'right'
            );

            $lines[0][] = array(
                'text' => Mage::helper('sales')->__('Tax'),
                'feed' => 495,
                'align' => 'right'
            );
        }
        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('Subtotal'),
            'feed'  => 565,
            'align' => 'right'
        );

        $lineBlock = array(
            'lines'  => $lines,
            'height' => 5
        );

        $this->drawLineBlocks($page, array($lineBlock), array('table_header' => true));
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
        $this->y -= 20;
    }

    /**
     * Return PDF document
     *
     * @param  array $invoices
     * @return Zend_Pdf
     */
    public function getPdf($invoices = array())
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('invoice');
        $helper = Mage::helper('zab_billing');
        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

        foreach ($invoices as $invoice) {
            if ($invoice->getStoreId()) {
                Mage::app()->getLocale()->emulate($invoice->getStoreId());
                Mage::app()->setCurrentStore($invoice->getStoreId());
            }
            $page  = $this->newPage();
            $order = $invoice->getOrder();
            /* Add image */
            $this->insertLogo($page, $invoice->getStore());
            /* Add address */
            $this->insertAddress($page, $invoice->getStore());
            /* Add head */
            $this->insertOrder(
                $page,
                $order,
                Mage::getStoreConfigFlag(self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID, $order->getStoreId())
            );
            /* Add document text and number */
            $prefix = "Nota di Consegna n.";
            $isFattura = $helper->isFattura($invoice->getTipo());
            if($isFattura){
                $prefix = "Fattura n.";
            }
            $this->insertDocumentNumber(
                $page,
                $prefix ." ". $invoice->getIncrementId() .' del '.Mage::helper('core')->formatDate(
                    $invoice->getCreatedAtStoreDate(), 'medium', false
                )
            );
            /* Add table */
            $this->_drawHeader($page,$isFattura);
            /* Add body */
            foreach ($invoice->getAllItems() as $item){
                if ($item->getOrderItem()->getParentItem()) {
                    continue;
                }
                /* Draw item */
                $this->_drawItem($item, $page, $order);
                $page = end($pdf->pages);
            }
            /* Add totals */
            $this->insertTotals($page, $invoice);
            if ($invoice->getStoreId()) {
                Mage::app()->getLocale()->revert();
            }
        }
        $this->_afterGetPdf();
        return $pdf;
    }
}