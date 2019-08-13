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

class Abserve_Magenotification_Model_License extends Mage_Core_Model_Abstract
{
    public function _construct(){
        parent::_construct();
        $this->_init('magenotification/license');
    }
    
    public function loadByLicenseExtension($licenseKey, $extensionName){
    	$item = $this->getCollection()
    		->addFieldToFilter('extension_code',$extensionName)
    		->addFieldToFilter('license_key',$licenseKey)
    		->getFirstItem();
   		if ($item && $item->getId()){
   			$this->addData($item->getData());
   		}
   		$this->setData('extension_code',$extensionName)
   			->setData('license_key',$licenseKey);
   		return $this;
    }
}