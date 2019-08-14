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
 * Inquiry edit form
 *
 * @category    Grp
 * @package     Grp_Inquiry
 * @author      Grapes-solutions.com
 */


class Grp_Inquiry_Block_Adminhtml_Inquiry_Edit_Form
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare form
     * @access protected
     * @return Grp_Inquiry_Block_Adminhtml_Inquiry_Edit_Form
     * @author Grapes-solutions.com
     */
    protected function _prepareForm() {
        $form = new Varien_Data_Form(array(
                        'id'         => 'edit_form',
                        'action'     => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                        'method'     => 'post',
                        'enctype'    => 'multipart/form-data'
                    )
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
