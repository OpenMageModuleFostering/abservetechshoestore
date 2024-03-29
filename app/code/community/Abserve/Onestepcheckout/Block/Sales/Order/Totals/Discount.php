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
class Abserve_Onestepcheckout_Block_Sales_Order_Totals_Discount extends Mage_Sales_Block_Order_Totals
{
    public function initTotals()
    {
		// var_dump($this->discountAmount());
		if($this->discountAmount() != 0){
			$total = new Varien_Object();
			$total->setCode('onestepcheckoutdiscount');
			$total->setValue(-$this->discountAmount());
			$total->setBaseValue(0);
			$total->setLabel('Discount(Admin)');
			$parent = $this->getParentBlock();
			$parent->addTotal($total,'subtotal');
		}
	}
	
	public function discountAmount() {
		$order = $this->getParentBlock()->getOrder();
		// $order->setData('onestepcheckout_discount_amount',123)->save();
		// var_dump($order->getData());
		$discountAmount = $order->getOnestepcheckoutDiscountAmount();
		return $discountAmount;
	}
}