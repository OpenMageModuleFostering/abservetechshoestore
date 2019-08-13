<?php
/**
 * @category    Abserve
 * @package     Abserve_Module
 * @author      Abserve Developer
 * @license     http://abservetech.com/license-agreement/
 * @copyright   Copyright (c) 2015 abservetech (http://abservetech.com)
 */

class Abserve_Onestepcheckout_Block_Adminhtml_Geoip_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'onestepcheckout';
        $this->_controller = 'adminhtml_geoip';
        
		$this->_updateButton('save', 'label', Mage::helper('onestepcheckout')->__('Upload'));
		
		$this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Upload and Continue'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
		
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('geoip_content') == null)
                    tinyMCE.execCommand('mceAddControl', false, 'geoip_content');
                else
                    tinyMCE.execCommand('mceRemoveControl', false, 'geoip_content');
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
			//edit back button in import
			function backEdit()
			{
				window.history.back();
			}			
        ";		
    }
    /**
     * get text to show in header when edit an item
     *
     * @return string
     */
    public function getHeaderText()
    {        
        return Mage::helper('onestepcheckout')->__('Upload new GeoIP database');
    }
}
