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
/**
 * Abserve
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Abserve.com license that is
 * available through the world-wide-web at this URL:
 * http://www.abserve.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category    Abserve
 * @package     Abserve_Onestepcheckout
 * @copyright   Copyright (c) 2012 Abserve (http://www.abserve.com/)
 * @license     http://www.abserve.com/license-agreement.html
 */

/**
 * Rewardpoints Config Field Separator Block
 * 
 * @category    Abserve
 * @package     Abserve_Onestepcheckout
 * @author      Abserve Developer
 */
class Abserve_Onestepcheckout_Block_Adminhtml_System_Config_Form_Field_Separator
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * render separator config row
     * 
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $fieldConfig = $element->getFieldConfig();
        $htmlId = $element->getHtmlId();
        $html  = '<tr id="row_' . $htmlId . '">'
                . '<td class="label" colspan="3">';
        
        $marginTop = $fieldConfig->margin_top ? (string)$fieldConfig->margin_top : '0px';
        $customStyle = $fieldConfig->style ? (string)$fieldConfig->style : '';
        
        $html .= '<div style="margin-top: ' . $marginTop
                . '; font-weight: bold; border-bottom: 1px solid #dfdfdf;'
                . $customStyle .'">';
        $html .= $element->getLabel();
        $html .= '</div></td></tr>';
        return $html;
    }
}
