<?php
class Magentothem_Revslider_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/revslider?id=15 
    	 *  or
    	 * http://site.com/revslider/id/15 	
    	 */
    	/* 
		$revslider_id = $this->getRequest()->getParam('id');

  		if($revslider_id != null && $revslider_id != '')	{
			$revslider = Mage::getModel('revslider/revslider')->load($revslider_id)->getData();
		} else {
			$revslider = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($revslider == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$revsliderTable = $resource->getTableName('revslider');
			
			$select = $read->select()
			   ->from($revsliderTable,array('revslider_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$revslider = $read->fetchRow($select);
		}
		Mage::register('revslider', $revslider);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}