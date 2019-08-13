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

class Abserve_Magenotification_Helper_License extends Mage_Core_Helper_Abstract
{
	public function getResumeCode($licensekey)
	{
		return md5($licensekey.'abserve-extension-upgrade-license-59*47@');
	}
	
	public function getUpgradePrice($licensekey,$licensetype)
	{
		$resume = $this->getResumeCode($licensekey);
		/* try{
			$xmlRpc = new Zend_XmlRpc_Client(Abserve_Magenotification_Model_Keygen::SERVER_URL.'api/xmlrpc/');
			$session = $xmlRpc->call('login', array('username'=>Abserve_Magenotification_Model_Keygen::WEBSERVICE_USER,'password'=>Abserve_Magenotification_Model_Keygen::WEBSERVICE_PASS));
			$result = $xmlRpc->call('call', array('sessionId' => $session,
												  'apiPath'   => 'licensemanager.getupgradeprice',
												  'args'      => array( $licensekey,
																		$licensetype,
																		$resume,
								)));
			if(!$result){ //error
				throw new Exception($this->__('Error! please try again.'));
				return;
			}
			return $result;
		} catch(Exception $e){
			throw new Exception($this->__('Error! please try again.').'<br/>'.$e->getMessage());
		}	 */		
	}
}