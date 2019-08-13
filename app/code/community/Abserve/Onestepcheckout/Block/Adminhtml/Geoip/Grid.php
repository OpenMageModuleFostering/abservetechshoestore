<?php
/**
 * @category    Abserve
 * @package     Abserve_Module
 * @author      Abserve Developer
 * @license     http://abservetech.com/license-agreement/
 * @copyright   Copyright (c) 2015 abservetech (http://abservetech.com)
 */
class Abserve_Onestepcheckout_Block_Adminhtml_Geoip_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * prepare tab form's information
     *
     * @return Abserve_Geoip_Block_Adminhtml_Geoip_Edit_Tab_Form
     */
    public function __construct()
    {	
        parent::__construct();
        $this->setUseAjax(false);
    }    
	
	public function getGeoip()
	{
            return Mage::getModel('onestepcheckout/countrylist')->load(1, 'type');
	}
		
	
	public function _prepareLayout()
	{
            $this->setTemplate('onestepcheckout/geoipgrid.phtml');
	}	

	public function linkUpdateGeoip()
	{
		$geoIp = Mage::getModel('onestepcheckout/countrylist')->load(1, 'type');
		if($geoIp->getCurrentVersion() == $geoIp->getLastVersion() && $geoIp->getStatus()=='1'){
			return false;
		}
		else{
			$link = Mage::getSingleton('adminhtml/url')->getUrl('onestepcheckoutadmin/adminhtml_geoip/showGeoip',array(
				'website' => $this->getRequest()->getParam('website'),		
				'_query'  => array('isAjax'  => 'false'),			
			));
		return $link;
		}
	}
		
}