<?php


class Tech9logy_Events_Block_Adminhtml_Howhear extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_howhear";
	$this->_blockGroup = "events";
	$this->_headerText = Mage::helper("events")->__("Howhear Manager");
	$this->_addButtonLabel = Mage::helper("events")->__("Add New Item");
	parent::__construct();
	
	}

}