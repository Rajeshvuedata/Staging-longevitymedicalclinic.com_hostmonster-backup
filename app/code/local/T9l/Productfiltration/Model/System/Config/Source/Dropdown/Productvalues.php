<?php

class T9l_productfiltration_Model_System_Config_Source_Dropdown_Productvalues
{
    public function toOptionArray()
    {
        
	$data = array(
            array(
                'value' => 'mostviewed',
                'label' => 'Most Viewed Product',
            ),
            array(
                'value' => 'bestreviewed',
                'label' => 'Best Reviewed Product',
            ),
	    array(
                'value' => 'featured',
                'label' => 'Featured Product',
            ),
	    array(
                'value' => 'bestseller',
                'label' => 'Best Seller Product',
            ),
	   array(
                'value' => 'latest',
                'label' => 'Latest Product',
            ),
        );
	
	return $data;
    }
}
?>

