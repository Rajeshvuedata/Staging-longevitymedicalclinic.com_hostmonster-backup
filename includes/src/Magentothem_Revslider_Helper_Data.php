<?php
class Magentothem_Revslider_Helper_Data extends Mage_Core_Helper_Abstract
{
  
  public function CreateOptionHtmls($array= array(),$value =1,$id=null, $name=null) {
          $select = '<select id="'.$id.'" name="'.$name.'">';
			foreach ($array as $key => $val) {
				if ($key == $value) {
					$slected = "selected =selected";
				} else {
					$slected = NULL;
				}
				$select .='<option ' . $slected . ' value ="' . $key . '" >' . $val . '</option>';
			}
			$select .='</select>';
			return $select;
    }
	
  public function toBackgroundPostionList($id =null,$name = null,$value= true) {
		$array= array(
				'center top'  => 'center top',
				'center right' => 'center right',
				'center bottom' => 'center bottom',
				'center center' => 'center center',
				'left top' => 'left top',
				'left center' => 'left center',
				'left bottom' => 'left bottom',
				'right top' => 'right top',
				'right center' => 'right center',
				'right bottom' => 'right bottom',
				'percentage' => '(x%, y%)',
		);
		return $this->CreateOptionHtmls($array,$value,$id, $name);
  }
  
    public function toEasingList($id =null,$name = null) {
		$array= array(
				"Linear.easeNone" => "Linear.easeNone",
				"Power0.easeIn" => "Power0.easeIn  (linear)",
				"Power0.easeInOut" => "Power0.easeInOut  (linear)",
				"Power0.easeOut" => "Power0.easeOut  (linear)",
				"Power1.easeIn" => "Power1.easeIn",
				"Power1.easeInOut" => "Power1.easeInOut",
				"Power1.easeOut" => "Power1.easeOut",
				"Power2.easeIn" => "Power2.easeIn",
				"Power2.easeInOut" => "Power2.easeInOut",
				"Power2.easeOut" => "Power2.easeOut",
				"Power3.easeIn" => "Power3.easeIn",
				"Power3.easeInOut" => "Power3.easeInOut",
				"Power3.easeOut" => "Power3.easeOut",
				"Power4.easeIn" => "Power4.easeIn",
				"Power4.easeInOut" => "Power4.easeInOut",
				"Power4.easeOut" => "Power4.easeOut",
				"Back.easeIn" => "Back.easeIn",
				"Back.easeInOut" => "Back.easeInOut",
				"Back.easeOut" => "Back.easeOut",
				"Bounce.easeIn" => "Bounce.easeIn",
				"Bounce.easeInOut" => "Bounce.easeInOut",
				"Bounce.easeOut" => "Bounce.easeOut",
				"Circ.easeIn" => "Circ.easeIn",
				"Circ.easeInOut" => "Circ.easeInOut",
				"Circ.easeOut" => "Circ.easeOut",
				"Elastic.easeIn" => "Elastic.easeIn",
				"Elastic.easeInOut" => "Elastic.easeInOut",
				"Elastic.easeOut" => "Elastic.easeOut",
				"Expo.easeIn" => "Expo.easeIn",
				"Expo.easeInOut" => "Expo.easeInOut",
				"Expo.easeOut" => "Expo.easeOut",
				"Sine.easeIn" => "Sine.easeIn",
				"Sine.easeInOut" => "Sine.easeInOut",
				"Sine.easeOut" => "Sine.easeOut",
				"SlowMo.ease" => "SlowMo.ease",
		);
		return $this->CreateOptionHtmls($array,1,$id, $name);
  }
  
