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
class Abserve_Magenotification_Block_Config_Label
	extends Varien_Data_Form_Element_Label
{
    public function getElementHtml()
    {
    	$html = $this->getBold() ? '<strong>' : '';
    	$html.= $this->getValue();
    	$html.= $this->getBold() ? '</strong>' : '';
    	$html.= $this->getAfterElementHtml();
    	return $html;
    }   
}