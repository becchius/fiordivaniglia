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
$table = $installer->getTable('zab_timedcart/timedcart_item');
$installer->getConnection()->addColumn($table,'is_deleted',array(
    'type'=>Varien_Db_Ddl_Table::TYPE_INTEGER,
    'length'=>5,
    'nullable'=>true,
    'default'=>0,
        'comment'=>'deleted')
);
$installer->getConnection()->addColumn($table,'error',array(
    'type'=>Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'=>255,
    'nullable'=>true,
    'default'=>null,
    'comment'=>'error')
);
$installer->endSetup();