  public function toLayerEasingList($id = null, $name = null) {
			$array = array(
					"nothing" => "No Change",
					"Linear.easeNone" => "Linear.easeNone",
					"Power0.easeIn" => "Power0.easeIn  (linear)",
					"Power0.easeInOut" => "Power0.easeInOut  (linear)",
					"Power0.easeOut" => "Power0.easeOut  (linear)",
					"Power1.easeIn" => "Power1.easeIn",
					"Power1.easeInOut" => "Power1.easeInOut",
					"Power1.easeOut" => "Power1.easeOut",
					"Power2.easeIn" => "Power2.easeIn",
					"Power2.easeInOut" => "Power2.easeInOut",
					"Power2.easeOut" => "Power2.easeOut",
					"Power3.easeIn" => "Power3.easeIn",
					"Power3.easeInOut" => "Power3.easeInOut",
					"Power3.easeOut" => "Power3.easeOut",
					"Power4.easeIn" => "Power4.easeIn",
					"Power4.easeInOut" => "Power4.easeInOut",
					"Power4.easeOut" => "Power4.easeOut",				
					"Quad.easeIn" => "Quad.easeIn  (same as Power1.easeIn)",				
					"Quad.easeInOut" => "Quad.easeInOut  (same as Power1.easeInOut)",				
					"Quad.easeOut" => "Quad.easeOut  (same as Power1.easeOut)",			
					"Cubic.easeIn" => "Cubic.easeIn  (same as Power2.easeIn)",		
					"Cubic.easeInOut" => "Cubic.easeInOut  (same as Power2.easeInOut)",			
					"Cubic.easeOut" => "Cubic.easeOut  (same as Power2.easeOut)",			
					"Quart.easeIn" => "Quart.easeIn  (same as Power3.easeIn)",				
					"Quart.easeInOut" => "Quart.easeInOut  (same as Power3.easeInOut)",				
					"Quart.easeOut" => "Quart.easeOut  (same as Power3.easeOut)",				
					"Quint.easeIn" => "Quint.easeIn  (same as Power4.easeIn)",			
					"Quint.easeInOut" => "Quint.easeInOut  (same as Power4.easeInOut)",			
					"Quint.easeOut" => "Quint.easeOut  (same as Power4.easeOut)",				
					"Strong.easeIn" => "Strong.easeIn  (same as Power4.easeIn)",				
					"Strong.easeInOut" => "Strong.easeInOut  (same as Power4.easeInOut)",				
					"Strong.easeOut" => "Strong.easeOut  (same as Power4.easeOut)",				
					"Back.easeIn" => "Back.easeIn",				
					"Back.easeInOut" => "Back.easeInOut",				
					"Back.easeOut" => "Back.easeOut",			
					"Bounce.easeIn" => "Bounce.easeIn",				
					"Bounce.easeInOut" => "Bounce.easeInOut",			
					"Bounce.easeOut" => "Bounce.easeOut",				
					"Circ.easeIn" => "Circ.easeIn",				
					"Circ.easeInOut" => "Circ.easeInOut",				
					"Circ.easeOut" => "Circ.easeOut",				
					"Elastic.easeIn" => "Elastic.easeIn",				
					"Elastic.easeInOut" => "Elastic.easeInOut",				
					"Elastic.easeOut" => "Elastic.easeOut",				
					"Expo.easeIn" => "Expo.easeIn",				
					"Expo.easeInOut" => "Expo.easeInOut",				
					"Expo.easeOut" => "Expo.easeOut",				
					"Sine.easeIn" => "Sine.easeIn",				
					"Sine.easeInOut" => "Sine.easeInOut",				
					"Sine.easeOut" => "Sine.easeOut",				
					"SlowMo.ease" => "SlowMo.ease",				
					"easeOutBack" => "easeOutBack",				
					"easeInQuad" => "easeInQuad",			
					"easeOutQuad" => "easeOutQuad",				
					"easeInOutQuad" => "easeInOutQuad",			
					"easeInCubic" => "easeInCubic",				
					"easeOutCubic" => "easeOutCubic",				
					"easeInOutCubic" => "easeInOutCubic",				
					"easeInQuart" => "easeInQuart",
					"easeOutQuart" => "easeOutQuart",
					"easeInOutQuart" => "easeInOutQuart",
					"easeInQuint" => "easeInQuint",
					"easeOutQuint" => "easeOutQuint",
					"easeInOutQuint" => "easeInOutQuint",
					"easeInSine" => "easeInSine",
					"easeOutSine" => "easeOutSine",
					"easeInOutSine" => "easeInOutSine",
					"easeInExpo" => "easeInExpo",
					"easeOutExpo" => "easeOutExpo",
					"easeInOutExpo" => "easeInOutExpo",
					"easeInCirc" => "easeInCirc",
					"easeOutCirc" => "easeOutCirc",
					"easeInOutCirc" => "easeInOutCirc",
					"easeInElastic" => "easeInElastic",
					"easeOutElastic" => "easeOutElastic",
					"easeInOutElastic" => "easeInOutElastic",
					"easeInBack" => "easeInBack",
					"easeInOutBack" => "easeInOutBack",
					"easeInBounce" => "easeInBounce",
					"easeOutBounce" => "easeOutBounce",
					"easeInOutBounce" => "easeInOutBounce",
		);
		return $this->CreateOptionHtmls($array,1,$id, $name);
  }
  
