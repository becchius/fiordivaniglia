<?php

/**
 * Created by PhpStorm.
 * User: andrea.becchio
 * Date: 29/09/2015
 * Time: 11:22
 */
class Zab_Timedcart_Model_Attribute_Backend_Datetime extends Mage_Eav_Model_Entity_Attribute_Backend_Datetime
{
    /**
     * Format date
     *
     * @param string|int $date
     * @return string
     */
    public function formatDate($date)
    {
        if (empty($date)) {
            return null;
        }
        $date = Mage::app()->getLocale()->utcDate(Mage::app()->getStore(),$date,true,
            Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT));

        return $date->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
    }
}