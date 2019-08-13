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
class Abserve_Onestepcheckout_Model_Sales_Order_Invoice_Total_Giftwrap extends Mage_Sales_Model_Order_Invoice_Total_Abstract {
	
	public function collect(Mage_Sales_Model_Order_Invoice $invoice) {
		$invoice->setOnestepcheckoutGiftwrapAmount(0);        

        $orderGiftwrapAmount = $invoice->getOrder()->getOnestepcheckoutGiftwrapAmount();		
        $baseOrderShippingAmount = $invoice->getOrder()->getOnestepcheckoutGiftwrapAmount();
        if ($orderGiftwrapAmount) {
            $invoice->setGiftwrapAmount($orderGiftwrapAmount);           
            $invoice->setGrandTotal($invoice->getGrandTotal()+$orderGiftwrapAmount);
			$invoice->setBaseGrandTotal($invoice->getBaseGrandTotal()+$orderGiftwrapAmount);			
        }
        return $this;
	}
}