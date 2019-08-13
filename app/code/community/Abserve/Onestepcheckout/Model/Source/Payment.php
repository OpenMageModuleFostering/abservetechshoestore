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
class Abserve_Onestepcheckout_Model_Source_Payment {
	public function toOptionArray() {
		$options = array();
		$options[] = array(
			'label' => '',
			'value'	=> ''
		);
		$quote = Mage::helper("onestepcheckout")->getOnePage()->getQuote();
		$store = $quote ? $quote->getStoreId() : null;
		$methods = Mage::helper('payment')->getStoreMethods($store, $quote);
		foreach ($methods as $key => $method) {
			$options[] = array(
			  'label' => $method->getTitle(),
				'value' => $method->getCode()
			);
		}		       
		return $options;
	}
}
