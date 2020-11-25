<?php
 
class T9l_Productfiltration_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
	
	$this->loadLayout();
	$this->renderLayout();
	
    }
 
    
    public function filterAction()
    {
	//http://localhost/magento/index.php/t9l-Productfiltration/index/filterWorld 

	/* $this->loadLayout();

    	$head = Mage::app()->getLayout()->getBlock('head');
   	 $head->addItem('skin_css', 'css/new_styles.css');

    	echo $head->toHtml(); 
*/
	if(Mage::getStoreConfig('tab1/settings/settingenable')){
	$post = $_POST['type'];
	echo '<div class="wrapper">',
			'<div class="books-panelbox">';
	if($post=="featured"){
	$f_obj = new T9l_Productfiltration_Block_Featured;
	$_productCollection = $f_obj->getFeaturedProduct();
	$heading = "Featured products";	
	}//end of featured if

	if($post=='mostviewed'){
	$mv_obj= new T9l_Productfiltration_Block_Mostviewed;
	$_productCollection =$mv_obj->getmostviewed();
	$heading = "Most Viewed Products";
	}//end of mostviewed if

	if($post=='bestseller'){
	$bs_obj = new T9l_Productfiltration_Block_Bestseller;
	$_productCollection =$bs_obj->getbestsellerproducts();
	$heading = "Top Products";
	}//end of bestseller products if 

	if($post=="latest"){
	$l_obj = new T9l_Productfiltration_Block_Latestproducts;
	$_productCollection = $l_obj->getlatestproducts();
	$heading = "Latest Products";
	} //end of latest if
	
	$this->loadLayout();    
	$block = $this->getLayout()->createBlock('Mage_Core_Block_Template',' ',array('template' => 't9l/productfiltration/productviewonload.phtml'))->setData('products', $_productCollection);
	echo '<h1 class="most-view-heading">'.$heading.'</h1>'.$block->toHtml(); 
	

	if($post=='bestreviewed'){
	$br_obj=new T9l_Productfiltration_Block_Bestreview();
	$product_id=$br_obj->getReviewedProductID();
	$pid_with_reviews=$br_obj->getTotalReviews($product_id);
	arsort($pid_with_reviews);
	//print_r($pid_with_reviews);
	$obj = Mage::getModel('catalog/product');
	echo '<h1 class="review-heading">'; echo "Reviewed products"; echo"</h1>";
	$product_count=Mage::getStoreConfig('tab1/general/generalproducts');
	$count=0;
	foreach($pid_with_reviews as $key=>$value){
	if($count<$product_count){
	$_product = $obj->load($key);
	echo '<div class="sub-bookpannel">',
		'<div class="book-row">',
			'<div class="book-column1">';
				
				$my_product = Mage::getModel('catalog/product')->load($key); 
				$my_product_url = $my_product->getProductUrl();
				
					echo '<a href="'.$my_product_url.'">','<img src="'.$br_obj->helper('catalog/image')->init($_product, 'small_image')->resize(135, 135).'" alt="'.$_product->getName().'"/>','</a>',
				'<div class="book-column2">',
				'<p class="writer-name">';
				echo $_product->getName();
 				echo '</p>',
				'</div>';
				if($_product->isSaleable()){ 
				echo '<div class="addcart-text">';
				echo '<button class="addcart-button" type="button" title="Add to Cart" onclick="setLocation("'.$br_obj->getAddToCartUrl($_product) .'")">','<a>'.$br_obj->__('Add to Cart').'</a>','</button>',
				'</div>';
				 }else{
				echo '<div class="addcart-text">','<p >','<span class="out-of-stock">'.$br_obj->__('Out of stock').'</span>',
				'</p>',
                   		     '</div>';
				 }
				echo '<div class="sale-image">';
				$price=$_product->getPrice(); echo "$".number_format($price, 2);
				echo '</div>',
				
			'</div>',
		'</div>	',
'</div>';
	}else{
	break;}
	$count=$count+1;
	}
	}//end of best review product if
	}
	else
	{
	$this->_redirect('');
	}
	
}//end of action


}//end of class
?>