  public function toCaptionEasingControllList($id = null, $name = null) {
		$array = array( 
			"Linear.easeNone" => "Linear.easeNone",
			"Power0.easeIn" => "Power0.easeIn  (linear)",
			"Power0.easeInOut" => "Power0.easeInOut  (linear)",
			"Power0.easeOut" => "Power0.easeOut  (linear)",
			"Power1.easeIn" => "Power1.easeIn",
			"Power1.easeInOut" => "Power1.easeInOut",
			"Power1.easeOut" => "Power1.easeOut",
			"Power2.easeIn" => "Power2.easeIn",
			"Power2.easeInOut" => "Power2.easeInOut",
			"Power2.easeOut" => "Power2.easeOut",
			"Power3.easeIn" => "Power3.easeIn",
			"Power3.easeInOut" => "Power3.easeInOut",
			"Power3.easeOut" => "Power3.easeOut",
			"Power4.easeIn" => "Power4.easeIn",
			"Power4.easeInOut" => "Power4.easeInOut",
			"Power4.easeOut" => "Power4.easeOut",
			"Back.easeIn" => "Back.easeIn",
			"Back.easeInOut" => "Back.easeInOut",
			"Back.easeOut" => "Back.easeOut",
			"Bounce.easeIn" => "Bounce.easeIn",
			"Bounce.easeInOut" => "Bounce.easeInOut",
			"Bounce.easeOut" => "Bounce.easeOut",
			"Circ.easeIn" => "Circ.easeIn",
			"Circ.easeInOut" => "Circ.easeInOut",
			"Circ.easeOut" => "Circ.easeOut",
			"Elastic.easeIn" => "Elastic.easeIn",
			"Elastic.easeInOut" => "Elastic.easeInOut",
			"Elastic.easeOut" => "Elastic.easeOut",
			"Expo.easeIn" => "Expo.easeIn",
			"Expo.easeInOut" => "Expo.easeInOut",
			"Expo.easeOut" => "Expo.easeOut",
			"Sine.easeIn" => "Sine.easeIn",
			"Sine.easeInOut" => "Sine.easeInOut",
			"Sine.easeOut" => "Sine.easeOut",
			"SlowMo.ease" => "SlowMo.ease",
		);
		return $this->CreateOptionHtmls($array,1,$id, $name);
  }
  public function toEndAnimation($id = null, $name = null,$all=true) {
		$array = array(
		"auto" => "Choose Automatic",
		"fadeout" => "Fade Out",
		"stt" => "Short to Top",
		"stb" => "Short to Bottom",
		"stl" => "Short to Left",
		"str" => "Short to Right",
		"ltt" => "Long to Top",
		"ltb" => "Long to Bottom",
		"ltl" => "Long to Left",
		"ltr" => "Long to Right",
		"skewtoright" => "Skew To Right",
		"skewtoleft" => "Skew To Left",
		"skewtorightshort" => "Skew To Right Short",
		"skewtoleftshort" => "Skew To Left Short",
		"randomrotateout" => "Random Rotate Out",);
		 if($all == true) {
			$newAnimArray = Mage::getModel('revslider/animation')->getCustomAnimations('customout');
			$array = array_merge($array, $newAnimArray);
		 }
			
		return $this->CreateOptionHtmls($array,1,$id, $name);
  }
    public function toLayerAnimationList($id = null, $name = null,$all= true) {
		$array = array(
		"tp-fade" => "Fade",
		"sft" => "Short from Top",
		"sfb" => "Short from Bottom",
		"sfr" => "Short from Right",
		"sfl" => "Short from Left",
		"lft" => "Long from Top",
		"lfb" => "Long from Bottom",
		"lfr" => "Long from Right",
		"lfl" => "Long from Left",
		"skewfromright" => "Skew From Long Right",
		"skewfromleft" => "Skew From Long Left",
		"skewfromleftshort" => "Skew From Short Right",
		"skewfromrightshort" => "Skew From Short Left",
		"randomrotate" => "Random Rotate",);
		 if($all == true) {
			$newAnimArray = Mage::getModel('revslider/animation')->getCustomAnimations('customin');
			$array = array_merge($array, $newAnimArray);
		 }
			
		return $this->CreateOptionHtmls($array,1,$id, $name);
  }
  
   public function toBackgroundFit($id = null, $name = null,$value= true) {
		$array = array(
		"cover" => "Cover",
		"contain" => "Contain",
		"percentage" => "(%, %)",
		"normal" => "normal",
		
		);
			
		return $this->CreateOptionHtmls($array,$value,$id, $name);
  }
  
