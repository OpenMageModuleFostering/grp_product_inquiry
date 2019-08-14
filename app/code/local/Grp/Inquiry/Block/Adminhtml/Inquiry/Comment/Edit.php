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
 * Inquiry comment admin edit form
 *
 * @category    Grp
 * @package     Grp_Inquiry
 * @author      Grapes-solutions.com
 */
class Grp_Inquiry_Block_Adminhtml_Inquiry_Comment_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container {
    /**
     * constructor
     * @access public
     * @return void
     * @author Grapes-solutions.com
     */
    public function __construct(){
        parent::__construct();
        $this->_blockGroup = 'grp_inquiry';
        $this->_controller = 'adminhtml_inquiry_comment';
        $this->_updateButton('save', 'label', Mage::helper('grp_inquiry')->__('Save Inquiry comment'));
        $this->_updateButton('delete', 'label', Mage::helper('grp_inquiry')->__('Delete Inquiry comment'));
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('grp_inquiry')->__('Save And Continue Edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);
        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
    /**
     * get the edit form header
     * @access public
     * @return string
     * @author Grapes-solutions.com
     */
    public function getHeaderText(){
        if( Mage::registry('comment_data') && Mage::registry('comment_data')->getId() ) {
            return Mage::helper('grp_inquiry')->__("Edit Inquiry comment '%s'", $this->htmlEscape(Mage::registry('comment_data')->getTitle()));
        }
        return '';
    }
}
