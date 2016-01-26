<?php
/**
 * Created by PhpStorm.
 * User: andrea.becchio
 * Date: 14/01/2016
 * Time: 07:16
 */

/**@var Mage_Catalog_Model_Resource_Setup $installer**/
$installer = $this;

$table = $installer->getTable('zab_timedcart/timedcart_item');
$ddl = $installer->getConnection()
    ->newTable($table)
    ->addColumn('tc_item_id',Varien_Db_Ddl_Table::TYPE_INTEGER,11,array('nullable'=>false,'primary'=>true,'identity'=>true),'Id')
    ->addColumn('quote_item_id',Varien_Db_Ddl_Table::TYPE_INTEGER,11,array('nullable'=>false),'quote_item_id')
    ->addColumn('quote_id',Varien_Db_Ddl_Table::TYPE_INTEGER,11,array('nullable'=>false),'quote_id')
    ->addColumn('product_id',Varien_Db_Ddl_Table::TYPE_INTEGER,11,array('nullable'=>false),'product_id')
    ->addColumn('store_id',Varien_Db_Ddl_Table::TYPE_INTEGER,11,array('nullable'=>false),'store_id')
    ->addColumn('qty',Varien_Db_Ddl_Table::TYPE_INTEGER,11,array('nullable'=>false),'qty')
    ->addColumn('expire_datetime',Varien_Db_Ddl_Table::TYPE_DATETIME,11,array('nullable'=>true),'expire_datetie')
    ->addColumn('is_active',Varien_Db_Ddl_Table::TYPE_SMALLINT,5,array('nullable'=>false,'default'=>1),'product_id')
    ->addForeignKey('ZAB_TC_ITEM_QUOTE_ID','quote_id',$installer->getTable('sales/quote'),'entity_id',Varien_Db_Ddl_Table::ACTION_CASCADE,Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey('ZAB_TC_ITEM_PRODUCT_ID','product_id',$installer->getTable('catalog/product'),'entity_id',Varien_Db_Ddl_Table::ACTION_CASCADE,Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addIndex('ZAB_TC_ITEM_IDX_QUOTE_ID_ITEM_ID',array('quote_item_id','quote_id'),array(Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE));

$installer->getConnection()->createTable($ddl);


