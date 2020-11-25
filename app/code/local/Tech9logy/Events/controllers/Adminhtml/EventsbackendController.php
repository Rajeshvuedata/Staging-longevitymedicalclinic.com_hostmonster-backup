<?php
class Tech9logy_Events_Adminhtml_EventsbackendController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("Schedule Event"));
	   $this->renderLayout();
    }



 public function postAction()
    {
		
		 $post = $this->getRequest()->getPost(); 
echo "<pre>";print_r($post);
		 try {

	/* start*/

		$path = Mage::getBaseDir() . '/media/events_images/';
    	if (!file_exists($path)) {
        mkdir($path, 777, true);
   	 }
	
	if (!empty($_FILES["uploadedimage"]["name"])) {

	    $file_name=$_FILES["uploadedimage"]["name"];
		//echo "filenameeis=>".$file_name;
	    $temp_name=$_FILES["uploadedimage"]["tmp_name"];
		//echo "<br/>".$temp_name."<br/>";
	    $imgtype=$_FILES["uploadedimage"]["type"];

	    $ext= $this->__GetImageExtension($imgtype);

	    $imagename=$file_name;
		//echo "<br/>image nameis=>".$imagename."<br/>";
	    $target_path = $path.$imagename;

	     echo $target_path;

	 

	if(move_uploaded_file($temp_name, $target_path)) {
	echo "uploaded";
	}//end of move_uploaded_file if
	else{
	echo "Error While uploading image on the server";
	} //end of else
	} //end of file name if

/*end of image upload */
	if($post['myform']['iswebinar']!="" && $post['myform']['iswebinar']=="on"){
	$iswebinar = 1;	 
	}else{
		$iswebinar = "";	 
	}	 
	//	$data = array('title'=> $post['myform']['title'],'location'=> $post['myform']['locationname']);
	$curr_time = date("Y-m-d H:i:s");
	$data = array('iswebinar'=> $iswebinar,'title'=> $post['myform']['title'],'event_date'=> $post['myform']['date'],'event_time'=> $post['myform']['time'],'location'=> $post['myform']['locationname'],'street'=> $post['myform']['street'],'city'=> $post['myform']['city'],'state'=> $post['myform']['state'],'zipcode'=> $post['myform']['zipcode'],'event_start_date'=> $post['myform']['dis_start_date'],'event_end_date'=> $post['myform']['dis_end_date'],'event_start_time'=> $post['myform']['dis_start_time'],'event_end_time'=> $post['myform']['dis_end_time'],'description'=> $post['myform']['description'],'google_map'=> $post['myform']['google_map'],'image'=> $imagename);
	
	
	
		

				  /* $model->settitle($post['myform']['title'])
                    ->setcontent($post['myform']['time'])
                    ->save();*/
					// Mage::log($model);
					
					

            if (empty($post)) {
                Mage::throwException($this->__('Invalid form data.'));
            }
            
            /* here's your form processing */
         $model = Mage::getModel('events/event')->setData($data);
		 $model->save();  
            $message = $this->__("Your Event has been added");
            Mage::getSingleton('adminhtml/session')->addSuccess($message);
        } catch (Exception $e) 
		{
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*');
		
	}

	protected function __GetImageExtension($imagetype)
   	 {
       if(empty($imagetype)) return false;
       switch($imagetype)
       {
           case 'image/bmp': return '.bmp';
           case 'image/gif': return '.gif';
           case 'image/jpeg': return '.jpg';
           case 'image/png': return '.png';
           default: return false;
       }
     }
	
}
