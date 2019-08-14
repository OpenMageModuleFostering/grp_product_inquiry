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
 * Inquiry admin block
 *
 * @category    Grp
 * @package     Grp_Inquiry
 * @author      Grapes-solutions.com
 */
class Grp_Inquiry_Block_Adminhtml_Inquiry
    extends Mage_Adminhtml_Block_Widget_Grid_Container {
    /**
     * constructor
     * @access public
     * @return void
     * @author Grapes-solutions.com
     */
    public function __construct(){
        $this->_controller         = 'adminhtml_inquiry';
        $this->_blockGroup         = 'grp_inquiry';
        parent::__construct();
        $this->_headerText         = Mage::helper('grp_inquiry')->__('Inquiry');
        //$this->_updateButton('add', 'label', Mage::helper('grp_inquiry')->__('Add Inquiry'));
        $this->removeButton('add');
    }
}
