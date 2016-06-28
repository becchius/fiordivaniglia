<?php
/**
 * Created by PhpStorm.
 * User: andrea.becchio
 * Date: 25/01/2016
 * Time: 18:20
 */
/**@var Mage_Catalog_Model_Resource_Setup $installer**/
$installer = $this;
$installer->startSetup();

$attributes = array(

    "date_available"=>array(
        'type' => 'datetime',
        'label'=> 'Date Available',
        'input' => 'date',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'default' => null,
        "position"=>6,
        "wysiwyg_enabled"=>false,
        'group' => "Attributi Aggiuntivi",
        "is_html_allowed_on_front"=>false,
        'is_filterable_in_search'=>false,
        'used_in_product_listing'=>false,
        'used_for_sort_by'              => false,
        'is_configurable'               => false,
        'is_used_for_promo_rules'       => true,
        'is_filterable'                 => false,
    ),

);
foreach($attributes as $code=>$attribute){
    $installer->addAttribute(Mage_Catalog_Model_Product::ENTITY,$code,$attribute);

}