   public function toBackgroundRepeat($id = null, $name = null,$value= true) {
		$array = array(
		"no-repeat" => "no-repeat",
		"repeat" => "repeat",
		"repeat-x" => "repeat-x",
		"repeat-y" => "repeat-y",
		
		);
			
		return $this->CreateOptionHtmls($array,$value,$id, $name);
  }
  
  
   public function getSlideTrasition($value = 'random') {

        $transition = array(
            "random" => "Random",
            "fade" => "Fade",
            "slidehorizontal" => "Slide Horizontal",
            "slidevertical" => "Slide Vertical",
            "boxslide" => "Box Slide",
            "boxfade" => "Box Fade",
            "slotzoom-horizontal" => "SlotZoom Horizontal",
            "slotslide-horizontal" => "SlotSlide Horizontal",
            "slotfade-horizontal" => "SlotFade Horizontal",
            "slotzoom-vertical" => "SlotZoom Vertical",
            "slotslide-vertical" => "SlotSlide Vertical",
            "slotfade-vertical" => "SlotFade Vertical",
            "curtain-1" => "Curtain 1",
            "curtain-2" => "Curtain 2",
            "curtain-3" => "Curtain 3",
            "slideleft" => "Slide Left",
            "slideright" => "Slide Right",
            "slideup" => "Slide Up",
            "slidedown" => "Slide Down",
            "papercut" => "Premium - Paper Cut",
            "3dcurtain-horizontal" => "Premium - 3D Curtain Horizontal",
            "3dcurtain-vertical" => "Premium - 3D Curtain Vertical",
            "flyin" => "Premium - Fly In",
            "turnoff" => "Premium - Turn Off",
            "cubic" => "Premium - Cubic",
        );
		$tranArr = array();
		foreach ($transition as $key=> $val) {
			$tranArr[] = array('value'=>$key,'label'=>$val);
		}
		return $tranArr;
	}

	public function getButtonClasses(){
		
		$arrButtons = array(
			"red"=>"Red Button",
			"green"=>"Green Button",
			"blue"=>"Blue Button",
			"orange"=>"Orange Button",
			"darkgrey"=>"Darkgrey Button",
			"lightgrey"=>"Lightgrey Button",
		);
		
		return($arrButtons);
	}
		
   public function toListButtons() {
		$html .='';
		$html .='<ul class="list-buttons">';
		$arrButtonClasses = $this->getButtonClasses();
		foreach($arrButtonClasses as $class=>$text): 
				$html .='<li>';
				$html .='<a href="javascript:UniteLayersRev.insertButton("'.$class.'","'.$text.'")" class="tp-button '. $class.' small">'.$text.'</a>';
				$html .='</li>';
		endforeach;
		$html .='</ul>'; 
		return $html; 
   }
   public function getCaptionData() {
		$collection = Mage::getModel('revslider/caption')->getCollection(); 
		$arrayCaptions = array();
		foreach($collection as $caption) {
			$arrayCaptions[] = $caption->getData(); 
		}
		$contentCss = $this->parseDbArrayToCss($arrayCaptions);
		if($contentCss) return $contentCss; 
	
   }
   
     public function getAllCaptionData() {
		$collection = Mage::getModel('revslider/caption')->getCollection(); 
		$arrayCaptions = array();
		foreach($collection as $caption) {
			$arrayCaptions[] = $caption->getData(); 
		}
		$contentCSS = $this->parseDbArrayToArray($arrayCaptions);
		return $contentCSS; 
	}		
   
   
   public static function parseDbArrayToCss($cssArray, $nl = "\n\r"){
		$css = '';
					if(!empty($cssArray))
		foreach($cssArray as $id => $attr){
			//$styles = json_decode(str_replace("'", '"', $attr['params']), true);
			$styles = json_decode($attr['params'], true);
							
							$css.= $attr['handle']." {".$nl;
							
			if(is_array($styles)){
									
				foreach($styles as $name => $style){                                                
					$css.= $name.':'.$style.";".$nl;
				}
			}
			$css.= "}".$nl.$nl;
			
			//add hover
			$setting = json_decode($attr['settings'], true);
			if(@$setting['hover'] == 'true'){
				//$hover = json_decode(str_replace("'", '"', $attr['hover']), true);
				$hover = json_decode($attr['hover'], true);
				if(is_array($hover)){
					$css.= $attr['handle'].":hover {".$nl;
					foreach($hover as $name => $style){
						$css.= $name.':'.$style.";".$nl;
					}
					$css.= "}".$nl.$nl;
				}
			}
		}
		return $css;
	}
	
	public  function parseArrayToCss($cssArray, $nl = "\n\r"){
		$css = '';
		if(!empty($cssArray)){
			
			//var_dump($cssArray);
			
			foreach($cssArray as $id => $attr){
					$styles = (array)$attr['params'];
					$css.= $attr['handle']." {".$nl;
					if(is_array($styles) && !empty($styles)){
							foreach($styles as $name => $style){
									if($name == 'background-color' && strpos($style, 'rgba') !== false){ //rgb && rgba
											$rgb = explode(',', str_replace('rgba', 'rgb', $style));
											unset($rgb[count($rgb)-1]);
											$rgb = implode(',', $rgb).')';
											$css.= $name.':'.$rgb.";".$nl;
									}
									//$css.= $name.':'.$style.";".$nl;
									$css.= $name.':'.str_replace('"', '\'', $style).";".$nl;
							}
					}
					$css.= "}".$nl.$nl;
					//add hover
					$setting = (array)$attr['settings'];
					if(@$setting['hover'] == 'true'){
							$hover = (array)$attr['hover'];
							if(is_array($hover)){
									$css.= $attr['handle'].":hover {".$nl;
									foreach($hover as $name => $style){
											if($name == 'background-color' && strpos($style, 'rgba') !== false){ //rgb && rgba
													$rgb = explode(',', str_replace('rgba', 'rgb', $style));
													unset($rgb[count($rgb)-1]);
													$rgb = implode(',', $rgb).')';
													$css.= $name.':'.$rgb.";".$nl;
											}
											//$css.= $name.':'.$style.";".$nl;
											$css.= $name.':'.str_replace('"', '\'', $style).";".$nl;
									}
									$css.= "}".$nl.$nl;
							}
					}
			}
		}

			return $css;
		}
		
