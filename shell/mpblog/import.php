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
require_once '../abstract.php';

/**
 * Magento Compiler Shell Script
 *
 * @category    Mage
 * @package     Mage_Shell
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Magpleasure_Shell_Mpblog_Import extends Mage_Shell_Abstract
{

    /**
     * Importer
     *
     * @return Magpleasure_Blog_Helper_Import
     */
    public function _importer()
    {
        return Mage::helper('mpblog/import');
    }

    /**
     * Run script
     *
     */
    public function run()
    {
        if ($this->getArg('awblog')) {
            echo "\n************************** \n";
            echo "* Importing AW_Blog data *\n";
            echo "************************** \n";
            try {
                $this->_importer()->importAwblog(true);
                echo "\nReady.\n\n";
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage() . "\n";
            }

        } elseif ($this->getArg('wp')) {

            echo "\n**************************** \n";
            echo "* Importing WordPress data *\n";
            echo "**************************** \n";

            $host = $this->getArg('host');
            $user = $this->getArg('user');
            $pass = $this->getArg('pass');

            if (!Mage::app()->isSingleStoreMode()){

                $stores = $this->getArg('stores');

                $storeIds = array();
                $storeCodes = explode(',', $stores);
                foreach ($storeCodes as $storeName){
                    $store = Mage::getModel('core/store')->load($storeName, 'name');

                    if ($store && $store->getId()){
                        $storeIds[] = $store->getId();
                    }
                }

            } else {
                $storeIds = array(Mage::app()->getDefaultStoreView()->getId());
            }

            if ($host && $user && $pass && $storeIds && is_array($storeIds)){

                $data = array(
                    'host' => $host,
                    'username' => $user,
                    'password' => $pass,
                    'stores' => $storeIds,
                );

                try {
                    $this->_importer()->importWordpress(true, $data);
                    echo "\nReady.\n\n";
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage() . "\n";
                }
            }

        } else {
            echo $this->usageHelp();
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     * @return string
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f import.php [options]
  awblog                        Import data from AW_Blog
  wp                            Import data from Wordpress Blog (-host <http://www.wordpress-host.com/> -user <USERNAME> -pass <PASSWORD> -stores <STORE_CODE_1,STORE_CODE_2>)
  help                          This help

USAGE;
    }
}

$shell = new Magpleasure_Shell_Mpblog_Import();
$shell->run();
