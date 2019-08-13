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
class Abserve_Onestepcheckout_Block_Sales_Order_Totals_Giftwrap extends Mage_Sales_Block_Order_Totals
{
	public function initTotals()
    {
		if($this->giftwrapAmount() > 0){
			$total = new Varien_Object();
			$total->setCode('giftwrap');
			$total->setValue($this->giftwrapAmount());
			$total->setBaseValue(0);
			$total->setLabel('Gift wrap');
			$parent = $this->getParentBlock();
			$parent->addTotal($total,'subtotal');
		}
	}
	
	public function giftwrapAmount() {
		$order = $this->getParentBlock()->getOrder();
		$giftwrapAmount = $order->getOnestepcheckoutGiftwrapAmount();
		return $giftwrapAmount;
	}
}