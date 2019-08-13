<?php
/**
 * @category    Abserve
 * @package     Abserve_Module
 * @author      Abserve Developer
 * @license     http://abservetech.com/license-agreement/
 * @copyright   Copyright (c) 2015 abservetech (http://abservetech.com)
 */
?>
<?php

class Abserve_Magenotification_Model_Observer
{
	public function controllerActionPredispatch($observer)
	{
		try{
			Mage::getModel('magenotification/magenotification')->checkUpdate();
		}catch(Exception $e){
		
		}
	}
	
}