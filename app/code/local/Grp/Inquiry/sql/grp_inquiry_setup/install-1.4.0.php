<?php
/**
 * Grp_Inquiry extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Grp
 * @package        Grp_Inquiry
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Inquiry module install script
 *
 * @category    Grp
 * @package     Grp_Inquiry
 * @author      Grapes-solutions.com
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('grp_inquiry/inquiry'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Inquiry ID')
    ->addColumn('inquiries', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'inquiries')

    ->addColumn('first_name', Varien_Db_Ddl_Table::TYPE_TEXT, 500, array(
        ), 'Send inquiry?')

    ->addColumn('last_name', Varien_Db_Ddl_Table::TYPE_TEXT, 500, array(
        ), 'Enabled')
    ->addColumn('email', Varien_Db_Ddl_Table::TYPE_TEXT, 500, array(
        ), 'Enabled')

    ->addColumn('phone', Varien_Db_Ddl_Table::TYPE_TEXT, 500, array(
        ), 'Enabled')
    
     ->addColumn('email', Varien_Db_Ddl_Table::TYPE_TEXT, 1000, array(
        ), 'Enabled')
   
     ->addColumn('status', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        ), 'Inquiry Status')
        
     ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        ), 'Product entity_id')
        
        ->addColumn('sku', Varien_Db_Ddl_Table::TYPE_VARCHAR, 500, array(
        ), 'Enabled')
    
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Inquiry Creation Time') 
    ->setComment('Inquiry Table');
$this->getConnection()->createTable($table);

$this->addAttribute('catalog_product', 'inquiry', array(
    'group'             => 'General',
    'backend'           => '',
    'frontend'          => '',
    'class'             => '',
    'default'           => 0,
    'label'             => 'Send inquiry?',
    'input'             => 'select',
    'type'              => 'int',
    'source'            => 'eav/entity_attribute_source_boolean',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'is_visible'        => 1,
    'required'          => 0,
    'searchable'        => 0,
    'filterable'        => 0,
    'unique'            => 0,
    'comparable'        => 0,
    'visible_on_front'  => 1,
    'user_defined'      => 1,
    'used_in_product_listing'   => 1
));
$this->endSetup();
