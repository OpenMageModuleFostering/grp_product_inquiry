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
 * Inquiry model
 *
 * @category    Grp
 * @package     Grp_Inquiry
 * @author      Grapes-solutions.com
 */
class Grp_Inquiry_Model_Inquiry
    extends Mage_Core_Model_Abstract {
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'grp_inquiry_inquiry';
    const CACHE_TAG = 'grp_inquiry_inquiry';
    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'grp_inquiry_inquiry';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'inquiry';
    /**
     * constructor
     * @access public
     * @return void
     * @author Grapes-solutions.com
     */
    public function _construct(){
        parent::_construct();
        $this->_init('grp_inquiry/inquiry');
    }
    /**
     * before save inquiry
     * @access protected
     * @return Grp_Inquiry_Model_Inquiry
     * @author Grapes-solutions.com
     */
    protected function _beforeSave(){
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()){
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }
    /**
     * get the url to the inquiry details page
     * @access public
     * @return string
     * @author Grapes-solutions.com
     */
    public function getInquiryUrl(){
        return Mage::getUrl('grp_inquiry/inquiry/view', array('id'=>$this->getId()));
    }
    /**
     * save inquiry relation
     * @access public
     * @return Grp_Inquiry_Model_Inquiry
     * @author Grapes-solutions.com
     */
    protected function _afterSave() {
        return parent::_afterSave();
    }
    /**
     * check if comments are allowed
     * @access public
     * @return array
     * @author Grapes-solutions.com
     */
    public function getAllowComments() {
        if ($this->getData('allow_comment') == Grp_Inquiry_Model_Adminhtml_Source_Yesnodefault::NO) {
            return false;
        }
        if ($this->getData('allow_comment') == Grp_Inquiry_Model_Adminhtml_Source_Yesnodefault::YES) {
            return true;
        }
        return Mage::getStoreConfigFlag('grp_inquiry/inquiry/allow_comment');
    }
    /**
     * get default values
     * @access public
     * @return array
     * @author Grapes-solutions.com
     */
    public function getDefaultValues() {
        $values = array();
        $values['status'] = 1;
        $values['allow_comment'] = Grp_Inquiry_Model_Adminhtml_Source_Yesnodefault::USE_DEFAULT;
        return $values;
    }
}
