<?php

class MP_Presentations_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * 
     */
    public function indexAction()
    {
        $response = array();
        
        $block = $this->_helper()->createBlock('mp_presentations/topical');
        $block->setTemplate(MP_Presentations_Block_Topical::CHART_TEMPLATE);
        $response['presentation'] = $block->toHtml();
        
        $this->getResponse()->clearHeaders()->setHeader('Content-type', 'application/json', true);
        $this->_ajaxResponse($response);
    }

    /**
     * Helper
     *
     * @return Magpleasure_Blog_Helper_Data
     */
    public function _helper()
    {
        return Mage::helper('mp_presentations');
    }
    
    /**
     * Response for Ajax Request
     *
     * @param array $result
     */
    protected function _ajaxResponse($result = array())
    {
        $this->getResponse()->setBody(Zend_Json::encode($result));
    }
    
    /**
     * Customer Session
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }
}