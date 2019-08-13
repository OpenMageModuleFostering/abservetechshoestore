<?php
$abserve_av_class = false;

if (! Mage::helper ( 'opc' )->isEnable ()) {
	// check if Abserve AddressValidation exists
	$path = Mage::getBaseDir ( 'app' ) . DS . 'code' . DS . 'local' . DS;
	$file = 'Abserve/AddressVerification/controllers/OnepageController.php';
	// load Abserve OPC class
	if (file_exists ( $path . $file )){		
		// check if Abserve AV enabled
		if (Mage::helper ( 'addressverification' )->isAddressVerificationEnabled ()){
			$abserve_av_class = true;
		}
	}
}

if (! $abserve_av_class) {
	require_once Mage::getModuleDir ( 'controllers', 'Mage_Checkout' ) . DS . 'OnepageController.php';
	class Abserve_Opc_Checkout_OnepageController extends Mage_Checkout_OnepageController {
		
		/**
		 * Checkout page
		 */
		public function indexAction() {
			$scheme = Mage::app ()->getRequest ()->getScheme ();
			if ($scheme == 'http') {
				$secure = false;
			} else {
				$secure = true;
			}
			
			if (Mage::helper ( 'opc' )->isEnable ()) {
				$this->_redirect ( 'onepage', array (
						'_secure' => $secure 
				) );
				return;
			} else {
				parent::indexAction ();
			}
		}
	}
} else {
	require_once Mage::getModuleDir ( 'controllers', 'Abserve_AddressVerification' ) . DS . 'OnepageController.php';
	class Abserve_Opc_Checkout_OnepageController extends Abserve_AddressVerification_OnepageController {
	}
}
