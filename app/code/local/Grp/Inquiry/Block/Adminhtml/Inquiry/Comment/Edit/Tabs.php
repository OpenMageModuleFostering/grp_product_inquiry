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
 * Inquiry comment admin edit tabs
 *
 * @category    Grp
 * @package     Grp_Inquiry
 * @author      Grapes-solutions.com
 */
class Grp_Inquiry_Block_Adminhtml_Inquiry_Comment_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs {
    /**
     * Initialize Tabs
     * @access public
     * @author Grapes-solutions.com
     */
    public function __construct() {
        parent::__construct();
        $this->setId('inquiry_comment_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('grp_inquiry')->__('Inquiry Comment'));
    }
    /**
     * before render html
     * @access protected
     * @return Grp_Inquiry_Block_Adminhtml_Inquiry_Edit_Tabs
     * @author Grapes-solutions.com
     */
    protected function _beforeToHtml(){
        $this->addTab('form_inquiry_comment', array(
            'label'        => Mage::helper('grp_inquiry')->__('Inquiry comment'),
            'title'        => Mage::helper('grp_inquiry')->__('Inquiry comment'),
            'content'     => $this->getLayout()->createBlock('grp_inquiry/adminhtml_inquiry_comment_edit_tab_form')->toHtml(),
        ));
        if (!Mage::app()->isSingleStoreMode()){
            $this->addTab('form_store_inquiry_comment', array(
                'label'        => Mage::helper('grp_inquiry')->__('Store views'),
                'title'        => Mage::helper('grp_inquiry')->__('Store views'),
                'content'     => $this->getLayout()->createBlock('grp_inquiry/adminhtml_inquiry_comment_edit_tab_stores')->toHtml(),
            ));
        }
        return parent::_beforeToHtml();
    }
    /**
     * Retrieve inquiry entity
     * @access public
     * @return Grp_Inquiry_Model_Inquiry_Comment
     * @author Grapes-solutions.com
     */
    public function getComment(){
        return Mage::registry('current_comment');
    }
}
