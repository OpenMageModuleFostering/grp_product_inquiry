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
 * Inquiry front contrller
 *
 * @category    Grp
 * @package     Grp_Inquiry
 * @author      Grapes-solutions.com
 */
class Grp_Inquiry_InquiryController extends Mage_Core_Controller_Front_Action {

    /**
     * init Inquiry
     * @access protected
     * @return Grp_Inquiry_Model_Entity
     * @author Grapes-solutions.com
     */
    const XML_PATH_EMAIL_RECIPIENT = 'grp_inquiry/email/recipient_email';
    const XML_PATH_EMAIL_SENDER = 'grp_inquiry/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE = 'grp_inquiry/email/email_template';
    const XML_PATH_EMAIL_CUSTOMER_TEMPLATE = 'grp_inquiry/email/email_customer_template';

    protected function _initInquiry() {
        $inquiryId = $this->getRequest()->getParam('id', 0);
        $inquiry = Mage::getModel('grp_inquiry/inquiry')
                //->setStoreId(Mage::app()->getStore()->getId())
                ->load($inquiryId);
        if (!$inquiry->getId()) {
            return false;
        } elseif (!$inquiry->getStatus()) {
            return false;
        }
        return $inquiry;
    }

    /**
     * view inquiry action
     * @access public
     * @return void
     * @author Grapes-solutions.com
     */
    public function viewAction() {
        $inquiry = $this->_initInquiry();
        if (!$inquiry) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_inquiry', $inquiry);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('inquiry-inquiry inquiry-inquiry' . $inquiry->getId());
        }
        if (Mage::helper('grp_inquiry/inquiry')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb('home', array(
                    'label' => Mage::helper('grp_inquiry')->__('Home'),
                    'link' => Mage::getUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb('inquiry', array(
                    'label' => $inquiry->getInquiries(),
                    'link' => '',
                        )
                );
            }
        }
        $this->renderLayout();
    }

    /**
     * Submit new comment action
     *
     */
    public function commentpostAction() {
        $data = $this->getRequest()->getPost();
        $inquiry = $this->_initInquiry();
        $session = Mage::getSingleton('core/session');
        if ($inquiry) {
            if ($inquiry->getAllowComments()) {
                if ((Mage::getSingleton('customer/session')->isLoggedIn() || Mage::getStoreConfigFlag('grp_inquiry/inquiry/allow_guest_comment'))) {
                    $comment = Mage::getModel('grp_inquiry/inquiry_comment')->setData($data);
                    $validate = $comment->validate();
                    if ($validate === true) {
                        try {
                            $comment->setInquiryId($inquiry->getId())
                                    ->setStatus(Grp_Inquiry_Model_Inquiry_Comment::STATUS_PENDING)
                                    ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                                    ->setStores(array(Mage::app()->getStore()->getId()))
                                    ->save();
                            $session->addSuccess($this->__('Your comment has been accepted for moderation.'));
                        } catch (Exception $e) {
                            $session->setInquiryCommentData($data);
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    } else {
                        $session->setInquiryCommentData($data);
                        if (is_array($validate)) {
                            foreach ($validate as $errorMessage) {
                                $session->addError($errorMessage);
                            }
                        } else {
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    }
                } else {
                    $session->addError($this->__('Guest comments are not allowed'));
                }
            } else {
                $session->addError($this->__('This inquiry does not allow comments'));
            }
        }
        $this->_redirectReferer();
    }

    public function productAction() {
        $productId = $this->getRequest()->getParam('product', 0);
        Mage::register('product_id', $productId);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('inquiry-inquiry inquiry-inquiry');
        }
        if (Mage::helper('grp_inquiry/inquiry')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb('home', array(
                    'label' => Mage::helper('grp_inquiry')->__('Home'),
                    'link' => Mage::getUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb('inquiry', array(
                    'label' => 'Product Inquiry',
                    'link' => '',
                        )
                );
            }
        }
        $this->renderLayout();
    }

    public function postinquiryAction() {
        $data = $this->getRequest()->getPost();

        $session = Mage::getSingleton('core/session');
        try {
            $inquiry = Mage::getModel('grp_inquiry/inquiry');

            $inquiry->setInquiries($data['content']);
            $inquiry->setFirstName($data['firstname']);
            $inquiry->setLastName($data['lastname']);
            $inquiry->setPhone($data['phone']);
            $inquiry->setEmail($data['email']);
            $inquiry->setProductId($data['product_id']);
            $product=Mage::getModel('catalog/product')->load($data['product_id']);
             $inquiry->setSku($product->getSku());
            $inquiry->setCreatedAt(now());

            //echo '<pre>';print_r($inquiry->getData());die;
            $inquiry->save();
            
            $this->sendEmail2($data);
            $session->addSuccess($this->__('Your inquiry has been sent.'));
        } catch (Exception $e) {
            $session->addError($this->__('Unable to sent inquiry.'));
        }

        $this->_redirectReferer();
    }

        public function  sendEmail2($data)
        {   
            try {
            $product = Mage::getModel('catalog/product')->load($data['product_id']);
            $sendto=$data['email'];$name=$data['firstname'] . ' ' . $data['lastname'];
                $params = array('name' => $data['firstname'] . ' ' . $data['lastname'],
                            'email' => $data['email'],
                            'telephone' => $data['phone'],
                            'product_id' => $data['product_id'],
                            'product_sku' => $product->getSku(),
                            'product_url' => $product->getProductUrl(),
                            'product_name' => $product->getName(),
                            'comment' => $data['content']);
                        $postObject = new Varien_Object();
                        $postObject->setData($params);
            $mailer = Mage::getModel('core/email_template_mailer');
            $emailInfo = Mage::getModel('core/email_info');
            $emailInfo->addTo((string)Mage::getStoreConfig('grp_inquiry/inquiry/inquiry_email'),"Administrator");
            $mailer->addEmailInfo($emailInfo);
            $storeId=Mage::app()->getStore()->getId();
           
         // Set all required params and send emails
            $mailer->setSender(Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER));
            $mailer->setStoreId($storeId);
            $mailer->setTemplateId(Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE));
            $mailer->setTemplateParams(array('data' => $postObject));
            $mailer->send();
            
           
            
            $emailInfo = Mage::getModel('core/email_info');
            $emailInfo->addTo($sendto,$name);
            $mailer->addEmailInfo($emailInfo);
            $storeId=Mage::app()->getStore()->getId();
             $mailer->setSubject('Product Inquiry');
         // Set all required params and send emails
            $mailer->setSender(Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER));
            $mailer->setStoreId($storeId);
            $mailer->setTemplateId(Mage::getStoreConfig(self::XML_PATH_EMAIL_CUSTOMER_TEMPLATE));
            $mailer->setTemplateParams(array('data' => $postObject));
            $mailer->send();
            
          
            } catch (Exception $e) {
            $session->addError($this->__('Unable to sent inquiry.'));
            }
        
        }
        
   

}