	public  function parseDbArrayToArray($cssArray = array(), $handle = false){
	
		if(!is_array($cssArray) || empty($cssArray)) return false;
		
		foreach($cssArray as $key => $css){
			if($handle != false){
				if($cssArray[$key]['handle'] == $handle){
					//$cssArray[$key]['params'] = json_decode(str_replace("'", '"', $css['params']));
					$cssArray[$key]['params'] = json_decode($css['params']);
											
					$cssArray[$key]['hover'] = json_decode($css['hover']);
					
											$cssArray[$key]['settings'] = json_decode(str_replace("'", '"', $css['settings']));
					return $cssArray[$key];
				}else{
					unset($cssArray[$key]);
				}
			}else{
				//$cssArray[$key]['params'] = json_decode(str_replace("'", '"', $css['params']));
				$cssArray[$key]['params'] = json_decode($css['params']);
									
				//$cssArray[$key]['hover'] = json_decode(str_replace("'", '"', $css['hover']));
				$cssArray[$key]['hover'] = json_decode($css['hover']);
				
				$cssArray[$key]['settings'] = json_decode(str_replace("'", '"', $css['settings']));
			}
		}
		
		return $cssArray;
	}
	
	public function getArrClasses($startText = "",$endText="",$content ){
				//trim from top
			if(!empty($startText)){
				$posStart = strpos($content, $startText);
				if($posStart !== false)
					$content = substr($content, $posStart,strlen($content)-$posStart);
			}
			//trim from bottom
			if(!empty($endText)){
				$posEnd = strpos($content, $endText);
				if($posEnd !== false)
					$content = substr($content,0,$posEnd);
			}
			//get styles
			$lines = explode("\n",$content);
			$arrClasses = array();
			foreach($lines as $key=>$line){
				$line = trim($line);
				if(strpos($line, "{") === false)
					continue;
				//skip unnessasary links
				if(strpos($line, ".caption a") !== false)
					continue;
				if(strpos($line, ".tp-caption a") !== false)
					continue;
				//get style out of the line
				$class = str_replace("{", "", $line);
				$class = trim($class);
				//skip captions like this: .tp-caption.imageclass img
				if(strpos($class," ") !== false)
					continue;
				//skip captions like this: .tp-caption.imageclass:hover, :before, :after
				if(strpos($class,":") !== false)
					continue;
				$class = str_replace(".caption.", ".", $class);
				$class = str_replace(".tp-caption.", ".", $class);
				$class = str_replace(".", "", $class);
				$class = trim($class);
				$arrWords = explode(" ", $class);
				$class = $arrWords[count($arrWords)-1];
				$class = trim($class);
				$arrClasses[] = $class;	
			}
			sort($arrClasses);
			return($arrClasses);
		}
		
		public static function writeFile($str,$filepath){
			if(is_writable(dirname($filepath)) == false){
				@chmod(dirname($filepath),0755);		//try to change the permissions
			}
			if(!is_writable(dirname($filepath))) 
			 {
				echo ("<div style='display:none'>Can't write file \"".$filepath."\", please change the permissions!</div>");
			 }
			$fp = fopen($filepath,"w+");
			fwrite($fp,$str);
			fclose($fp);

		}
		public function getAllClassFromCaption() {
			$cssContent = $this->getCaptionData();
			$arrCaptions = $this->getArrCaptionClasses($cssContent);
			 return $arrCaptions;
		}
		
		public function getArrCaptionClasses($contentCss){
			$arrCaptionClasses = $this->getArrClasses("","",$contentCss);
			return $arrCaptionClasses;
		}
		
		public function ClearQuote($content){                    
			return str_replace('"', '\'', $content);                    
		}
		
		public function getAllRevSlides($task=""){
			$collection = Mage::getModel('revslider/revslider')->getCollection();
			if($task=='col')  return $collection;
				$arraySlides = array();
			if($task=='array') {
				foreach($collection as $slide) {
					$arraySlides[] = $slide->getData();
				}
				if($arraySlides) return $arraySlides; 
			}
		}
		
