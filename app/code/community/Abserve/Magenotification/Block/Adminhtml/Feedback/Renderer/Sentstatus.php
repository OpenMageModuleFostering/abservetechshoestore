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
class Abserve_Magenotification_Block_Adminhtml_Feedback_Renderer_Sentstatus
	extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	/* Render Grid Column*/
	public function render(Varien_Object $row) 
	{
		if($row->getIsSent() == '1'){
			return '<span class="grid-severity-notice"><span>'.$this->__('Sent').'</span></span>';
		} else {
			return '<span class="grid-severity-critical"><span>'.$this->__('Not Sent').'</span></span>';
		}
	}
}