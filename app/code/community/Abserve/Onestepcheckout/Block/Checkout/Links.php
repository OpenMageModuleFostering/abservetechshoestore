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
class Abserve_Onestepcheckout_Block_Checkout_Links extends Mage_Checkout_Block_Links
{

    public function addCheckoutLink()
    {
		$parentBlock = $this->getParentBlock();
		if(Mage::helper('onestepcheckout')->enabledOnestepcheckout() && Mage::helper('core')->isModuleOutputEnabled('Abserve_Onestepcheckout') ){
			$text = $this->__('Checkout');
			if($parentBlock)
				$parentBlock->addLink(
					$text, 'onestepcheckout/index', $text,
					true, array('_secure' => true), 60, null,
					'class="top-link-checkout"'
				);
		}else{
			if (!$this->helper('checkout')->canOnepageCheckout()) {
				return $this;
			}

        
			if ($parentBlock && Mage::helper('core')->isModuleOutputEnabled('Mage_Checkout')) {
				$text = $this->__('Checkout');
				$parentBlock->addLink(
					$text, 'checkout', $text,
					true, array('_secure' => true), 60, null,
					'class="top-link-checkout"'
				);
			}
		}
        
        return $this;
    }
}
