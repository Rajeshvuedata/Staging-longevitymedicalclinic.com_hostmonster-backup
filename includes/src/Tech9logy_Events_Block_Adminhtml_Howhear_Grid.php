<?php

class Tech9logy_Events_Block_Adminhtml_Howhear_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("howhearGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("events/howhear")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("id", array(
				"header" => Mage::helper("events")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "id",
				));
                
				$this->addColumn("from_where_hear", array(
				"header" => Mage::helper("events")->__("Hear From"),
				"index" => "from_where_hear",
				));
						$this->addColumn('status', array(
						'header' => Mage::helper('events')->__('status'),
						'index' => 'status',
						'type' => 'options',
						'options'=>Tech9logy_Events_Block_Adminhtml_Howhear_Grid::getOptionArray1(),				
						));
						
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_howhear', array(
					 'label'=> Mage::helper('events')->__('Remove Howhear'),
					 'url'  => $this->getUrl('*/adminhtml_howhear/massRemove'),
					 'confirm' => Mage::helper('events')->__('Are you sure?')
				));
			return $this;
		}
			
		static public function getOptionArray1()
		{
            $data_array=array(); 
			$data_array[0]='Enabled';
			$data_array[1]='Disabled';
            return($data_array);
		}
		static public function getValueArray1()
		{
            $data_array=array();
			foreach(Tech9logy_Events_Block_Adminhtml_Howhear_Grid::getOptionArray1() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		

}