<?php
/**
 * MagPleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE-CE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE-CE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * MagPleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   MagPleasure
 * @package    Magpleasure_Blog
 * @version    1.2.3
 * @copyright  Copyright (c) 2012-2013 MagPleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */


class Magpleasure_Blog_Block_Adminhtml_Comment_Grid extends Magpleasure_Blog_Block_Adminhtml_Filterable_Grid
{
    /**
     * Helper
     * @return Magpleasure_Blog_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('mpblog');
    }

    public function __construct()
    {
        parent::__construct();
        $this->setId('blogCommentGrid');
        $this->setDefaultSort('comment_id');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        /** @var Magpleasure_Blog_Model_Mysql4_Comment_Collection $collection  */
        $collection = Mage::getModel('mpblog/comment')->getCollection();
        $collection->addReplyTo();

        if (!Mage::app()->isSingleStoreMode()){
            if ($this->isStoreFilterApplied()){
                $storeIds = array($this->getAppliedStoreId());
            } else {
                $storeIds = $this->_helper()->getCommon()->getStore()->getFrontendStoreIds();
            }

            $collection->addStoreFilter($storeIds);
        }

        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection()) {
            $field = ( $column->getFilterIndex() ) ? $column->getFilterIndex() : $column->getIndex();
            if ($column->getFilterConditionCallback()) {
                call_user_func($column->getFilterConditionCallback(), $this->getCollection(), $column);
            } else {
                $cond = $column->getFilter()->getCondition();
                if ($field && isset($cond)) {
                    $this->getCollection()->addFieldToFilter("main_table.".$field , $cond);
                }
            }
        }
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('comment_id', array(
            'header' => $this->_helper()->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'comment_id',
        ));

        $this->addColumn('post_id', array(
            'header' => $this->_helper()->__('Post'),
            'align' => 'left',
            'index' => 'post_id',
            'width' => '200px',
            'renderer' => 'Magpleasure_Blog_Block_Adminhtml_Widget_Grid_Column_Renderer_Post',
            'filter_condition_callback' => array($this, '_filterPostCondition'),
        ));

        $this->addColumn('reply_to_text', array(
            'header' => $this->_helper()->__('Reply To'),
            'align' => 'left',
            'width' => '200px',
            'index' => 'reply_to_text',
            'renderer' => 'Magpleasure_Blog_Block_Adminhtml_Widget_Grid_Column_Renderer_Comment',
            'filter_condition_callback' => array($this, '_filterReplyToCondition'),
        ));

        $this->addColumn('message', array(
            'header' => $this->_helper()->__('Comment'),
            'align' => 'left',
            'width' => '200px',
            'renderer' => 'Magpleasure_Blog_Block_Adminhtml_Widget_Grid_Column_Renderer_Comment',
            'index' => 'message',
            'filter_condition_callback' => array($this, '_filterMessageCondition'),
        ));

        $this->addColumn('name', array(
            'header' => $this->_helper()->__('Name'),
            'align' => 'left',
            'index' => 'name',
        ));

        $this->addColumn('email', array(
            'header' => $this->_helper()->__('Email'),
            'align' => 'left',
            'index' => 'email',
        ));

        $this->addColumn('customer_id', array(
            'header' => $this->_helper()->__('Customer'),
            'align' => 'left',
            'index' => 'customer_id',
            'filter' => false,
            'renderer' => 'Magpleasure_Blog_Block_Adminhtml_Widget_Grid_Column_Renderer_Customer',
        ));

        $this->addColumn('status', array(
            'header' => $this->_helper()->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => Mage::getModel('mpblog/comment')->getOptionsArray(),
            'filter_condition_callback' => array($this, '_filterStatus'),
        ));

        if(!Mage::app()->isSingleStoreMode() && !$this->isStoreFilterApplied()){
            $this->addColumn('store_id', array(
                'header' => $this->__('Store View'),
                'index' => 'store_id',
                'sortable' => true,
                'width' => '120px',
                'type' => 'store',
                'store_view' => true,
                'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Store',
            ));
        }

        $this->addColumn('created_at', array(
            'header' => $this->_helper()->__('Created At'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '140px',
        ));

        $this->addColumn('updated_at', array(
            'header' => $this->_helper()->__('Updated At'),
            'index' => 'updated_at',
            'type' => 'datetime',
            'width' => '140px',
        ));

        $this->addColumn('action',
            array(
                'header' => $this->_helper()->__('Action'),
                'width' => '100',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => $this->_helper()->__('Answer'),
                        'url' => array('base' => '*/*/answer', 'params' => $this->_getCommonParams()),
                        'field' => 'id_to_answer'
                    ),
                    array(
                        'caption' => $this->_helper()->__('Approve'),
                        'url' => array('base' => '*/*/approve', 'params' => $this->_getCommonParams()),
                        'field' => 'id'
                    ),
                    array(
                        'caption' => $this->_helper()->__('Edit'),
                        'url' => array('base' => '*/*/edit', 'params' => $this->_getCommonParams()),
                        'field' => 'id'
                    ),
                    array(
                        'caption' => $this->_helper()->__('Reject'),
                        'url' => array('base' => '*/*/reject', 'params' => $this->_getCommonParams()),
                        'field' => 'id'
                    ),
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'is_system' => true,
            ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('comment_id');
        $this->getMassactionBlock()->setFormFieldName('comments');

        $statuses = Mage::getModel('mpblog/comment')->toOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => $this->_helper()->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => $this->_helper()->__('Status'),
                    'values' => $statuses
                )
            )
        ));

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => $this->_helper()->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => $this->_helper()->__('Are you sure?')
        ));
        return $this;
    }

    protected function _filterPostCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addPostTextFilter($value);
    }

    protected function _filterReplyToCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addReplyToTextFilter($value);
    }

    protected function _filterMessageCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addMessageTextFilter($value);
    }

    protected function _filterStatus($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addStatusFilter($value);
    }

    public function getAdditionalJavaScript()
    {
        return "
            var moreComments = function(id){
                if (!$(id).hasClassName('expanded')){
                    $(id).addClassName('expanded');
                    Effect.Appear(id, {duration: 0.5, afterFinish: (function(){
                            $('a-'+id+'-hide').style.display = 'block';
                            $('a-'+id+'-show').style.display = 'none';
                    }).bind(id)})
                } else {
                    $(id).removeClassName('expanded');
                    Effect.Fade(id, {duration: 0.3, afterFinish: (function(){
                            $('a-'+id+'-show').style.display = 'block';
                            $('a-'+id+'-hide').style.display = 'none';
                    }).bind(id)});
                }
            };
        ";
    }
}