<?php
class Tech9logy_Contactform_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Contact Us"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("contact us", array(
                "label" => $this->__("Contact Us"),
                "title" => $this->__("Contact Us")
		   ));

      $this->renderLayout(); 
	  
    }
	
	
	  public function postAction()
    {
        //Fetch submited params
        $params = $this->getRequest()->getParams();
		
		 $mail = new Zend_Mail();
		
		$body="<div>";
		$body.="<label>Name: </label> <strong>".$params['fname']." ".$params['lname']."</strong><br>";
		$body.="<label>Phone: </label> <strong>".$params['telephone']."</strong><br>";
		$body.="<label>Email: </label> <strong>".$params['email']."</strong><br>";
		$body.="<label>Query: </label> <strong>".$params['comments']."</strong><br>";
		$body.="</div>"; 
        $mail->setBodyHtml($body);
        $mail->setFrom($params['fname']." ".$params['lname']);
        $mail->addTo('contactus@longevitymedicalclinic.com', 'Recipient');
        $mail->setSubject($params['fname']." ".$params['lname']." has an enquiry");
        try {
            $mail->send();
        }        
        catch(Exception $ex) {
            Mage::getSingleton('core/session')->addError('Unable to send email. There was some error while sending email. Please try again');
 
        }
 
        //Redirect back to index action of (this) inchoo-simplecontact controller
        $this->_redirect('thanks/');
	}	
	
	 public function agingseminarAction()
	 {
		 //Fetch submited params
        $params = $this->getRequest()->getParams();
		
		 $mail = new Zend_Mail();
		
		$body="<div>";
		$body.="<label>Name: </label> <strong>".$params['fname']." ".$params['lname']."</strong><br>";
		$body.="<label>Phone: </label> <strong>".$params['telephone']."</strong><br>";
		$body.="<label>Email: </label> <strong>".$params['email']."</strong><br>";
		$body.="<label>Address: </label> <strong>".$params['address']."</strong><br>";
		$body.="<label>Country: </label> <strong>".$params['country']."</strong><br>";
		$body.="<label>Zip Code: </label> <strong>".$params['zip_code']."</strong><br>";
		$body.="<label>City: </label> <strong>".$params['city']."</strong><br>";
		$body.="<label>State: </label> <strong>".$params['state']."</strong><br>";
		
		$body.="</div>"; 
        $mail->setBodyHtml($body);
        $mail->setFrom($params['fname']." ".$params['lname']);
        $mail->addTo('bia.remen@longevitymedicalclinic.com', 'Recipient');
		$mail->addCc('tim.burke@promedev.com');
		$mail->addBcc('ankit.bansal@tech9logy.com');
        $mail->setSubject($params['fname']." ".$params['lname']." has registered for Aging Seminar");
        try {
            $mail->send();
        }        
        catch(Exception $ex) {
            Mage::getSingleton('core/session')->addError('Unable to send email. There was some error while sending email. Please try again');
 
        }
 
        //Redirect back to index action of (this) inchoo-simplecontact controller
		
        $this->_redirect('thanks-for-registering');
	 }
}
