<?php

class Magentothem_Revslider_Adminhtml_RevsliderController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('revslider/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('revslider/revslider')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}
			$session = Mage::getSingleton('adminhtml/session');
			//Mage::register('revslider_data', $model);
			$jsonParams = $model->getParams();
			if ($jsonParams) {
				$jsondecodeParams = json_decode($jsonParams, true);
			}
			
			 $sessionData = $session->getRevsliderData(true);
			if (is_array($sessionData))
				$data = array_merge($data, $sessionData);
			$session->setRevsliderContentData(false);
			if ($id = $this->getRequest()->getParam('id'))
             $data['revslider_id'] = $id;
			Mage::register('revslider_data', $model->setData($jsondecodeParams));
			$this->loadLayout();
			$this->_setActiveMenu('revslider/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('revslider/adminhtml_revslider_edit'))
				->_addLeft($this->getLayout()->createBlock('revslider/adminhtml_revslider_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('revslider')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
	

 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
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
	  			
	  		$data['params'] = json_encode($data);	
			$model = Mage::getModel('revslider/revslider');		
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
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('revslider')->__('Item was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('revslider')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
	
	
	  public function editbannerAction() {
		$session = Mage::getSingleton('adminhtml/session');
		//$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('revslider/slide');
		 if ($id = $this->getRequest()->getParam('slide_id')) {
            $model->load($id);
            if (!$model->getId()) {
                $session->addError($this->__('This content block no longer exists'));
                $this->_redirectReferer();
                return;
            }
        } else { $session->setSlideId(); }
        $data = $model->getData();
	
        $jsonParams = $model->getParams();
        if ($jsonParams) {
            $jsondecodeParams = json_decode($jsonParams, true);
        }
		
        $sessionData = $session->getSlideData(true);
        if (is_array($sessionData))
            $data = array_merge($data, $sessionData);
         $session->setSlideData(false);

        if ($id = $this->getRequest()->getParam('id'))
            $data['revslider_id'] = $id;
        Mage::register('slide_data', $model->setData($jsondecodeParams));
		$this->_initAction()
				->_addContent($this->getLayout()->createBlock('revslider/adminhtml_revslider_slide_edit'))
				->_addLeft($this->getLayout()->createBlock('revslider/adminhtml_revslider_slide_edit_tabs'))
				->renderLayout();
		
	}
	
	public function saveSlideAction() {
		$session = Mage::getSingleton('adminhtml/session');
        if ($data = $this->getRequest()->getParams()) {
			$saveAndClose = $data['save_close']; 
			$model = Mage::getModel('revslider/slide');
			
			$slideId = $this->getRequest()->getParam('slide_id');
			if(!$slideId) {
			  $slideId = $session->getSlideId();
			}
            $model = Mage::getModel('revslider/slide');

            if ($slideId) {
                $model->load($slideId);
            } 
			
			$params = $data['params'];
			$helper = Mage::helper('revslider/data');
			
			$params = $helper->RevJsonDecode($params); 
			$data['title'] = $params['title'];
			$data['status'] = $params['status'];
			$image_url = $params['image_url'];
			$mediaUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
			$image_url = str_replace($mediaUrl,'',$image_url);
			$params['image_url'] = $image_url;
			$params = json_encode($params,true);
			
			$layers = $data['layers'];
			$layers = $helper->RevJsonDecode($layers); 
			$layerArray = array();
			foreach($layers as $key=>$layer) {
				$image_layer = $layer['image_url'];
				if($image_layer) {
					$image_layer = str_replace($mediaUrl,'',$image_layer);
					$layer['image_url'] = $image_layer;
				}
				$layerArray[$key] = $layer;
				
			}

			$layerJson = json_encode($layerArray,true); 
			$data['params'] = $params; 
			$data['layers'] = $layerJson; 
			$model		
				->setData($data);
				if($slideId) {
					$model->setSlideId($slideId);
				}else {
					$model->setSlideId(NULL);
				}
				$model->save();
		    if ($model->save()){
			
				$session->setSlideId($model->getSlideId());
				if($saveAndClose==1) {
					$url = $this->getUrl('*/*/slide/', array('revslider_id'=>$model->getRevsliderId()));
					$this->getResponse()->setBody($url);
				} else {
					$this->getResponse()->setBody(1);
				}
			} else {
				$this->getResponse()->setBody(0);
			}
		}
	
	}
	
	/** save Caption*/
	 public function saveCaptionAction() {
		if ($data = $this->getRequest()->getParams()) {
				$delete_action = $data['delete_action']; 
				if($delete_action !=1) {
					$captions = json_decode($data['mydata'], true); 
					$hoverArray = $captions['hover'];
					$paramArray = $captions['params'];
					$settingArray = $captions['settings'];
					$helper = Mage::helper('revslider/data');
					$handle = '.tp-caption.'.$captions['handle'];
					$captionArray['hover'] =  stripslashes(json_encode($helper->ClearQuote($hoverArray)));
					$captionArray['params'] =  stripslashes(json_encode($helper->ClearQuote($paramArray)));
					$captionArray['settings'] =  stripslashes(json_encode($helper->ClearQuote($settingArray)));
					$captionArray['handle'] = $handle;
					$collection = Mage::getModel('revslider/caption')->getCollection();
					$caption_id = null;
					foreach($collection as $cap) {
						if($cap['handle'] == $handle) {
							$caption_id = $cap['caption_id'];
							break;
						}
					}

					$model = Mage::getModel('revslider/caption');
					if($caption_id) {
						$model->load($caption_id);
					}
					$model->setData($captionArray)
						  ->setCaptionId($caption_id)
						  ->save();
			} else {
					$handle = '.tp-caption.'.$data['mydata'];
				 	
					$collection = Mage::getModel('revslider/caption')->getCollection();
					$caption_id = null;
					foreach($collection as $cap) {
						if($cap['handle'] == $handle) {
							$caption_id = $cap['caption_id'];
							break;
						}
					}
					$model = Mage::getModel('revslider/caption');
					if($caption_id) {
						$model->load($caption_id)->delete();
					}
				
			}
			//Mage::helper('revslider/data')->updateToCssFile(); 
			$cssContent = $helper->getCaptionData();
			$arrCaptions = array();
			$arrCaptions = $helper->getArrCaptionClasses($cssContent);
			//Mage::log(__LINE__, null,'layer.log');
		//	Mage::log($arrCaptions, null,'layer.log');
			$this->getResponse()->setHeader('Content-type', 'application/json');
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('arrCaptions'=>$arrCaptions)));
		}
	}
	
	public function urlCaptionAction() {
		$helper = Mage::helper('revslider/data');
		$collection = Mage::getModel('revslider/caption')->getCollection(); 
			$arrayCaptions = array();
			foreach($collection as $caption) {
				$arrayCaptions[] = $caption->getData(); 
			}
		$contentCss = $helper->parseDbArrayToCss($arrayCaptions);
		echo $contentCss; 
	}
	
	/** save Animation**/
	 public function saveAnimationAction() {
		if ($data = $this->getRequest()->getParams()) {
			
			$animations = json_decode($data['anim_data'], true); 
			$helper = Mage::helper('revslider/data');
			$handle = $animations['handle'];
			unset($animations['handle']);
			$animationsArray['params'] =  stripslashes(json_encode($helper->ClearQuote($animations)));
			$animationsArray['handle'] = $handle;
			$collection = Mage::getModel('revslider/animation')->getCollection();
			$animation_id = null;
			foreach($collection as $anim) {
				if($anim['handle'] == $handle) {
					$animation_id = $anim['animation_id'];
					break;
				}
			}

			$model = Mage::getModel('revslider/animation'); 
			
			if($animation_id) {
				$model->load($animation_id);
			}
			
			$model->setData($animationsArray)
				  ->setAnimationId($animation_id)
				  ->save();
		
			$animModel = Mage::getModel('revslider/animation');
			$arrAnims = array();
			$arrAnims['customin'] =  $animModel->getCustomAnimations();
			$arrAnims['customout'] = $animModel->getCustomAnimations('customout');
			$arrAnims['customfull'] = $animModel->getFullCustomAnimations('array');
			//$arrAnims['test'] = 'okkkkkkkkk';
			$this->getResponse()->setHeader('Content-type', 'application/json');
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($arrAnims));

		
			
		}
	}
	

	public function slideAction() { 
		$this->loadLayout()
                ->_setActiveMenu('revslider/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__(' Manager Slides'), Mage::helper('adminhtml')->__('Manager Slides'))
                ->renderLayout();
	}
	
	public function mediaAction() {
		$this->loadLayout();
      //  $this->getLayout()->getBlock('content')->append($this->getLayout()->
        //                createBlock('revslider/slide/media'));
        $this->renderLayout();
	}
	
	public function saveimageAction() {

        if (isset($_FILES['filename']['name']) and (file_exists($_FILES['filename']['tmp_name']))) {
            Mage::log('uploading............', null, 'upload.log');
            try {
                $uploader = new Varien_File_Uploader('filename');
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png')); // or pdf or anything
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $path = Mage::getBaseDir('media') . DS . 'magentothem/revslider';
                $uploader->save($path, $_FILES['filename']['name']);
                $data['filename'] = $_FILES['filename']['name'];
        
                Mage::log('upload succesfully', null, 'upload.log');
                $this->_redirect('*/*/media');
                //return json_encode($data);
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/media');
            }
        }
    }
	
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('revslider/revslider');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $revsliderIds = $this->getRequest()->getParam('revslider');
        if(!is_array($revsliderIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($revsliderIds as $revsliderId) {
                    $revslider = Mage::getModel('revslider/revslider')->load($revsliderId);
                    $revslider->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($revsliderIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
	  public function massDeleteSlideAction() {
        $revsliderIds = $this->getRequest()->getParam('revslider');
	    if(!is_array($revsliderIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($revsliderIds as $revsliderId) {
                    $revslider = Mage::getModel('revslider/slide')->load($revsliderId);
                    $revslider->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($revsliderIds)
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
        $revsliderIds = $this->getRequest()->getParam('revslider');
        if(!is_array($revsliderIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($revsliderIds as $revsliderId) {
                    $revslider = Mage::getSingleton('revslider/revslider')
                        ->load($revsliderId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($revsliderIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
	public function exportSlideAction()  {
	
		/************Export Revslider**************/
	    $helper = Mage::helper('revslider/data'); 
		//$helper->importRevsliderCaption(); 
		$write = Mage::getSingleton("core/resource")->getConnection("core_write");
		$revCollection = Mage::getModel('revslider/revslider')->getCollection(); 
		$fp = fopen(Mage::getBaseDir().'\app\code\local\Magentothem\Revslider\sql/file.csv','w+');
		$arrayFields = array("revslider_id","title","alias","params");
		$paramString = Null; 
		fputcsv($fp, $arrayFields);
		foreach($revCollection as $revslider) {
			$revslider_id = $revslider->getRevsliderId(); 
			$title = $revslider->getTitle(); 
			$alias = $revslider->getAlias(); 
			$params = $revslider->getParams(); 
			$params = $helper->RevJsonDecode($params);
			if($params) {
				$paramString .= $helper->array_implode_with_keys($params); 
				$arrayFields = array($revslider_id,$title,$alias,$paramString);	
				fputcsv($fp, $arrayFields);
			}
		}
		fclose($fp);
		/************************Export revslider slide**************************/
		
		$revSlideCollection = Mage::getModel('revslider/slide')->getCollection(); 
		$fp = fopen(Mage::getBaseDir().'\app\code\local\Magentothem\Revslider\sql/file_slide.csv','w+');
		$arrayFields = array("slide_id","revslider_id","params","title","layers","status","s_order");
		fputcsv($fp, $arrayFields);
		
		$paramString = Null; 
		foreach($revSlideCollection as $revslider) {
			$slide_id = $revslider->getSlideId(); 
			$revslider_id = $revslider->getRevsliderId(); 
			$title = $revslider->getTitle(); 
			$params = $revslider->getParams(); 
			$params = $helper->RevJsonDecode($params);
			$layers = $revslider->getLayers(); 
			$layers = $helper->RevJsonDecode($layers);
			$layerString = "";
			foreach($layers as $layer) {
				//$layerString .= implode('|',$layer).'__'; 
				$layerString .= $helper->array_implode_with_keys($layer).'__'; 
			}
			
			$paramString .= $helper->array_implode_with_keys($params); 
		
			$status = $revslider->getStatus(); 
			$s_order = $revslider->getData('s_order'); 
			$arrayFields = array($slide_id,$revslider_id,$paramString,$title,$layerString,$status,$s_order);	
			fputcsv($fp, $arrayFields);
		}
		fclose($fp);
		/************Export Caption**************/
			$capCollection = Mage::getModel('revslider/caption')->getCollection(); 
			$fp = fopen(Mage::getBaseDir().'\app\code\local\Magentothem\Revslider\sql/file_caption.csv','w+');
			$arrayFields = array("caption_id","handle","settings","hover","params");
			fputcsv($fp, $arrayFields);
			$paramString = Null; 
			foreach($capCollection as $caption) {
				$caption_id = $caption->getCaptionId(); 
				$handle = $caption->getHandle(); 
				$settings = $caption->getSettings(); 
				$settings = $helper->RevJsonDecode($settings);
				$hover = $caption->getHover();
				$hover = $helper->RevJsonDecode($hover);
				$params = $caption->getParams(); 
				$params = $helper->RevJsonDecode($params);
				if($params) {
					$paramString .= $helper->array_implode_with_keys($params); 
					$settingString .= $helper->array_implode_with_keys($settings); 
					$hoverString .= $helper->array_implode_with_keys($hover); 
					$arrayFields = array($caption_id,$handle,$settingString,$hoverString,$paramString);	
					fputcsv($fp, $arrayFields);
				}
			}
			fclose($fp);
				/************Export Animation **************/
			$paramString = Null; 	
			$aniCollection = Mage::getModel('revslider/animation')->getCollection(); 
			$fpa = fopen(Mage::getBaseDir().'\app\code\local\Magentothem\Revslider\sql/file_animation.csv','w+');
			$arrayFields = array("animation_id","handle","params");
			fputcsv($fpa, $arrayFields);
			$params = array();
			foreach($aniCollection as $animation) {
				$animation_id = $animation->getAnimationId(); 
				$handle = $animation->getHandle(); 
				$params = $animation->getParams(); 		 
				$params = $helper->RevJsonDecode($params);
				if($params) {
					$paramString .= $helper->array_implode_with_keys($params); 
				
					$arrayFields = array($animation_id,$handle,$paramString);	
					fputcsv($fpa, $arrayFields);
				}
			}
			fclose($fpa);	
			
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('revslider')->__('Item was  successfully Exported'));
			
			$this->_redirect('*/*/');
	}
  
    public function exportCsvAction()
    {
        $fileName   = 'revslider.csv';
        $content    = $this->getLayout()->createBlock('revslider/adminhtml_revslider_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'revslider.xml';
        $content    = $this->getLayout()->createBlock('revslider/adminhtml_revslider_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}