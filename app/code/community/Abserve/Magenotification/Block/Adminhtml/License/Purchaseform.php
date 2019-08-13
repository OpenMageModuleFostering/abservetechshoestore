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
class Abserve_Magenotification_Block_Adminhtml_License_Purchaseform 
	extends Mage_Adminhtml_Block_Template
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('magenotification/license/purchaseform.phtml');
	}
	
	public function getPurchaseUrl()
	{
		return $this->getUrl('magenotification/adminhtml_license/purchase',array('extension'=>$this->getExtensionName(),'_secure'=>true));
	}
}