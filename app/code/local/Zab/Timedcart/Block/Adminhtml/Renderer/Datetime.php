<?php

/**
 * Created by PhpStorm.
 * User: andrea.becchio
 * Date: 29/09/2015
 * Time: 11:17
 */
class Zab_Timedcart_Block_Adminhtml_Renderer_Datetime extends Varien_Data_Form_Element_Date
{
    /**
     * Construct
     *
     * @param array $attributes
     */
    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        $this->setTime(true);
    }

    /**
     * Retrieve date format
     *
     * @return string
     */
    public function getFormat()
    {
        return Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
    }

    public function getValue($format = null)
    {
        if (empty($this->_value)) {
            return '';
        }
        if (null === $format) {
            $format = $this->getFormat();
        }

        return Mage::app()->getLocale()->storeDate(Mage::app()->getStore(),$this->_value,true,$format);
       // return $this->_value->toString($format);
    }
}
