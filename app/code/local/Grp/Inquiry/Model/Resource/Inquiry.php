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
 * Inquiry resource model
 *
 * @category    Grp
 * @package     Grp_Inquiry
 * @author      Grapes-solutions.com
 */
class Grp_Inquiry_Model_Resource_Inquiry
    extends Mage_Core_Model_Resource_Db_Abstract {
    /**
     * constructor
     * @access public
     * @author Grapes-solutions.com
     */
    public function _construct(){
        $this->_init('grp_inquiry/inquiry', 'entity_id');
    }
    /**
     * Get store ids to which specified item is assigned
     * @access public
     * @param int $inquiryId
     * @return array
     * @author Grapes-solutions.com
     */
    public function lookupStoreIds($inquiryId){
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getTable('grp_inquiry/inquiry_store'), 'store_id')
            ->where('inquiry_id = ?',(int)$inquiryId);
        return $adapter->fetchCol($select);
    }
    /**
     * Perform operations after object load
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return Grp_Inquiry_Model_Resource_Inquiry
     * @author Grapes-solutions.com
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object){
        if ($object->getId()) {
            //$stores = $this->lookupStoreIds($object->getId());
            //$object->setData('store_id', $stores);
        }
        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param Grp_Inquiry_Model_Inquiry $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object){
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId());
            $select->join(
                array('inquiry_inquiry_store' => $this->getTable('grp_inquiry/inquiry_store')),
                $this->getMainTable() . '.entity_id = inquiry_inquiry_store.inquiry_id',
                array()
            )
            ->where('inquiry_inquiry_store.store_id IN (?)', $storeIds)
            ->order('inquiry_inquiry_store.store_id DESC')
            ->limit(1);
        }
        return $select;
    }
    /**
     * Assign inquiry to store views
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return Grp_Inquiry_Model_Resource_Inquiry
     * @author Grapes-solutions.com
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object){
       
        return parent::_afterSave($object);
    }}
