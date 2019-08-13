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
class Abserve_Onestepcheckout_Model_Countrylist extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('onestepcheckout/countrylist');
    }	
	
	public function getStatuses(){
		$updated = Mage::helper('onestepcheckout')->__('Updated');
		$needUpdate = Mage::helper('onestepcheckout')->__('Need Updated');
		$status = array(
						0 => $needUpdate,
						1 => $updated,
				);
		return $status;
	}
	
	public function getStatusLabel($status)
	{
		if($status == 0) return Mage::helper('onestepcheckout')->__('Need Updated');
		if($status == 1) return Mage::helper('onestepcheckout')->__('Updated');
	}
}