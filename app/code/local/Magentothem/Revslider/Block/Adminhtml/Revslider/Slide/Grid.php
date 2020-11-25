<?php

class Magentothem_Revslider_Block_Adminhtml_Revslider_Slide_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('slideGrid');
      $this->setDefaultSort('slide_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('revslider/slide')->getCollection();
	  $revslider_id = $this->getRequest()->getParam('revslider_id');
	  if($revslider_id) {
		$collection->addAttributeToFilter('revslider_id',$revslider_id);
	  }
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
   $this->addColumn('revslider_id', array(
          'header'    => Mage::helper('revslider')->__('Revslider Id'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'revslider_id',
      ));

      $this->addColumn('slide_id', array(
          'header'    => Mage::helper('revslider')->__('Slide Id'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'slide_id',
      ));

      $this->addColumn('title', array(
          'header'    => Mage::helper('revslider')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));


      $this->addColumn('status', array(
          'header'    => Mage::helper('revslider')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	    $revslider_id = $this->getRequest()->getParam('revslider_id');
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('revslider')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('revslider')->__('Edit'),
                        'url'       => array('base'=> '*/*/editbanner/revslider_id/'.$revslider_id),
                        'field'     => 'slide_id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('revslider')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('revslider')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('revslider_id');
        $this->getMassactionBlock()->setFormFieldName('revslider');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('revslider')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDeleteSlide'),
             'confirm'  => Mage::helper('revslider')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('revslider/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('revslider')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('revslider')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {		  $revslider_id = $this->getRequest()->getParam('revslider_id');
      return $this->getUrl('*/*/editbanner', array('slide_id' => $row->getId(),'revslider_id'=>$revslider_id));
  }

}