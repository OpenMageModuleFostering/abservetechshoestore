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
class Abserve_Magenotification_Block_Adminhtml_Feedback_Renderer_Status
	extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	/* Render Grid Column*/
	public function render(Varien_Object $row) 
	{
		$statuses = Mage::helper('magenotification')->getFeedbackStatusList();
		switch((int)$row->getStatus()){
			case 1:
				$prefix = 'notice';
				break;
			case 2:
				$prefix = 'critical';
				break;
			case 3:
			default:
				$prefix = 'major';
		}
		return '<span class="grid-severity-'.$prefix.'"><span>'.$statuses[(int)$row->getStatus()].'</span></span>';
	}
}