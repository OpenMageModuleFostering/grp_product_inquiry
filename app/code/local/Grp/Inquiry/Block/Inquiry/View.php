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
 * Inquiry view block
 *
 * @category    Grp
 * @package     Grp_Inquiry
 * @author      Grapes-solutions.com
 */
class Grp_Inquiry_Block_Inquiry_View
    extends Mage_Core_Block_Template {
    /**
     * get the current inquiry
     * @access public
     * @return mixed (Grp_Inquiry_Model_Inquiry|null)
     * @author Grapes-solutions.com
     */
    public function getCurrentInquiry(){
        return Mage::registry('current_inquiry');
    }
}
