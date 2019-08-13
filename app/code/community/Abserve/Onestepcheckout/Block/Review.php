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
class Abserve_Onestepcheckout_Block_Review extends Mage_Checkout_Block_Onepage_Abstract {
	public function __construct() {
		parent::_construct();
		$this->getQuote()->collectTotals()->save();
	}
	
	public function getItems() {
		return $this->getQuote()->getAllVisibleItems();
	}

	public function getTotals() {
		return $this->getQuote()->getTotals();
	}
}