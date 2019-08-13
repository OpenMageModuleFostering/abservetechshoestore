<?php
class Abserve_Opc_VersionController extends Mage_Core_Controller_Front_Action{
	
	public function indexAction(){
		$version = Mage::getConfig()->getModuleConfig("Abserve_Opc")->version;
		echo 'Abserve OPC Version: ' . $version;
		return;
	}
}