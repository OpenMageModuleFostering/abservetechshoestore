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
class Abserve_AjaxWishlist_Block_AjaxWishlist extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getAjaxWishlist()     
     { 
        if (!$this->hasData('ajaxwishlist')) {
            $this->setData('ajaxwishlist', Mage::registry('ajaxwishlist'));
        }
        return $this->getData('ajaxwishlist');
        
    }
}