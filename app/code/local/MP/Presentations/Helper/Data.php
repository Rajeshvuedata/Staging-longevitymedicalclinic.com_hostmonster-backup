<?php

class MP_Presentations_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Create block by class
     *
     * @param  string $class
     * @return string
     */
    public function createBlock($class)
    {
        return Mage::app()->getLayout()->createBlock($class);
    }
    
    /**
     * Return block html by class
     *
     * @param  string $class
     * @return string
     */
    public function getBlockHtml($class)
    {
        $b = $this->createBlock($class);
        
        if (is_object($b))
        {
            return $b->toHtml();
        } else {
            return '<ul class="messages"><li class="error-msg"><ul><li><span>
				<b>ERROR:</b> Could not find block '.$class.'
			</span></li></ul></li></ul>
			<div class="clearfix clear clearFix"></div>';
        }
    }
}
