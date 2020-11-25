<?php
/**
 * Magpleasure Ltd.
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
 * Magpleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   Magpleasure
 * @package    Magpleasure_Searchcore
 * @version    1.0.6
 * @copyright  Copyright (c) 2013 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */

class Magpleasure_Searchcore_Adminhtml_SearchcoreController extends Magpleasure_Common_Controller_Adminhtml_Action
{
    /**
     * Helper
     *
     * @return Magpleasure_Searchcore_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('searchcore');
    }

	protected function _initAction()
    {
		$this->loadLayout()
			->_setActiveMenu('searchcore/items')
			->_addBreadcrumb($this->_helper()->__('Items Manager'), $this->_helper()->__('Item Manager'));

		return $this;
	}

	public function indexAction()
    {
		$this
            ->_initAction()
			->renderLayout()
            ;
	}

	public function editAction()
    {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('searchcore/searchcore')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('searchcore_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('searchcore/items');

			$this->_addBreadcrumb($this->_helper()->__('Item Manager'), $this->_helper()->__('Item Manager'));
			$this->_addBreadcrumb($this->_helper()->__('Item News'), $this->_helper()->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('searchcore/adminhtml_searchcore_edit'))
				->_addLeft($this->getLayout()->createBlock('searchcore/adminhtml_searchcore_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError($this->_helper()->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}

	public function newAction()
    {
		$this->_forward('edit');
	}

	public function saveAction()
    {
		if ($data = $this->getRequest()->getPost()) {

			if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
				try {
					/* Starting upload */
					$uploader = new Varien_File_Uploader('filename');

					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);

					// Set the file upload mode
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);

					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS ;
					$uploader->save($path, $_FILES['filename']['name'] );

				} catch (Exception $e) {

		        }

		        //this way the name is saved in DB
	  			$data['filename'] = $_FILES['filename']['name'];
			}


			$model = Mage::getModel('searchcore/searchcore');
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));

			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}

				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess($this->_helper()->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError($this->_helper()->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}

	public function deleteAction()
    {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('searchcore/searchcore');

				$model->setId($this->getRequest()->getParam('id'))
					->delete();

				Mage::getSingleton('adminhtml/session')->addSuccess($this->_helper()->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction()
    {
        $searchcoreIds = $this->getRequest()->getParam('searchcore');
        if(!is_array($searchcoreIds)) {
			Mage::getSingleton('adminhtml/session')->addError($this->_helper()->__('Please select item(s)'));
        } else {
            try {
                foreach ($searchcoreIds as $searchcoreId) {
                    $searchcore = Mage::getModel('searchcore/searchcore')->load($searchcoreId);
                    $searchcore->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    $this->_helper()->__(
                        'Total of %d record(s) were successfully deleted', count($searchcoreIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $searchcoreIds = $this->getRequest()->getParam('searchcore');
        if(!is_array($searchcoreIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->_helper()->__('Please select item(s)'));
        } else {
            try {
                foreach ($searchcoreIds as $searchcoreId) {
                    $searchcore = Mage::getSingleton('searchcore/searchcore')
                        ->load($searchcoreId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->_helper()->__('Total of %d record(s) were successfully updated', count($searchcoreIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction()
    {
        $fileName   = 'searchcore.csv';
        $content    = $this->getLayout()->createBlock('searchcore/adminhtml_searchcore_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'searchcore.xml';
        $content    = $this->getLayout()->createBlock('searchcore/adminhtml_searchcore_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }
}