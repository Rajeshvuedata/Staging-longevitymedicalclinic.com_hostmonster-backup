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


class Magpleasure_Blog_Model_System_Config_Source_Categories
{	
    public function toOptionArray()
    {
        $result = array(
            array('value'=>'-', 'label'=>Mage::helper('mpblog')->__('All Categories')),
        );


        /** @var Magpleasure_Blog_Model_Mysql4_Category_Collection $categories  */
        $categories = Mage::getModel('mpblog/category')->getCollection();
        $categories
            ->setSortOrder('asc')
            ->addFieldToFilter('status', Magpleasure_Blog_Model_Category::STATUS_ENABLED)
            ;

        foreach ($categories as $category){
            $result[] = array('value'=>$category->getId(), 'label'=>$category->getName());
        }

        return $result;
    }	
	
}