		public function getAllSlides($rev_id=NULL){
			$slides = $this->getAllRevSlides('col');
			$slideModel = Mage::getModel('revslider/slide')->getCollection();
			foreach($slides as $slide) {
				if($slide->getRevsliderId() == $rev_id) {
					$slideModel->addAttributeToFilter('revslider_id',$rev_id);
					return $slideModel;
					break;
				}
			}
			
		}
		
		
		public  function getCaptionsContentArray($handle = false){
			$model = Mage::getModel('revslider/caption');
			$result = $model->getCollection();
			$captionArray = array();
			foreach($result as $caption) {
				$captionArray[] = $caption->getData();
			}
		
			$contentCSS = $this->parseDbArrayToArray($captionArray, $handle);
		
			return($contentCSS);
		}
		
		public  function updateToCssFile($full = false){
				$classes = array();
				$model = Mage::getModel('revslider/caption');
				$result = $model->getCollection();
				$captionArray = array();
				foreach($result as $caption) {
					$classes[$caption['handle']] = 1;
				}
				//Mage::log($classes, null,'layer.log');
				if(!empty($classes)){
				 	$captions = array();
					foreach($classes as $class => $val){
						$captionCheck = $this->getCaptionsContentArray($class);
						if(!empty($captionCheck)) $captions[] = $captionCheck;
					}

					$styles = $this->parseArrayToCss($captions, "\n");
					Mage::log(555, null, 'layer1.log');
					Mage::log($styles, null,'layer1.log');
					//write styles into dynamic css
					// $cssPathAdmin =  Mage::getBaseDir().'\skin\frontend\default\default\magentothem\revslider\css/captions.css'; 
					// $cssPath =  Mage::getBaseDir().'\skin\adminhtml\default\default\revslider/captions.css'; 
					// $this->writeFile($styles, $cssPathAdmin);
					// $this->writeFile($styles, $cssPath);
					//@copy($this->fileCssAdmin, $this->fileCssFront);
				}
					
			}
			
			public function getAllCaptionInSlide() {
				$slideCols = Mage::getModel('revslider/slide')->getCollection();
				$captionClass = array();
				foreach($slideCols as $slide) {
					$layers = $this->RevJsonDecode($slide->getLayers());
					foreach ($layers as $layer) {
						if($layer['style']) {
							$captionClass[] = '.tp-caption.'.$layer['style'] ;
						}
					}
				} 
				return $captionClass; 
			}						
			public  function isLocal ()			{			  
				return !checkdnsrr($_SERVER['SERVER_NAME'], 'NS');			
			}
			
			public  function initCss($full = false){
				$captionClass   = $this->getAllCaptionInSlide(); 
				$classes = array();
				$model = Mage::getModel('revslider/caption');
				$result = $model->getCollection();
				$captionArray = array();
				foreach($result as $caption) {
				  if(in_array($caption['handle'],$captionClass)) { 
					$classes[$caption['handle']] = 1;
				  }
				}
				
				if(!empty($classes)){
				 	$captions = array();
					foreach($classes as $class => $val){
						$captionCheck = $this->getCaptionsContentArray($class);
						if(!empty($captionCheck)) $captions[] = $captionCheck;
					}

					$styles = $this->parseArrayToCss($captions, "\n");
					$helper = Mage::helper('revslider/data');
					$skin = Mage::getDesign()->getSkinBaseDir().'/magentothem/revslider/css/captions.css';		
					$this->writeFile($styles, $skin);
				}
			}	
			
	public function getArrFontFamilys(){
			//Web Safe Fonts
			$fonts = array(
				//Serif Fonts
				'Georgia, serif',
				'\'Palatino Linotype\', \'Book Antiqua\', Palatino, serif',
				'"Times New Roman", Times, serif',

				//Sans-Serif Fonts
				'Arial, Helvetica, sans-serif',
				'\'Arial Black\', Gadget, sans-serif',

				'\'Comic Sans MS\', cursive, sans-serif',

				'Impact, Charcoal, sans-serif',

				'\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif',

				'Tahoma, Geneva, sans-serif',

				'\'Trebuchet MS\', Helvetica, sans-serif',

				'Verdana, Geneva, sans-serif',

				//Monospace Fonts

				'\'Courier New\', Courier, monospace',

				'\'Lucida Console\', Monaco, monospace'

			);
			$fontJson = $this->RevJsonEncode($fonts);
			return $fontJson;
		}		
			
	    public  function RevJsonEncode($arr){
			$json = "";
			if(!empty($arr)){                                
				$json = json_encode($arr);
				$json = addslashes($json);                              
			}
                        
			$json = "'".$json."'";
			
			return($json);
		}
	
	
		/**
		 * 
		 * decode json from 
		 */
		public  function RevJsonDecode($data){
			$data = json_decode($data,true);                        
			return($data);
		}
		
