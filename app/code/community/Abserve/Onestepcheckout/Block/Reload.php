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
class Abserve_Onestepcheckout_Block_Reload extends Mage_Checkout_Block_Onepage_Abstract {
  public function __construct() {
		$this->configData = $this->_getConfigData();
	}
	
	protected function _getConfigData() {
		return Mage::helper('onestepcheckout')->getConfigData();
	}
}