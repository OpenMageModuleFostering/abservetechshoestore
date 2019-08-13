<?php
/**
 * @category    Abserve
 * @package     Abserve_Module
 * @author      Abserve Developer
 * @license     http://abservetech.com/license-agreement/
 * @copyright   Copyright (c) 2015 abservetech (http://abservetech.com)
 */

class Abserve_Onestepcheckout_Block_Adminhtml_Geoip_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('geoip_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('onestepcheckout')->__('Upload New GeoIP Database'));
    }
    
    /**
     * prepare before render block to html
     *
     * @return Abserve_Geoip_Block_Adminhtml_Geoip_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('onestepcheckout')->__('Upload new GeoIP Database'),
            'title'     => Mage::helper('onestepcheckout')->__('Upload new GeoIP Database'),
            'content'   => $this->getLayout()
                                ->createBlock('onestepcheckout/adminhtml_geoip_edit_tab_form')
                                ->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}