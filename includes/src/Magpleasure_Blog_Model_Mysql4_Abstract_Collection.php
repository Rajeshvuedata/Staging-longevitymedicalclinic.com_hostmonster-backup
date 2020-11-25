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

class Magpleasure_Blog_Model_Mysql4_Abstract_Collection extends Magpleasure_Common_Model_Resource_Collection_Abstract
{
     protected $_mainTable;

    /**
     * Retrieve main table
     *
     * @return string
     */
    public function getMainTable()
    {
        if ($this->_mainTable === null) {
            $this->setMainTable($this->getResource()->getMainTable());
        }

        return $this->_mainTable;
    }

    /**
     * Set main collection table
     *
     * @param string $table
     * @return Mage_Core_Model_Resource_Db_Collection_Abstract
     */
    public function setMainTable($table)
    {
        if (strpos($table, '/') !== false) {
            $table = $this->getTable($table);
        }

        if ($this->_mainTable !== null && $table !== $this->_mainTable && $this->getSelect() !== null) {
            $from = $this->getSelect()->getPart(Zend_Db_Select::FROM);
            if (isset($from['main_table'])) {
                $from['main_table']['tableName'] = $table;
            }
            $this->getSelect()->setPart(Zend_Db_Select::FROM, $from);
        }

        $this->_mainTable = $table;
        return $this;
    }


}