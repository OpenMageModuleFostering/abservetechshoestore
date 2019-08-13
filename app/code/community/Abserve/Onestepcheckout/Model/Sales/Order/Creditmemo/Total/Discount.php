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
class Abserve_Onestepcheckout_Model_Sales_Order_Creditmemo_Total_Discount extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract {
	
	public function collect(Mage_Sales_Model_Order_Invoice $creditmemo) {
		$creditmemo->setOnestepcheckoutDiscountAmount(0);        
        $orderOnestepcheckoutDiscount = $creditmemo->getOrder()->getOnestepcheckoutDiscountAmount();		
        if ($orderOnestepcheckoutDiscount) {
            $creditmemo->setOnestepcheckoutDiscountAmount($orderOnestepcheckoutDiscount);           
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal()-$orderOnestepcheckoutDiscount);
			$creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal()-$orderOnestepcheckoutDiscount);			
        }
        return $this;
	}
}