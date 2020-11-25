<?php

class Magentothem_Revslider_Model_Animation extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('revslider/animation');
    }
	
	public function getFullCustomAnimations($task= '') {
		    $collection = $this->getCollection();
		    $customAnimations = array();
			
			if(!empty($collection)){
				foreach($collection as $key => $anim){
					$customAnimations[$key]['id'] = $anim->getAnimationId();
					$customAnimations[$key]['handle'] = $anim->getHandle();
					$customAnimations[$key]['params'] = json_decode(str_replace("'", '"', $anim->getParams()), true);
				}
			}
			if($task=='array') {
				return $customAnimations;
			}
			if($task =='obj') {
				$customObject = Mage::helper('revslider/data')->RevJsonEncode($customAnimations);
				return $customObject;
			}
			
	}
	
	public  function getCustomAnimations($pre = 'customin'){
			$collection = $this->getCollection();
		    $customAnimations = array();
			if(!empty($collection)){
				foreach($collection as $key => $anim){
					$customAnimations[$pre.'-'.$anim->getAnimationId()] = $anim->getHandle();
				}
			}
			return $customAnimations; 
		
	}
	
	 public function toEndAnimation($all=true) {
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
			
	return $array;
  }
    public function toLayerAnimationList($all= true) {
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
			
		return $array;
  }
  
  public function getCustomAnimationByHandle($handle ="") {
	
	$all_Anim = $this->getFullCustomAnimations('array'); 
	foreach($all_Anim as $anim) {
		if($anim['handle'] == $handle) {
			return $anim; 
			break; 
		}
	}
	
  }
  
  public  function parseCustomAnimationByArray($animArray){
		$retString = '';
		if(isset($animArray['movex']) && $animArray['movex'] !== '') $retString.= 'x:'.$animArray['movex'].';';
		if(isset($animArray['movey']) && $animArray['movey'] !== '') $retString.= 'y:'.$animArray['movey'].';';
		if(isset($animArray['movez']) && $animArray['movez'] !== '') $retString.= 'z:'.$animArray['movez'].';';
		
		if(isset($animArray['rotationx']) && $animArray['rotationx'] !== '') $retString.= 'rotationX:'.$animArray['rotationx'].';';
		if(isset($animArray['rotationy']) && $animArray['rotationy'] !== '') $retString.= 'rotationY:'.$animArray['rotationy'].';';
		if(isset($animArray['rotationz']) && $animArray['rotationz'] !== '') $retString.= 'rotationZ:'.$animArray['rotationz'].';';
		
		if(isset($animArray['scalex']) && $animArray['scalex'] !== ''){
			$retString.= 'scaleX:';
			$retString.= (intval($animArray['scalex']) == 0) ? 0 : $animArray['scalex'] / 100;
			$retString.= ';';
		}
		if(isset($animArray['scaley']) && $animArray['scaley'] !== ''){
			$retString.= 'scaleY:';
			$retString.= (intval($animArray['scaley']) == 0) ? 0 : $animArray['scaley'] / 100;
			$retString.= ';';
		}
		
		if(isset($animArray['skewx']) && $animArray['skewx'] !== '') $retString.= 'skewX:'.$animArray['skewx'].';';
		if(isset($animArray['skewy']) && $animArray['skewy'] !== '') $retString.= 'skewY:'.$animArray['skewy'].';';
		
		if(isset($animArray['captionopacity']) && $animArray['captionopacity'] !== ''){
			$retString.= 'opacity:';
			$retString.= (intval($animArray['captionopacity']) == 0) ? 0 : $animArray['captionopacity'] / 100;
			$retString.= ';';
		}
		
		if(isset($animArray['captionperspective']) && $animArray['captionperspective'] !== '') $retString.= 'transformPerspective:'.$animArray['captionperspective'].';';
		
		if(isset($animArray['originx']) && $animArray['originx'] !== '' && isset($animArray['originy']) && $animArray['originy'] !== ''){
			$retString.= "transformOrigin:".$animArray['originx'].'% '.$animArray['originy']."%;";
		}
		
		return $retString;
	}
	
}