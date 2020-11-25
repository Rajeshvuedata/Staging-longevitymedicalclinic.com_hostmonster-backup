<?php

class Magentothem_Revslider_Model_Revslider extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('revslider/revslider');
    }
	
	public function getAllRevsliders() {
        $collection = Mage::getModel('revslider/revslider')->getCollection();
        //$collection->getSelect();
        //->where('find_in_set(0, store_ids) OR find_in_set(?, store_ids)', (int) (Mage::app()->getStore()->getId()))
        //->where('slide_id=1');
        $RevslideArrays = array();
		$helper = Mage::helper('revslider/data');
        foreach ($collection as $revslider) {
            $slideContent = Mage::getModel('revslider/slide')->getSlideByRevId($revslider->getRevsliderId());
            $LayerArrays = array();
            foreach ($slideContent as $slide) {
                $layerData = $slide->getLayers();
                $layerJsons = $helper->RevJsonDecode($layerData);
                    foreach ($layerJsons as $layer) {
                        $LayerArrays[$slide->getSlideId()][] = $layer;
                    }
            }
          
            $RevslideArrays[$revslider->getRevsliderId()][] = $LayerArrays;
        }
		  //Mage::Log($RevslideArrays, null, 'revslider.log');
        if ($RevslideArrays)
            return $RevslideArrays;
        return array();
    }
	
	public function getRevsliderById($id) {
		$data = Mage::getModel('revslider/revslider')->load($id);
		return $data; 
	}
	
	 public function getInfoParams($slideData = array(), $slideParams = array(),$countSlides = NULL, $order = 0) {
     
        $transition = $slideParams["slide_transition"];
        $slotAmount = $slideParams["slot_amount"];
        $urlSlideImage = $slideParams['image_url'];
        $mediaUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
        $htmlThumb = "";
        $enableThumbs = 1;
        if ($enableThumbs == 1) {
            $urlThumb = $slideParams["slide_thumb"];
            if (empty($urlThumb))
                $urlThumb = $urlSlideImage;

            $htmlThumb = 'data-thumb="' . $urlThumb . '" ';
        }
        $htmlLink = "";
        $enableLink = $slideParams["enable_link"];
        if ($enableLink) {
			$linkType= null;
			if(isset($slideParams["link_type"])) {
				$linkType = $slideParams["link_type"];
			}
			  

            switch ($linkType) {
                default:
                case "regular":
                    $link = $slideParams["link"];
                    $linkOpenIn = $slideParams["link_open_in"];
                    $htmlTarget = "";
                    if ($linkOpenIn == "new")
                        $htmlTarget = ' data-target="_blank"';
                    $htmlLink = "data-link=\"$link\" $htmlTarget ";
                    break;

                case "slide":
                    $slideLink = $slideParams["slide_link"];
                    if (!empty($slideLink) && $slideLink != "nothing") {
                        if (!empty($slideLink))
                            $htmlLink = "data-link=\"slide\" data-linktoslide=\"$slideLink\" ";
                    }
                    break;
            }

            //set link position:
            $linkPos = $slideParams["link_pos"];
            if (trim($linkPos) == "back"){
                $htmlLink .= ' data-slideindex="back"';
			}
						
        }
	
        //set delay
        $htmlDelay = "";
        $delay = $slideParams["delay"];
        if (!empty($delay) && is_numeric($delay))
            $htmlDelay = "data-delay=\"$delay\" ";
        //get duration
        $htmlDuration = "";
        $duration = $slideParams["transition_duration"];
        if (!empty($duration) && is_numeric($duration))
            $htmlDuration = "data-masterspeed=\"$duration\" ";
        //get rotation
        $htmlRotation = "";
        $rotation = $slideParams["transition_rotation"];
        if (!empty($rotation)) {
            $rotation = (int) $rotation;
            if ($rotation != 0) {
                if ($rotation > 720 && $rotation != 999)
                    $rotation = 720;
                if ($rotation < -720)
                    $rotation = -720;
            }
            $htmlRotation = "data-rotate=\"$rotation\" ";
        }

        //$video = $this->getSlideFullWidthVideo($params);
        $htmlImageCentering = "";
		if(isset($slideParams["fullwidth_centering"])) {
			$fullWidthCentering = $slideParams["fullwidth_centering"];
		}
		$sliderType= $slideData['slide_layout'];
        if ($sliderType == "full" && (int) $fullWidthCentering)
            $htmlImageCentering = 'data-fullwidthcentering="on"';
        // start with slide
        //set first slide transition
         $htmlFirstTrans = "";
         $startWithSlide = $this->getStartSlide($slideData,$countSlides);
         //echo $order.'--'.$startWithSlide; 
         if ($order == $startWithSlide) {
			$firstTransActive= null;
			if(isset($slideData["first_tran_active"])) {
				$firstTransActive = $slideData["first_tran_active"];
			}
            if ($firstTransActive== 'on') {
                $firstTransition = $slideData["first_tran_type"];
                $htmlFirstTrans .= " data-fstransition=\"$firstTransition\"";

                $firstDuration = $slideData["first_tran_du"];
                if (!empty($firstDuration) && is_numeric($firstDuration))
                    $htmlFirstTrans .= " data-fsmasterspeed=\"$firstDuration\"";

                $firstSlotAmount = $slideData["first_tran_slot_amoun"];
                if (!empty($firstSlotAmount) && is_numeric($firstSlotAmount))
                    $htmlFirstTrans .= " data-fsslotamount=\"$firstSlotAmount\"";
               
            }
        }
        //$startWithSlide = $this->getStartSlide($sliderParams, $countSlides);
        $htmlParams = $htmlDuration . $htmlLink . $htmlThumb . $htmlDelay . $htmlRotation . $htmlFirstTrans;

        if ($htmlParams)
            return $htmlParams;
        return null;
    }
    
     public function getStartSlide ( $slideData, $countSlide )
    {
        // start with slide
        $startWithSlide = (int)$slideData["start_with_slide"];
     
        if ( is_numeric($startWithSlide) ) {
            $startWithSlide = (int)$startWithSlide;
            if ( $startWithSlide < 0 ) $startWithSlide = 0;
            if ( $startWithSlide >= $countSlide ) $startWithSlide = 0;
            
        } else
            $startWithSlide = 0;
        return $startWithSlide;
    }
}