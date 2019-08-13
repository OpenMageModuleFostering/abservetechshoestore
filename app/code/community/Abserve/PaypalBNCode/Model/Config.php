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
class Abserve_PaypalBNCode_Model_Config extends Mage_Paypal_Model_Config
{
    /**
     * BN code getter
     * override method
     *
     * @param string $countryCode ISO 3166-1
     */
    public function getBuildNotationCode($countryCode = null)
    {
		if($this->isMageEnterprise()){
			$newBnCode = 'Abserve_SI_MagentoEE';
		} else {
			$newBnCode = 'Abserve_SI_MagentoCE';
		}
		
        $bnCode = parent::getBuildNotationCode($countryCode);
		
		if(class_exists("Abserve_Onestepcheckout_Helper_Data") && Mage::getStoreConfig('onestepcheckout/general/active')){
			return $newBnCode;
		} else {
			return $bnCode;
		}
    }
	
	public function isMageEnterprise() {
		return Mage::getConfig ()->getModuleConfig ( 'Enterprise_Enterprise' ) 
			&& Mage::getConfig ()->getModuleConfig ( 'Enterprise_AdminGws' ) 
			&& Mage::getConfig ()->getModuleConfig ( 'Enterprise_Checkout' ) 
			&& Mage::getConfig ()->getModuleConfig ( 'Enterprise_Customer' );
	}		

}