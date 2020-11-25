<?php
$installer = $this;
$installer->startSetup();
	$installer->run("
		RENAME TABLE Magentothem_testimonial TO simple_testimonial;		
	");	
$installer->endSetup(); 

?>