	public  function strToBool($str){
			if(is_bool($str))
				return($str);
				
			if(empty($str))
				return(false);
				
			if(is_numeric($str))
				return($str != 0);
				
			$str = strtolower($str);
			if($str == "true")
				return(true);
				
			return(false);
		}
		
		
	function csv_to_array($filename='', $delimiter=',')
	{
		if(!file_exists($filename) || !is_readable($filename))
			return FALSE;

		$header = NULL;
		$data = array();
		if (($handle = fopen($filename, 'r')) !== FALSE)
		{
			while (($row = fgetcsv($handle, 10000, $delimiter)) !== FALSE)
			{
				$data[] = $row;
			}

			fclose($handle);
		}
		return $data;
	}
	
	public function importRevsliderData() {
		$this->importRevslider(); 
		$this->importRevsliderSlide(); 
		//$this->importRevsliderCaption(); 
		$this->importRevsliderAnimation(); 
	}
	
	public function jsonToData($jsondata) {
		$json = json_decode($jsondata);
		// Will use $csv to build our CSV content
		$csv = array();
		// Need to define the special column
		$modelList = 'modelList';
		// Find necessary additional column number for modelList
		$maxSubData = 0;
		foreach ($json as $id => $data)
		{
			$maxSubData = max(array(count($data->modelList), $maxSubData ));
		}
		// Headers for CSV file
		// Headers Start
		$headers = array();
		foreach ($json[0] as $key => $value)
		{
			if ($key == $modelList)
			{
				for ($i = 1; $i <= $maxSubData; $i++)
				{
					$headers[] = $modelList . $i;
				}
			}
			else
			{
				$headers[] = $key;
			}
		}
		$csv[] = '"' . implode('","', $headers) . '"';
		// Headers End
		// Now the rows
		// Rows Start
		foreach ($json as $data)
		{
			$fieldValues = array();
			foreach ($data as $key => $value )
			{
				if ($key == $modelList)
				{
					$j = 0;
					foreach ($value as $subValue)
					{
						$subData = array();
						foreach ($subValue as $subCols)
						{
							$subData[] = $subCols;
						}
						$j++;
						$fieldValues[] = htmlspecialchars(implode(':', $subData ));
					}
					for ($i = $j + 1; $i <= $maxSubData; $i++)
					{
						$fieldValues[] = '';
					}
				}
				else
				{
					$fieldValues[] = htmlspecialchars($value);
				}
			}
			$csv[] = '"' . implode('","', $fieldValues) . '"';
		}
		// Rows End
		$finalCSV = implode("\n", $csv);
		print $finalCSV;
	
	}
	
		function array_implode_with_keys($array){
			$return = '';
			if (count($array) > 0){
			foreach ($array as $key=>$value){
			$return .= $key . '||||' . $value . '----';
			}
			$return = substr($return,0,strlen($return) - 4);
			}
			return $return;
		}

		function array_explode_with_keys($string){
				$return = array();
				$pieces = explode('----',$string);
				foreach($pieces as $piece){
					$keyval = explode('||||',$piece);
					if (count($keyval) > 1){
						$return[$keyval[0]] = $keyval[1];
					} else {
						$return[$keyval[0]] = '';
					}
				}
				return $return;
		}
		
		 function strToHex($string){
				$hex = '';
				for ($i=0; $i<strlen($string); $i++){
					$ord = ord($string[$i]);
					$hexCode = dechex($ord);
					$hex .= substr('0'.$hexCode, -2);
				}
				return strToUpper($hex);
			}
			function hexToStr($hex){
				$string='';
				for ($i=0; $i < strlen($hex)-1; $i+=2){
					$string .= chr(hexdec($hex[$i].$hex[$i+1]));
				}
				return $string;
			}

		
		public function importRevsliderSlide() {
		  $helper = Mage::helper('revslider/data');
		  $fp = Mage::getBaseDir().'\app\code\local\Magentothem\Revslider\sql/file_slide.csv';
		   $revsliders1 = $helper->csv_to_array($fp,',');
			$data = array();
			 foreach($revsliders1 as $revslider) {
				$fields = $revsliders1[0];
				foreach($revslider as $key => $items) {
					foreach($fields as $m => $n) {
						if($m == $key) {
							if($items!= 'revslider_id' && $items !='title' && $items !='slide_id'&& $items !='layers' && $items !='params'&& $items !='status'&& $items !='s_order'){
								$data[$n] = $items;
							}
						}
					}
				}
				if($data) {
				$layers = $data['layers'] ; 
				$layerArray = explode('__',$layers); 
				$layerArrays = array();
				foreach($layerArray as $layer) {
					if($layer) {
						$text = null; 
						$layerItem = $this->array_explode_with_keys($layer);
						$text = $this->strToHex($layerItem['text']);
						$layerItem['text'] = $text; 
						$layerArrays[] = $layerItem; 
					}
				}
				$params = $data['params'] ; 
				$paramArrays = $this->array_explode_with_keys($params);


				$layerJson = json_encode($layerArrays,true); 
				$paramJson = json_encode($paramArrays, true); 
				$write = Mage::getSingleton("core/resource")->getConnection("core_write");
				$query = "INSERT INTO revslider_slide(`slide_id`,`revslider_id`,`params`,`title`,`layers`,`status`,`s_order`) VALUES ({$data[slide_id]},{$data[revslider_id]},'{$paramJson}','{$data[title]}','{$layerJson}',{$data[status]},{$data[s_order]}) ";
					$write->query($query);
				}
			 }
	}
	
