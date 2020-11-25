<?php
class Tech9logy_Events_RegisterController extends Mage_Core_Controller_Front_Action{
    public function indexAction() {
      
	//$collection = Mage::getModel('Sintax/sintax')getCollection();   
	 
	  //Mage::log($collection);
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Register Now"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home"),
                "title" => $this->__("Home"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("titlename", array(
                "label" => $this->__("Register Now"),
                "title" => $this->__("Register Now")
		   ));

      $this->renderLayout(); 
	  
    }
	
	
	 public function postAction()
    {
	
	$post = $this->getRequest()->getPost();	
	
	//echo "<pre>"; print_r($post); die;
	if($post['g-recaptcha-response']==''){
		Mage::getSingleton('checkout/session')->addError('Please verify the captcha!!');
		$this->_redirect(''); 
	}else{
		$data = array('fname'=> $post['fname'],'lname'=> $post['lname'],'phone'=> $post['telephone'],'email'=> $post['email'],'seminar'=> $post['selSeminar'],'howhear'=> $post['selHowHear']);
		
		$event_seminar_model = Mage::getModel('events/seminar')->setData($data);	
		$event_seminar_model->save(); 
		
		
		$updated_number = substr($post['telephone'], 0, 3) .'.'.substr($post['telephone'], 3, 3) .'.'.substr($post['telephone'], 6);
			
		$data_to_append = $post['fname']."|".$post['lname']."|".$updated_number."|".$post['email']."|".$post['selSeminar']."|".$post['selHowHear']."|";
		$xml = new DOMDocument();
		$xml->save("seminar/".$post['fname'].$post['lname'].".xml");
		$myfile = fopen("seminar/".$post['fname'].$post['lname'].".xml", "w") or die("Unable to open file!");
		$txt = $data_to_append;
		fwrite($myfile, $txt);

		fclose($myfile);

		$this->_redirect('thankyou/');
		}
	}
	
	public function staticpostAction()
    {
		$post = $this->getRequest()->getPost();	
	
		//echo "<pre>"; print_r($post); die;
		if($post['g-recaptcha-response']==''){
			Mage::getSingleton('checkout/session')->addError('Please verify the captcha!!');
			$this->_redirect('event'); 
		}else{
			$data = array('fname'=> $post['firstname'],'lname'=> $post['lastname'],'phone'=> $post['number'],'email'=> $post['email'],'seminar'=> $post['selSeminar'],'howhear'=> $post['fromwherehear']);
			
			$event_seminar_model = Mage::getModel('events/seminarreg');	
			if($event_seminar_model){
			$event_seminar_model =  $event_seminar_model->setData($data);
			$event_seminar_model->save(); 
			}
			
			
			$updated_number = substr($post['number'], 0, 3) .'.'.substr($post['number'], 3, 3) .'.'.substr($post['number'], 6);
				
			$data_to_append = $post['firstname']."|".$post['lastname']."|".$updated_number."|".$post['email']."|".$post['selSeminar']."|".$post['fromwherehear']."|";
			$xml = new DOMDocument();
			$xml->save("yelmevent/".$post['firstname'].$post['lastname'].".xml");
			$myfile = fopen("yelmevent/".$post['firstname'].$post['lastname'].".xml", "w") or die("Unable to open file!");
			$txt = $data_to_append;
			fwrite($myfile, $txt);

			fclose($myfile);

			$this->_redirect('thankyou/');
			}
		
	}
	
}
