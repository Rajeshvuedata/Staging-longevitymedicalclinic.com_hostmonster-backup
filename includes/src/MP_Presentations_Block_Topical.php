<?php

/**
 * 
 * @author anonymous
 *
 */
class MP_Presentations_Block_Topical extends Mage_Adminhtml_Block_Template implements Mage_Widget_Block_Interface
{
    /**
     * 
     * @var string
     */
    const CHART_TEMPLATE = 'mp_presentations/topical.phtml';
    
    protected $_size = 5;
    
    protected $_total = null;
    
    protected $_paginated = null;

    /**
     * 
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate(self::CHART_TEMPLATE);
    }
    
    /**
     * 
     */
    public function getLastPage()
    {
       return $this->_total; 
    }
    
    /**
     * 
     * @param unknown $page
     */
    public function getPaginationUrl( $page )
    {
        return Mage::getUrl('/').'/mp_presentations/index/index/pagination/'.$page.'/';//."?pagination=$page";
    }
    
    /**
     * 
     * @return mixed|unknown
     */
    public function getCurrentPage()
    {
        if (is_null($this->_paginated))
        {
            $this->_paginated = Mage::app()->getRequest()->getParam('pagination');
            if (!$this->_paginated || is_null($this->_paginated))
            {
                $this->_paginated = 1;
            }
        }
        return $this->_paginated;
    }
    
    /**
     * 
     * @return object|boolean|Mage_Core_Model_Abstract|false
     */
    public function getPresentations()
    {
        $collection = Mage::getModel('mp_presentations/topical')->getCollection();
        $collection->getSelect()->order(array('position ASC'));
        
        $collection->setPageSize($this->_size);
        $collection->setCurPage($this->getCurrentPage());
       
        $this->_total = ceil($collection->getSize()/$this->_size);
       
        return $collection;
    }
}
