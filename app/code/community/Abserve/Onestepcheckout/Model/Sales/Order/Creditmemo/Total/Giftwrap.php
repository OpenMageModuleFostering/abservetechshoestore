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
class Abserve_Onestepcheckout_Model_Sales_Order_Creditmemo_Total_Giftwrap extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract {
	
	public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo) {
		$creditmemo->setOnestepcheckoutGiftwrapAmount(0);        

        $orderGiftwrapAmount = $creditmemo->getOrder()->getOnestepcheckoutGiftwrapAmount();		
        $baseOrderShippingAmount = $creditmemo->getOrder()->getOnestepcheckoutGiftwrapAmount();
        if ($orderGiftwrapAmount) {
            $creditmemo->setOnestepcheckoutGiftwrapAmount($orderGiftwrapAmount);           
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal()+$orderGiftwrapAmount);
			$creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal()+$orderGiftwrapAmount);			
        }
        return $this;
	}
}