<?php

class Tech9logy_Events_Adminhtml_EventController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("events/event")->_addBreadcrumb(Mage::helper("adminhtml")->__("Event  Manager"),Mage::helper("adminhtml")->__("Event Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("Events List"));
			    $this->_title($this->__("Manager Events"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("Edit Single Event"));
				$this->_title($this->__("Events List"));
			    $this->_title($this->__("Edit Events"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("events/event")->load($id);
				if ($model->getId()) {
					Mage::register("event_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("events/event");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Event Manager"), Mage::helper("adminhtml")->__("Event Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Event Description"), Mage::helper("adminhtml")->__("Event Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("events/adminhtml_event_edit"))->_addLeft($this->getLayout()->createBlock("events/adminhtml_event_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("events")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("Events"));
		$this->_title($this->__("Event"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("events/event")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("event_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("events/event");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Event Manager"), Mage::helper("adminhtml")->__("Event Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Event Description"), Mage::helper("adminhtml")->__("Event Description"));


		$this->_addContent($this->getLayout()->createBlock("events/adminhtml_event_edit"))->_addLeft($this->getLayout()->createBlock("events/adminhtml_event_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						
						if(isset($post_data['iswebinar']) && $post_data['iswebinar'] == 1){
							$post_data['iswebinar'] = 1;
						}else{
							$post_data['iswebinar'] = 0;
						}
						$model = Mage::getModel("events/event")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Event was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setEventData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setEventData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("events/event");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Event was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("events/event");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Event(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'event.csv';
			$grid       = $this->getLayout()->createBlock('events/adminhtml_event_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'event.xml';
			$grid       = $this->getLayout()->createBlock('events/adminhtml_event_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