	public function importRevslider() {
		$fp = Mage::getBaseDir().'\app\code\local\Magentothem\Revslider\sql/file.csv';
		$revsliders = $this->csv_to_array($fp,',');
		$data = array();

		 foreach($revsliders as $key => $revslider) {
				$fields = $revsliders[0];
			
				foreach($revslider as $key => $items) {
					
					foreach($fields as $m => $n) {
						
						if($m == $key) {
							if($items!= 'revslider_id' && $items !='title' && $items !='alias' && $items !='params'){ 
								$data[$n] = $items;
							}
						}
					}		
			}
		
			 if($data && $data['revslider_id'] != '||||' && is_numeric($data['revslider_id'])) {
			 	$write = Mage::getSingleton("core/resource")->getConnection("core_write");
				$params = $data['params'] ; 
				$paramArrays = $this->array_explode_with_keys($params);
				$paramJson = json_encode($paramArrays, true); 
				 $query = "INSERT INTO revslider(`revslider_id`,`params`,`title`,`alias`) VALUES ({$data[revslider_id]},'{$paramJson}','{$data[title]}','{$data[alias]}') ";
				 $write->query($query);
			 }
		 }
	
	
	}
	
	public function importRevsliderCaption() {
	
		$fp = Mage::getBaseDir().'\app\code\local\Magentothem\Revslider\sql/file_caption.csv';
		$revsliders = $this->csv_to_array($fp,',');
		$data = array();
		 foreach($revsliders as $key => $revslider) {
				$fields = $revsliders[0];
			
				foreach($revslider as $key => $items) {
					
					foreach($fields as $m => $n) {
						
						if($m == $key) {
							if($items!= 'caption_id' && $items !='handle' && $items !='settings' && $items !='hover' && $items !='params'){
								$data[$n] = $items;
							}
						}
					}		
			}
		 if($data && $data['caption_id'] != '||||'&&is_numeric($data['caption_id'])) {
			$params = $data['params'] ; 
			$paramArrays = $this->array_explode_with_keys($params);
			$paramJson = json_encode($paramArrays, JSON_HEX_APOS); 

			$settings = $data['settings'] ; 
			$settingArrays = $this->array_explode_with_keys($settings);
			$settingJson = json_encode($settingArrays, true); 
	
			$hover = $data['hover'] ; 
			$hoverArrays = $this->array_explode_with_keys($hover);
			$hoverJson = json_encode($hoverArrays); 
			 $write = Mage::getSingleton("core/resource")->getConnection("core_write");
			 $query = "INSERT INTO revslider_caption(`caption_id`,`handle`,`settings`,`hover`,`params`) VALUES ({$data[caption_id]},'{$data[handle]}','{$settingJson}','{$hoverJson}','{$paramJson}') ";
			 $write->query($query);
		 }
		}
	
	
	}
	
	public function importRevsliderAnimation() {
		$fp = Mage::getBaseDir().'\app\code\local\Magentothem\Revslider\sql/file_animation.csv';
		$revsliders = $this->csv_to_array($fp,',');
		$data = array();
		 foreach($revsliders as $key => $revslider) {
				$fields = $revsliders[0];
				foreach($revslider as $key => $items) {
					foreach($fields as $m => $n) {
						if($m == $key) {
							if($items!= 'animation_id' && $items !='handle' && $items !='params'){
								$data[$n] = $items;
							}
						}
					}		
			}
		 if($data && $data['animation_id'] != '||||'&&is_numeric($data['animation_id'])) {
			$params = $data['params'] ; 
			$paramArrays = $this->array_explode_with_keys($params);
			$paramJson = json_encode($paramArrays, true); 
			 $write = Mage::getSingleton("core/resource")->getConnection("core_write");
			 $query = "INSERT INTO revslider_animation(`animation_id`,`handle`,`params`) VALUES ({$data[animation_id]},'{$data[handle]}','{$paramJson}') ";
			 $write->query($query);
		 }
		}
	}

   
}