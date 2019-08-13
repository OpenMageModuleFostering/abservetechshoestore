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
class Abserve_Onestepcheckout_Block_Postcode extends Mage_Core_Block_Template
{
    /**
     * prepare block's layout
     *
     * @return Abserve_Onestepcheckout_Block_Postcode
     */
	 
   protected function _toHtml()
    {
        // Mage::dispatchEvent('adminhtml_block_html_before', array('block' => $this));
        return parent::_toHtml();
    }
}