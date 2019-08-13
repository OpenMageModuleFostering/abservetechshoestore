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

class Abserve_Onestepcheckout_Model_Config extends Mage_Core_Model_Abstract 
{	
	public function _construct()	
	{
		parent::_construct();
		$this->_init('onestepcheckout/config');
	}	
}
?>
