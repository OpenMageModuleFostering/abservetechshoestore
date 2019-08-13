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
class Abserve_Onestepcheckout_Helper_Rewrite_Checkout_Url extends Mage_Checkout_Helper_Url {
	
	public function getCheckoutUrl()
	{
		if(Mage::helper('onestepcheckout')->enabledOnestepcheckout() && Mage::helper('core')->isModuleOutputEnabled('Abserve_Onestepcheckout') )
			return Mage::getUrl('onestepcheckout/index', array('_secure' => true));
		else
			return $this->_getUrl('checkout/onepage');
    }
  

}