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

class Abserve_Onestepcheckout_Block_Adminhtml_Config_Edit extends Mage_Adminhtml_Block_System_Config_Edit
{
    protected function _prepareLayout()
    {
		parent::_prepareLayout();
		if($this->getRequest()->getModuleName() == 'admin' && $this->getRequest()->getControllerName() == 'system_config'
			&& $this->getRequest()->getActionName() == 'edit' && $this->getRequest()->getParam('section') == 'onestepcheckout'){
			$this->setChild('save_button',
				$this->getLayout()->createBlock('adminhtml/widget_button')
					->setData(array(
						'label'     => Mage::helper('adminhtml')->__('Save Config'),
						'onclick'   => 'checkValueRequire()',
						'class' => 'save',
					))
			);
		}else{
			$this->setChild('save_button',
				$this->getLayout()->createBlock('adminhtml/widget_button')
					->setData(array(
						'label'     => Mage::helper('adminhtml')->__('Save Config'),
						'onclick'   => 'configForm.submit()',
						'class' => 'save',
					))
			);
		}
    }

}
