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
class Abserve_Onestepcheckout_Model_Order_Pdf_Amount 
		extends Mage_Sales_Model_Order_Pdf_Total_Default
{
    public function getTotalsForDisplay()
    {	
	
		$amount = $this->getAmount();	
		$fontSize = $this->getFontSize() ? $this->getFontSize() : 7;
		if(floatval($amount))
		{
			$amount = $this->getOrder()->formatPriceTxt($amount);
			if ($this->getAmountPrefix()) 
			{
				$amount = $this->getAmountPrefix().$amount;
			}		
			
			$totals = array(
						array(
							'label' => Mage::helper('onestepcheckout')->__('Giftwrap'),
							'amount' => $amount,
							'font_size' => $fontSize,
						)
					);						
			return $totals;
		}
	}
    public function getAmount()
    {
        return $this->getOrder()->getOnestepcheckoutGiftwrapAmount();
    }	
}