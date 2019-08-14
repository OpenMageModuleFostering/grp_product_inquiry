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
 * Inquiry admin controller
 *
 * @category    Grp
 * @package     Grp_Inquiry
 * @author      Grapes-solutions.com
 */
class Grp_Inquiry_Adminhtml_Inquiry_InquiryController
    extends Grp_Inquiry_Controller_Adminhtml_Inquiry {
    /**
     * init the inquiry
     * @access protected
     * @return Grp_Inquiry_Model_Inquiry
     */
    protected function _initInquiry(){
        $inquiryId  = (int) $this->getRequest()->getParam('id');
        $inquiry    = Mage::getModel('grp_inquiry/inquiry');
        if ($inquiryId) {
            $inquiry->load($inquiryId);
        }
        Mage::register('current_inquiry', $inquiry);
        return $inquiry;
    }
     /**
     * default action
     * @access public
     * @return void
     * @author Grapes-solutions.com
     */
    public function indexAction() {
        $this->loadLayout();
        $this->_title(Mage::helper('grp_inquiry')->__('Inquiry'))
             ->_title(Mage::helper('grp_inquiry')->__('Inquiries'));
        $this->renderLayout();
    }
    /**
     * grid action
     * @access public
     * @return void
     * @author Grapes-solutions.com
     */
    public function gridAction() {
        $this->loadLayout()->renderLayout();
    }
    /**
     * edit inquiry - action
     * @access public
     * @return void
     * @author Grapes-solutions.com
     */
    public function editAction() {
        $inquiryId    = $this->getRequest()->getParam('id');
        $inquiry      = $this->_initInquiry();
        if ($inquiryId && !$inquiry->getId()) {
            $this->_getSession()->addError(Mage::helper('grp_inquiry')->__('This inquiry no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getInquiryData(true);
        if (!empty($data)) {
            $inquiry->setData($data);
        }
        Mage::register('inquiry_data', $inquiry);
        $this->loadLayout();
        $this->_title(Mage::helper('grp_inquiry')->__('Inquiry'))
             ->_title(Mage::helper('grp_inquiry')->__('Inquiries'));
        if ($inquiry->getId()){
            $this->_title($inquiry->getInquiries());
        }
        else{
            $this->_title(Mage::helper('grp_inquiry')->__('Add inquiry'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }
    /**
     * new inquiry action
     * @access public
     * @return void
     * @author Grapes-solutions.com
     */
    public function newAction() {
        $this->_forward('edit');
    }
    /**
     * save inquiry - action
     * @access public
     * @return void
     * @author Grapes-solutions.com
     */
    public function saveAction() {
        if ($data = $this->getRequest()->getPost('inquiry')) {
            try {
                $inquiry = $this->_initInquiry();
                $inquiry->addData($data);
                $inquiry->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('grp_inquiry')->__('Inquiry was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $inquiry->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setInquiryData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
            catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('grp_inquiry')->__('There was a problem saving the inquiry.'));
                Mage::getSingleton('adminhtml/session')->setInquiryData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('grp_inquiry')->__('Unable to find inquiry to save.'));
        $this->_redirect('*/*/');
    }
    /**
     * delete inquiry - action
     * @access public
     * @return void
     * @author Grapes-solutions.com
     */
    public function deleteAction() {
        if( $this->getRequest()->getParam('id') > 0) {
            try {
                $inquiry = Mage::getModel('grp_inquiry/inquiry');
                $inquiry->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('grp_inquiry')->__('Inquiry was successfully deleted.'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('grp_inquiry')->__('There was an error deleting inquiry.'));
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('grp_inquiry')->__('Could not find inquiry to delete.'));
        $this->_redirect('*/*/');
    }
    /**
     * mass delete inquiry - action
     * @access public
     * @return void
     * @author Grapes-solutions.com
     */
    public function massDeleteAction() {
        $inquiryIds = $this->getRequest()->getParam('inquiry');
        if(!is_array($inquiryIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('grp_inquiry')->__('Please select inquiries to delete.'));
        }
        else {
            try {
                foreach ($inquiryIds as $inquiryId) {
                    $inquiry = Mage::getModel('grp_inquiry/inquiry');
                    $inquiry->setId($inquiryId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('grp_inquiry')->__('Total of %d inquiries were successfully deleted.', count($inquiryIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('grp_inquiry')->__('There was an error deleting inquiries.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * mass status change - action
     * @access public
     * @return void
     * @author Grapes-solutions.com
     */
    public function massStatusAction(){
        $inquiryIds = $this->getRequest()->getParam('inquiry');
        if(!is_array($inquiryIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('grp_inquiry')->__('Please select inquiries.'));
        }
        else {
            try {
                foreach ($inquiryIds as $inquiryId) {
                $inquiry = Mage::getSingleton('grp_inquiry/inquiry')->load($inquiryId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d inquiries were successfully updated.', count($inquiryIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('grp_inquiry')->__('There was an error updating inquiries.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * mass Send inquiry? change - action
     * @access public
     * @return void
     * @author Grapes-solutions.com
     */
    public function massInquiryAction(){
        $inquiryIds = $this->getRequest()->getParam('inquiry');
        if(!is_array($inquiryIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('grp_inquiry')->__('Please select inquiries.'));
        }
        else {
            try {
                foreach ($inquiryIds as $inquiryId) {
                $inquiry = Mage::getSingleton('grp_inquiry/inquiry')->load($inquiryId)
                            ->setInquiry($this->getRequest()->getParam('flag_inquiry'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d inquiries were successfully updated.', count($inquiryIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('grp_inquiry')->__('There was an error updating inquiries.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * export as csv - action
     * @access public
     * @return void
     * @author Grapes-solutions.com
     */
    public function exportCsvAction(){
        $fileName   = 'inquiry.csv';
        $content    = $this->getLayout()->createBlock('grp_inquiry/adminhtml_inquiry_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as MsExcel - action
     * @access public
     * @return void
     * @author Grapes-solutions.com
     */
    public function exportExcelAction(){
        $fileName   = 'inquiry.xls';
        $content    = $this->getLayout()->createBlock('grp_inquiry/adminhtml_inquiry_grid')->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as xml - action
     * @access public
     * @return void
     * @author Grapes-solutions.com
     */
    public function exportXmlAction(){
        $fileName   = 'inquiry.xml';
        $content    = $this->getLayout()->createBlock('grp_inquiry/adminhtml_inquiry_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * Check if admin has permissions to visit related pages
     * @access protected
     * @return boolean
     * @author Grapes-solutions.com
     */
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('sales/grp_inquiry/inquiry');
    }
}
