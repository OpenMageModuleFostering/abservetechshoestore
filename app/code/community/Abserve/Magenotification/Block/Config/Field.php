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
class Abserve_Magenotification_Block_Config_Field
	extends Mage_Adminhtml_Block_System_Config_Form_Field
	implements Varien_Data_Form_Element_Renderer_Interface
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
    	$html = $element->getBold() ? '<strong>' : '';
    	$html.= $element->getValue();
    	$html.= $element->getBold() ? '</strong>' : '';
    	$html.= $element->getAfterElementHtml();
    	return $html;	
    } 
}