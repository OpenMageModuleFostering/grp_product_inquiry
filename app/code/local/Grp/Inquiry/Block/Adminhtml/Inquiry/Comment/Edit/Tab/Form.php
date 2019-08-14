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
 * Inquiry comment edit form tab
 *
 * @category    Grp
 * @package     Grp_Inquiry
 * @author      Grapes-solutions.com
 */
class Grp_Inquiry_Block_Adminhtml_Inquiry_Comment_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare the form
     * @access protected
     * @return Inquiry_Inquiry_Block_Adminhtml_Inquiry_Comment_Edit_Tab_Form
     * @author Grapes-solutions.com
     */
    protected function _prepareForm(){
        $inquiry = Mage::registry('current_inquiry');
        $comment    = Mage::registry('current_comment');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('comment_');
        $form->setFieldNameSuffix('comment');
        $this->setForm($form);
        $fieldset = $form->addFieldset('comment_form', array('legend'=>Mage::helper('grp_inquiry')->__('Comment')));
        $fieldset->addField('inquiry_id', 'hidden', array(
            'name'  => 'inquiry_id',
            'after_element_html' => '<a href="'.Mage::helper('adminhtml')->getUrl('adminhtml/inquiry_inquiry/edit', array('id'=>$inquiry->getId())).'" target="_blank">'.Mage::helper('grp_inquiry')->__('Inquiry').' : '.$inquiry->getInquiries().'</a>'
        ));
        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('grp_inquiry')->__('Title'),
            'name'  => 'title',
            'required'  => true,
            'class' => 'required-entry',
        ));
        $fieldset->addField('comment', 'textarea', array(
            'label' => Mage::helper('grp_inquiry')->__('Comment'),
            'name'  => 'comment',
            'required'  => true,
            'class' => 'required-entry',
        ));
        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('grp_inquiry')->__('Status'),
            'name'  => 'status',
            'required'  => true,
            'class' => 'required-entry',
            'values'=> array(
                array(
                    'value' => Grp_Inquiry_Model_Inquiry_Comment::STATUS_PENDING,
                    'label' => Mage::helper('grp_inquiry')->__('Pending'),
                ),
                array(
                    'value' => Grp_Inquiry_Model_Inquiry_Comment::STATUS_APPROVED,
                    'label' => Mage::helper('grp_inquiry')->__('Approved'),
                ),
                array(
                    'value' => Grp_Inquiry_Model_Inquiry_Comment::STATUS_REJECTED,
                    'label' => Mage::helper('grp_inquiry')->__('Rejected'),
                ),
            ),
        ));
        $configuration = array(
             'label' => Mage::helper('grp_inquiry')->__('Poster name'),
             'name'  => 'name',
             'required'  => true,
             'class' => 'required-entry',
        );
        if ($comment->getCustomerId()) {
            $configuration['after_element_html'] = '<a href="'.Mage::helper('adminhtml')->getUrl('adminhtml/customer/edit', array('id'=>$comment->getCustomerId())).'" target="_blank">'.Mage::helper('grp_inquiry')->__('Customer profile').'</a>';
        }
        $fieldset->addField('name', 'text', $configuration);
        $fieldset->addField('email', 'text', array(
            'label' => Mage::helper('grp_inquiry')->__('Poster e-mail'),
            'name'  => 'email',
            'required'  => true,
            'class' => 'required-entry',
        ));
        $fieldset->addField('customer_id', 'hidden', array(
            'name'  => 'customer_id',
        ));

        if (Mage::app()->isSingleStoreMode()){
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            Mage::registry('current_comment')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $form->addValues($this->getComment()->getData());
        return parent::_prepareForm();
    }
    /**
     * get the current comment
     * @access public
     * @return Grp_Inquiry_Model_Inquiry_Comment
     */
    public function getComment(){
        return Mage::registry('current_comment');
    }
}