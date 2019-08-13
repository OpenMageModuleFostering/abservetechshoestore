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
class Abserve_Onestepcheckout_Adminhtml_SimiController extends Mage_Adminhtml_Controller_Action
{
public function indexAction(){
		$url = "https://www.simicart.com/usermanagement/checkout/buyProfessional/?extension=3&utm_source=abservebuyer&utm_medium=backend&utm_campaign=Abserve Buyer Backend";

		Mage::app()->getResponse()->setRedirect($url)->sendResponse();
		exit();
	}

}