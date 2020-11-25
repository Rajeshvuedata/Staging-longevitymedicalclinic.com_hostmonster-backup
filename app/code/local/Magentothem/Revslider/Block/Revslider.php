<?php
class Magentothem_Revslider_Block_Revslider extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getRevslider()     
     { 
        if (!$this->hasData('revslider')) {
            $this->setData('revslider', Mage::registry('revslider'));
        }
        return $this->getData('revslider');
        
    }
	
	public function _toHtml() { 
		$enabled_module = Mage::getStoreConfig('revslider/revslider_config/enabled');
		if(!$enabled_module) return; 
		$html = NULL;
		$revModel = Mage::getModel('revslider/revslider');
		$helper = Mage::helper('revslider/data');
		$mediaUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
		
		$helper->initCss();
		$baseUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
		$allRevsliders = $revModel->getAllRevsliders(); //echo "<pre>"; print_r($allRevsliders); die;
		 foreach ($allRevsliders as $rev_id => $rev_slide) {
		 
			$revSlider = $revModel->getRevsliderById($rev_id);
			$revParams = $revSlider->getParams();
			$revParamArray = $helper->RevJsonDecode($revParams);
			//Mage::log($revParamArray, null, 'layer.log'); 
			$sliderType = $revParamArray['slide_layout'];
			$grid_width = $revParamArray['grid_width'];
			$grid_height = $revParamArray['grid_height'];			
			 if ($revParamArray['position_on'] == trim($this->getBlockPosition())) {
				$containerStyle = "";
				$htmlTimerBar = "";
				$timerBar =  true;
				if($timerBar == "true")
					$timerBar = $revParamArray["timer_on"];
				switch($timerBar){
					case "top":
						$htmlTimerBar = '<div class="tp-bannertimer"></div>';
					break;
					case "bottom":
						$htmlTimerBar = '<div class="tp-bannertimer tp-bottom"></div>';
					break;
				}

				
				//add background color
				$backgrondColor = trim($revParamArray["bg_color"]);
				if ( !empty($backgrondColor) ) $containerStyle .= "background-color:$backgrondColor;";
				//set padding
				$containerStyle .= "padding:" . $revParamArray["padding_border"] . "px;";
				//set margin:
				$containerStyle .= "margin-left:" . $revParamArray["margin_left"] . "px;";
				$containerStyle .= "margin-right:" . $revParamArray["margin_right"] . "px;";
				$containerStyle .= "margin-top:" . $revParamArray["margin_top"] . "px;";
				$containerStyle .= "margin-bottom:" . $revParamArray["margin_bottom"] . "px;";
				$sliderWrapperClass = "rev_slider_wrapper";
				$sliderClass = "rev_slider";
				$bannerStyle = "";
				//echo "<pre>"; print_r($revParamArray); die; 
					$statusImage = trim($revParamArray['bg_image']);
					if($statusImage == 'true') {
						$backgroundImage = $revParamArray['bg_url'];
						if($backgroundImage){
							$bannerStyle .= "background-image:url($backgroundImage) ;background-repeat: no-repeat no-repeat;";
						}
					}
					$bannerWidth = $revParamArray['grid_width'];
					$bannerHeight = $revParamArray['grid_height'];
					$ResponsiveStyles =false;
				switch($sliderType){
						default:
						case "fix":
							$bannerStyle .= "height:".$bannerHeight."px;width:".$bannerWidth."px;";
							$containerStyle .= "height:".$bannerHeight."px;width:".$bannerWidth."px;";
						break;
						
						case "auto_res":
							$sliderWrapperClass .= " fullwidthbanner-container";
							$sliderClass .= " fullwidthabanner";
							$bannerStyle .= "max-height:".$bannerHeight."px;height:".$bannerHeight.";";
							$containerStyle .= "max-height:".$bannerHeight."px;";						
						break;
						case "full":
							//$containerStyle .= "height:".$bannerHeight."px;";
							$sliderWrapperClass .= " fullscreen-container";
							$sliderClass .= " fullscreenbanner";
						break;

					}		
			   $position = $this->getBlockPosition();
			   $sliderHtmlID = "rev_slider_" . $position;
				$sliderHtmlID_wrapper = $sliderHtmlID . "_wrapper";
				if ($ResponsiveStyles) {
					Mage::helper('revslider/data')->getRespStyles($sliderHtmlID, $sliderHtmlID_wrapper, $revParamArray);
				}
				
				 $countSlide = count($rev_slide[0]);
				 $order = 0;		
				 $html .= '<div id="' . $sliderHtmlID_wrapper . '" class="' . $sliderWrapperClass . '" style="' . $containerStyle . '" >
								<div id="' . $sliderHtmlID . '" class="' . $sliderClass . '" style="' . $bannerStyle . '"  >';
				 $html .='<ul>';
				foreach($rev_slide[0] as $slide_id =>$slides) {
					$slideParams = Mage::getModel('revslider/slide')->load($slide_id)->getParams();
					$slideParams = $helper->RevJsonDecode($slideParams); 
					//Mage::log($slideParams, null, 'layer.log'); 
					$transition = $slideParams['slide_transition'];
					$slotAmount = $slideParams["slot_amount"];
					$htmlParams = $revModel->getInfoParams($revParamArray, $slideParams, $countSlide, $order);
					$bgType = $slideParams["bg_type"];
					
					if($bgType != "external"){
							$urlSlideImage = $mediaUrl.$slideParams['image_url'];
						
					}else{
						
							$urlSlideImage = $slideParams['bg_external'];
					}
					
							$styleImage = "";
							$urlImageTransparent = $baseUrl."js/magentothem/revslider/images/transparent.png";
							
							switch($bgType){
								case "trans":
									$urlSlideImage = $urlImageTransparent;
								break;
								case "solid":
									$urlSlideImage = $urlImageTransparent;
									$slideBGColor = $slideParams["bg_color"];
								
									$styleImage = "style='background-color:".$slideBGColor."'";
								break;	
							}
					
							$lazyLoad = isset($revParamArray["lazy_load"]) ? $revParamArray["lazy_load"] : '';
							
							//additional params
							$imageAddParams = "";
							if($lazyLoad == "on1"){
								$imageAddParams .= "data-lazyload=\"$urlSlideImage\"";
								$urlSlideImage = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS)."magentothem/revslider/images/dummy.png";
							}
							
							//$imageAddParams .= $htmlImageCentering;
							
							//additional background params
							$bgFit = $slideParams["bg_fit"];
							$bgFitX = intval($slideParams["bg_fit_x"]);
							$bgFitY = intval($slideParams["bg_fit_y"]);
							
							$bgPosition = intval($slideParams["bg_position"]);
							$bgPositionX = intval($slideParams["bg_position_x"]);
							$bgPositionY = intval($slideParams["bg_position_y"]);
							
							$bgRepeat = $slideParams["bg_repeat"];
							
							if($bgPosition == 'percentage'){
								$imageAddParams .= ' data-bgposition="'.$bgPositionX.'% '.$bgPositionY.'%"';
							}else{
								$imageAddParams .= ' data-bgposition="'.$bgPosition.'"';
							}
							
							//check for kenburn & pan zoom
							$kenburn_effect = $slideParams["kenburn_effect"];
							$kb_duration = intval($slideParams["kb_duration"]);
							$kb_ease = $slideParams["kb_easing"];
							$kb_start_fit = $slideParams["kb_start_fit"];
							$kb_end_fit =$slideParams["kb_end_fit"];
				
							$kb_pz = '';
							
							if($kenburn_effect == "on" && ($bgType == 'image' || $bgType == 'external')){
								$kb_pz.= ' data-kenburns="on"';
								//$kb_pz.= ' data-rotationstart="'.$kb_rotation_start.'"';
								//$kb_pz.= ' data-rotationend="'.$kb_rotation_end.'"';
								$kb_pz.= ' data-duration="'.$kb_duration.'"';
								$kb_pz.= ' data-ease="'.$kb_ease.'"';
								$kb_pz.= ' data-bgfit="'.$kb_start_fit.'"';
								$kb_pz.= ' data-bgfitend="'.$kb_end_fit.'"';
								
								$bgEndPosition = $slideParams["bg_end_position"];
								$bgEndPositionX = intval($slideParams["bg_end_position_x"]);
								$bgEndPositionY = intval($slideParams["bg_end_position_y"]);
								
								if($bgEndPosition == 'percentage'){
									$kb_pz.= ' data-bgpositionend="'.$bgEndPositionX.'% '.$bgEndPositionY.'%"';
								}else{
									$kb_pz.= ' data-bgpositionend="'.$bgEndPosition.'"';
								}
							}else{ 
								//only set if kenburner is off
								if($bgFit == 'percentage'){
									$imageAddParams .= ' data-bgfit="'.$bgFitX.'% '.$bgFitY.'%"';
								}else{
									$imageAddParams .= ' data-bgfit="'.$bgFit.'"';
								}
								
								$imageAddParams .= ' data-bgrepeat="'.$bgRepeat.'"';
							}
						
						 $html .='<li  data-transition="' . $transition . '" data-slotamount="' . $slotAmount . '" ' . $htmlParams . '>';
						 $html .= '<img class="bg_image_layout" src="' . $urlSlideImage . '" ' . $styleImage . ' alt="Image Layer"  '.$imageAddParams. $kb_pz .' />';
				
								foreach($slides as $key => $layers) {
										$html.=$this->createLayerHtml($layers);
								}
						 $html .='</li>';
					
					$order++;
				}
				$html .='</ul>'; //return $html; 
				$html .=$htmlTimerBar;
				$html .= $this->createScript($revParamArray,$countSlide,$slide_id,$sliderHtmlID );
				$html .='</div></div>';
			}
			//Mage::log($html, null,'slide1.log');
			//$html .=$this->addJsHeader();
		 }
		return $html; 
	}
	
	public function addJsHeader(){
		$jsUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS);      
		$urlCaption = $jsUrl.'magentothem/revslider/css/caption.php';
	 ?>
		 <script type="text/javascript">
			  jQuery(document).ready(function() {
				var urlCssFile = "<?php echo $urlCaption;  ?>"
				jQuery("head").append("<link>");
				var css = jQuery("head").children(":last");
				css.attr({
					  rel:  "stylesheet",
					  type: "text/css",
					  href: urlCssFile,
					  media:"screen"
				});
			 });
		 </script>
	 <?php 
	}
	
	
	
	 public function createLayerHtml($layers = array()) {
		$helper = Mage::helper('revslider/data');
		$mediaUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
        $type = $layers['type'];
        $class = $layers['style'];
        //set output class:
		$model_anim = Mage::getModel('revslider/animation');
		$customAnimations = $model_anim->getCustomAnimations('customin'); //get all custom animations
		$customEndAnimations = $model_anim->getCustomAnimations('customout'); //get all custom animations
		$startAnimations =$model_anim->toLayerAnimationList(false); //only get the standard animations
		$endAnimations = $model_anim->toEndAnimation(false); //only get the standard animations			

					
		$animation = $layers["animation"];
		
		if($animation == "fade") $animation = "tp-fade";

		$customin = '';
		if(!array_key_exists($animation, $startAnimations) && array_key_exists($animation, $customAnimations)){ //if true, add custom animation
			$customin.= 'data-customin="';
			$animArr = $model_anim->getCustomAnimationByHandle($customAnimations[$animation]);
			if($animArr !== false) $customin.= $model_anim->parseCustomAnimationByArray($animArr);						
			$customin.= '"';
			$animation = 'customin';
		}

		if(strpos($animation, 'customin') !== false || strpos($animation, 'customout') !== false) $animation = "tp-fade";

		//set output class:
		$outputClass = "tp-caption ". trim($class);
			$outputClass = trim($outputClass) . " ";
			
		$outputClass .= trim($animation);
        $left = $layers["left"];
        $top = $layers["top"];
        $speed = $layers["speed"];
        $time = $layers["time"];
        $easing = $layers["easing"];
		$randomRotate = 0;
		if(isset($layers["random_rotation"])) {
			$randomRotate = (int) $layers["random_rotation"] ? true : false;
		}

        $text = $layers["text"];
		if(ctype_xdigit($text)) {
			$text = $helper->hexToStr($text);
		}
        $htmlVideoAutoplay = "";
		$htmlVideoAutoplayOnlyFirstTime = "";
		$htmlVideoNextSlide = "";
		$htmlVideoThumbnail = "";
		$htmlMute = '';
		$htmlCover = '';
		$htmlDotted = '';
		$htmlRatio = '';
		$htmlRewind = '';
		
		$alignHor = $layers["align_hor"];
		$alignVert = $layers["align_vert"];
		//Mage::log($alignHor.'---'.$alignVert, null, 'align.log');
		$htmlPosX = "";
		$htmlPosY = "";
		switch($alignHor){
			default:
			case "left":
				$htmlPosX = "data-x=\"".$left."\"";
			break;
			case "center":
				$htmlPosX = "data-x=\"center\" data-hoffset=\"".$left."\"";
			break;
			case "right":
				$left = (int)$left*-1;
				$htmlPosX = "data-x=\"right\" data-hoffset=\"".$left."\"";
			break;
		}
		switch($alignVert){
			default:
			case "top":
				$htmlPosY = "data-y=\"".$top."\" ";
			break;
			case "middle":
				$htmlPosY = "data-y=\"center\" data-voffset=\"".$top."\"";
			break;
			case "bottom":
				$top = (int)$top*-1;
				$htmlPosY = "data-y=\"bottom\" data-voffset=\"".$top."\"";
			break;						
		}
		$isFullWidthVideo= "";
        //set html:
        $html = "";
		$htmlCorners = "";
        switch ($type) {

            default:
            case "text":
                $html = $text;
				$cornerLeft = $layers["corner_left"];
				$cornerRight = $layers["corner_right"];
				switch($cornerLeft){
					case "curved":
						$htmlCorners .= "<div class='frontcorner'></div>";
					break;
					case "reverced":
						$htmlCorners .= "<div class='frontcornertop'></div>";							
					break;
				}
				
				switch($cornerRight){
					case "curved":
						$htmlCorners .= "<div class='backcorner'></div>";
					break;
					case "reverced":
						$htmlCorners .= "<div class='backcornertop'></div>";							
					break;
				}

			//add resizeme class
				$resizeme = $layers["resizeme"];
				if($resizeme == "true" || $resizeme == "1")
				$outputClass .= ' tp-resizeme';
                break;
            case "image":
                $urlImage = $mediaUrl.$layers["image_url"];
				$additional = "";
				$scaleX = $layers["scaleX"];
				$scaleY = $layers["scaleY"];
				if($scaleX != '') $additional .= ' data-ww="'.$scaleX.'"';
				if($scaleY != '') $additional .= ' data-hh="'.$scaleY.'"';
                $html = '<img src="' . $urlImage . '" alt="' . $text . '" '.$additional.'>';
                $imageLink = $layers["link"];
			
                if (!empty($imageLink)) {
                    $openIn = $layers["link_open_in"];

                    $target = "";
                    if ($openIn == "new")
                        $target = ' target="_blank"';

                    $html = '<a href="' . $imageLink . '"' . $target . '>' . $html . '</a>';
                }
                break;
            case "video":
					$videoType = trim($layers["video_type"]); 
					$videoData = $layers["video_data"];
					
					if(!empty($videoData)){
						$videoData = (array)$videoData;
						
						$isFullWidthVideo = $videoData["fullwidth"];
						$isFullWidthVideo = $helper->strToBool($isFullWidthVideo);
					}else{
						$videoData = array();
					}
			
                $videoID = trim($layers["video_id"]);
                $videoWidth = trim($layers["video_width"]);
                $videoHeight = trim($layers["video_height"]);
				$rewind = $videoData["forcerewind"];
				$rewind = $helper->strToBool($rewind);
				$htmlRewind = ($rewind == true) ? ' data-forcerewind="on"' : '';
				if($isFullWidthVideo == true){
					$videoWidth = "100%";
					$videoHeight = "100%";
				}

                switch ($videoType) {
                    case "youtube":
                        $html = "<iframe src='http://www.youtube.com/embed/{$videoID}?hd=1&amp;wmode=opaque&amp;controls=1&amp;showinfo=0;rel=0' width='{$videoWidth}' height='{$videoHeight}' style='width:{$videoWidth}px;height:{$videoHeight}px;'></iframe>";
                        break;
                    case "vimeo":
                        $html = "<iframe src='http://player.vimeo.com/video/{$videoID}?title=0&amp;byline=0&amp;portrait=0' width='{$videoWidth}' height='{$videoHeight}' style='width:{$videoWidth}px;height:{$videoHeight}px;'></iframe>";
                        break;
					case "html5":
						$html = $this->getHtml5LayerHtml($videoData);
				
						$cover = $videoData["cover"];
						$cover = $helper->strToBool($cover);
					
						if($cover == true){
							$htmlCover = ' data-forceCover="1"';
							$dotted = $videoData["dotted"];
							if($dotted !== 'none')
								$htmlDotted = ' data-dottedoverlay="'.$dotted.'"';	
							
							$ratio = $videoData["ratio"];
							if(!empty($ratio))
								$htmlRatio = ' data-aspectratio="'.$ratio.'"';
						}
					break;	
                    default:
                        echo "wrong video type: $videoType";
                        break;
                }

               	//set video autoplay, with backward compatability
						if(array_key_exists("autoplay", $videoData))
							$videoAutoplay = $videoData["autoplay"];
						else	//backword compatability
							$videoAutoplay = $layers["video_autoplay"];
						
						//set video autoplayonlyfirsttime, with backward compatability
						if(array_key_exists("autoplayonlyfirsttime", $videoData))
							$videoAutoplayOnlyFirstTime = $videoData["autoplayonlyfirsttime"];
						else
							$videoAutoplayOnlyFirstTime = "";
						
						$videoAutoplay = $helper->strToBool($videoAutoplay);
						$videoAutoplayOnlyFirstTime = $helper->strToBool($videoAutoplayOnlyFirstTime);
						$mute = $videoData["mute"];
						$mute = $helper->strToBool($mute);
						$htmlMute = ($mute)	? ' data-volume="mute"' : '';
						
						if($videoAutoplay == true)
							$htmlVideoAutoplay = '			data-autoplay="true"'."\n";								
						else
							$htmlVideoAutoplay = '			data-autoplay="false"'."\n";
						
						if($videoAutoplayOnlyFirstTime == true && $videoAutoplay == true)
							$htmlVideoAutoplayOnlyFirstTime = '        data-autoplayonlyfirsttime="true"'."\n";								
						else
							$htmlVideoAutoplayOnlyFirstTime = '        data-autoplayonlyfirsttime="false"'."\n";
							
						$videoNextSlide = $videoData["nextslide"];
						$videoNextSlide = $helper->strToBool($videoNextSlide);
						
						if($videoNextSlide == true)
							$htmlVideoNextSlide = '			data-nextslideatend="true"'."\n";								
							
						$videoThumbnail = @$videoData["previewimage"];
						
						if(trim($videoThumbnail) !== '') $htmlVideoThumbnail = 'data-thumbimage="'.$videoThumbnail.'"'."\n";
						

					
					
                break;
        }

        //handle end transitions:
		
        $endTime = trim($layers["endtime"]);

        $htmlEnd = "";
			if (!empty($endTime)) { 
				$htmlEnd = "data-end=\"$endTime\"";
			}
			
			$endSpeed = trim($layers["endspeed"]);
            if (!empty($endSpeed))
                $htmlEnd .= " data-endspeed=\"$endSpeed\"";
				
            $endEasing = trim($layers["endeasing"]);
			
            if (!empty($endSpeed) && $endEasing != "nothing")
                $htmlEnd .= " data-endeasing=\"$endEasing\"";
				
            //add animation to class
            $endAnimation = trim($layers["endanimation"]);
			if($endAnimation == "fade") $endAnimation = "tp-fade";
            if (!empty($endAnimation) && $endAnimation != "auto")
                $outputClass .= " " . $endAnimation;

			//slide link
			$htmlLink = "";
			$slideLink = $layers["link_slide"];

        if (!empty($slideLink) && $slideLink != "nothing") {

            if (!empty($slideLink))
                $htmlLink = " data-linktoslide=\"$slideLink\"";
        }
		
		if($isFullWidthVideo == true){
				$htmlPosX = "data-x=\"0\"";
				$htmlPosY = "data-y=\"0\"";
				$outputClass .= " fullscreenvideo";
		}
		
        //hidden under resolution
        $htmlHidden = "";
		if($htmlCorners != ""){
					$htmlCorners = $htmlCorners."\n";
		}
        $layerHidden = $layers["hiddenunder"];
        if ($layerHidden == "true")
            $htmlHidden = ' data-captionhidden="on"';
       // $htmlParams = $htmlEnd . $htmlLink . $htmlVideoAutoplay . $htmlHidden. $htmlRatio. $htmlCover.$htmlDotted;
		$htmlParams = $htmlEnd.$htmlLink.$htmlVideoAutoplay.$htmlVideoAutoplayOnlyFirstTime.$htmlVideoNextSlide.$htmlVideoThumbnail.$htmlHidden.$htmlMute.$htmlCover.$htmlDotted.$htmlRatio.$htmlRewind;
		//Mage::log($htmlPosX.'---'.$htmlPosY, null,'align.log');	
        $layerData = '<div class="' . $outputClass . '"
             data-speed="' . $speed . '"
             data-start="' . $time . '"
			 ' . $htmlPosX . '
			 ' . $htmlPosY . '
             data-easing="' . $easing . '" ' . $htmlParams . '>' . $html . $htmlCorners. '</div>';
        return $layerData;
    }
	
	
	private function createScript($RevParams = array(),$countSlides =0,$sliderID = null,$sliderHtmlID= null ){
			$sliderType = $RevParams["slide_layout"];
			$optFullWidth = ($sliderType == "auto_res")?"on":"off";
			$optFullScreen = "off";
			if($sliderType == "full"){
				$optFullWidth = "off";
				$optFullScreen = "on";
			}
			
			
			//set thumb amount
			$numSlides = $countSlides;
			$thumbAmount = (int)$RevParams["thumb_amount"];
			if($thumbAmount > $numSlides)
				$thumbAmount = $numSlides;
				
			
			//get stop slider options
			$stopSlider = "off";
			if(isset($RevParams["stop_slider"])) {
				$stopSlider = $RevParams["stop_slider"];
			 }
			 $stopAfterLoops = $RevParams["after_loop"];
			 $stopAtSlide = $RevParams["stop_at"];
			 
			 if($stopSlider == "off"){
				 $stopAfterLoops = "-1";
				 $stopAtSlide = "-1";
			 }
			
			// set hide navigation after
			$hideThumbs = 0; 
			if(isset($RevParams["hide_thumbs"])) {
				$hideThumbs = $RevParams["hide_thumbs"];
			}
			if(is_numeric($hideThumbs) == false)
				$hideThumbs = "0";
			else{
				$hideThumbs = (int)$hideThumbs;
				if($hideThumbs < 10)
					$hideThumbs = 10;
			}
			
			$alwaysOn = $RevParams["show_nav"];
			if($alwaysOn == "true")
				$hideThumbs = "0";
			
				
			//treat hide slider at limit
			$hideSliderAtLimit = $RevParams["hide_slider_under"];
			if(!empty($hideSliderAtLimit))
				$hideSliderAtLimit++;
			//this option is disabled in full width slider
			if($sliderType == "auto_res")
				$hideSliderAtLimit = "0";
			
			$hideCaptionAtLimit = $RevParams["hide_defined_layers_under"];
			if(!empty($hideCaptionAtLimit))
				$hideCaptionAtLimit++;
			
			$hideAllCaptionAtLimit = $RevParams["hide_all_layers_under"];
			if(!empty($hideAllCaptionAtLimit))
				$hideAllCaptionAtLimit++;
			
			//start_with_slide
			$startWithSlide = $RevParams['start_with_slide'];
			
	 	  //modify navigation type (backward compatability)
			$arrowsType = $RevParams["navigation_arrow"];
			switch($arrowsType){
				case "verticalcentered":
					$arrowsType = "solo";
				break;
			}
			
			//More Mobile Options
			$hideThumbsOnMobile ="";
			$hideThumbsUnderResolution= "";
			$hideBulletsOnMobile = "";
			$hideArrowsOnMobile = "";
			if(isset($RevParams["hide_thumbs_on_mobile"])) {
				$hideThumbsOnMobile = $RevParams["hide_thumbs_on_mobile"];
			}
			if(isset($RevParams["hide_bullets_on_mobile"])) {
				$hideBulletsOnMobile = $RevParams["hide_bullets_on_mobile"];
			}
			if(isset($RevParams["hide_arrows_on_mobile"])) {
				$hideArrowsOnMobile = $RevParams["hide_arrows_on_mobile"];
			}
			if(isset($RevParams["hide_thumbs_under_resolution"])) {
				$hideThumbsUnderResolution = $RevParams["hide_thumbs_under_resolution"];
			}
			
			$videoJsPath = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS)."magentothem/revslider/rs-plugin/videojs/";			
			?>
			
			<script type="text/javascript">
				var tpj=jQuery;				
				
				var revapi<?php echo $sliderID?>;
				
				jQuery(function($) {
							
				if($('#<?php echo $sliderHtmlID?>').revolution == undefined)
					revslider_showDoubleJqueryError('#<?php echo $this->sliderHtmlID?>');
				else
				    $('#<?php echo $sliderHtmlID?>').show().revolution(
					{
						dottedOverlay:"<?php echo $RevParams["dotted_overlay"];?>",
						delay:<?php echo $RevParams["delay"]?>,
						startwidth:<?php echo $RevParams["grid_width"]?>,
						startheight:<?php echo $RevParams["grid_height"]?>,
						hideThumbs:<?php echo $hideThumbs?>,
						
						thumbWidth:<?php echo $RevParams["thumb_width"]?>,
						thumbHeight:<?php echo $RevParams["thumb_hight"]?>,
						thumbAmount:<?php echo $thumbAmount?>,
						
						navigationType:"<?php echo $RevParams["navigation_type"]?>",
						navigationArrows:"<?php echo $arrowsType?>",
						navigationStyle:"<?php echo $RevParams["navigation_style"]?>",
						
						touchenabled:"<?php echo $RevParams["touch_enable"]?>",
						onHoverStop:"<?php echo $RevParams["on_hover"]?>",
						
						navigationHAlign:"<?php echo $RevParams["nav_align_hor"]?>",
						navigationVAlign:"<?php echo $RevParams["nav_align_vert"]?>",
						navigationHOffset:<?php echo $RevParams["nav_offset_hor"]?>,
						navigationVOffset:<?php echo $RevParams["nav_offset_vert"]?>,
						soloArrowLeftHalign:"<?php echo $RevParams["leftarrow_align_hor"]?>",
						soloArrowLeftValign:"<?php echo $RevParams["leftarrow_align_vert"]?>",
						soloArrowLeftHOffset:<?php echo $RevParams["leftarrow_offset_hor"]?>,
						soloArrowLeftVOffset:<?php echo $RevParams["leftarrow_offset_vert"]?>,
						soloArrowRightHalign:"<?php echo $RevParams["rightarrow_align_hor"]?>",
						soloArrowRightValign:"<?php echo $RevParams["rightarrow_align_vert"]?>",
						soloArrowRightHOffset:<?php echo $RevParams["rightarrow_offset_hor"]?>,
						soloArrowRightVOffset:<?php echo $RevParams["rightarrow_offset_vert"]?>,
								
						shadow:<?php echo $RevParams["shadow_type"]?>,
						fullWidth:"<?php echo $optFullWidth?>",
						fullScreen:"<?php echo $optFullScreen?>",
						stopLoop:"<?php echo $stopSlider?>",
						stopAfterLoops:<?php echo $stopAfterLoops?>,
						stopAtSlide:<?php echo $stopAtSlide?>,
						
						shuffle:"<?php if(isset($RevParams["shuffle_mode"])) { echo $RevParams["shuffle_mode"]; } ?>",
						
						<?php if($RevParams["slide_layout"] == "auto_res"){ ?>autoHeight:"<?php echo isset($RevParams["auto_height"]) ? $RevParams["auto_height"] : 0 ; ?>",<?php }  ?>
						
						<?php if($RevParams["slide_layout"] == "auto_res" || $RevParams["slide_layout"] == "full"){ ?>	forceFullWidth:"<?php echo $RevParams["full_width"]; ?>",<?php }  ?>
						
						<?php if($RevParams["slide_layout"] == "full"){ ?>fullScreenAlignForce:"<?php echo $RevParams["full_screen"] ?>",<?php }  ?>
						
						<?php if($RevParams["slide_layout"] == "full"){ ?>minFullScreenHeight:"<?php echo $RevParams["fullscreen_min_height"] ?>",<?php }  ?>
						hideThumbsOnMobile:"<?php echo $hideThumbsOnMobile?>",
						hideBulletsOnMobile:"<?php echo $hideBulletsOnMobile?>",
						hideArrowsOnMobile:"<?php echo $hideArrowsOnMobile?>",
						hideThumbsUnderResolution:<?php echo $hideThumbsUnderResolution?>,
						hideSliderAtLimit:<?php echo $hideSliderAtLimit?>,
						hideCaptionAtLimit:<?php echo $hideCaptionAtLimit?>,
						hideAllCaptionAtLilmit:<?php echo $hideAllCaptionAtLimit?>,
						startWithSlide:<?php echo $startWithSlide?>,
						videoJsPath:"<?php echo $videoJsPath?>",
						fullScreenOffsetContainer: "<?php echo $RevParams["fullscreen_offset_container"];?>"	
					});
				
				});	//ready
				
			</script>
			
			<?php			
		}
		
		public function getHtml5LayerHtml($data){
			$helper = Mage::helper('revslider/data');
			$urlPoster = $data["urlPoster"];
			$urlMp4 = $data["urlMp4"];
			$urlWebm = $data["urlWebm"];
			$urlOgv = $data["urlOgv"];
			$width = $data["width"];
			$height = $data["height"];
			
			$ids = $data["attrID"];
			$ids = $data["attrID"];
			$classes = $data["attrClasses"];
			$title = $data["attrTitle"];
			$rel = $data["attrRel"];
			$ids = ($ids != '') ? ' id="'.$ids.'"' : '';
			$classes = ($classes != '') ? ' '.$classes : '';
			$title = ($title != '') ? ' title="'.$title.'"' : '';
			$rel = ($rel != '') ? ' rel="'.$rel.'"' : '';
			
			$fullwidth = $data["fullwidth"];
			$fullwidth = $helper->strToBool($fullwidth);
			
			$videoloop = $data["videoloop"];
			$videoloop = $helper->strToBool($videoloop);
			
			$controls = $data["controls"];
			$controls = $helper->strToBool($controls);
			
			if($fullwidth == true){
				$width = "100%";
				$height = "100%";
			}
			
			$videoloop = ($videoloop == true) ? ' loop' : '';
			$controls = ($controls == true) ? '' : ' controls';
			
			$htmlPoster = "";
			if(!empty($urlPoster))
				$htmlPoster = "poster='".$urlPoster."'";
				
			$htmlMp4 = "";
			if(!empty($urlMp4))
				$htmlMp4 = "<source src='".$urlMp4."' type='video/mp4' />";
			$htmlWebm = "";
			if(!empty($urlWebm))
				$htmlWebm = "<source src='".$urlWebm."' type='video/webm' />";
				
			$htmlOgv = "";
			if(!empty($urlOgv))
				$htmlOgv = "<source src='".$urlOgv."' type='video/ogg' />";
			
			$html =	"<video class=\"video-js vjs-default-skin".$classes."\"".$ids.$title.$rel.$videoloop.$controls." preload=\"none\" width=\"".$width."\" height=\"".$height."\" \n";
	   		$html .=  $htmlPoster ." data-setup=\"{}\"> \n";
	        $html .=  $htmlMp4."\n";
	        $html .=  $htmlWebm."\n";
	        $html .=  $htmlOgv."\n";
			$html .=  "</video>\n";
			
			return($html);
		}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}