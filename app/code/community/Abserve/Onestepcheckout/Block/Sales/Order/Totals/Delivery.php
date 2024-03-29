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
class Abserve_Onestepcheckout_Block_Sales_Order_Totals_Delivery extends Mage_Sales_Block_Order_Totals
{
    public function _prepareLayout()
    {
	return parent::_prepareLayout();
    }
    
    public function getDelivery($order){
        $delivery = Mage::getModel('onestepcheckout/delivery')->load($order->getId(), 'order_id');
        return $delivery;
    }

    protected function _beforeToHtml()
    {
        if(!Mage::helper('magenotification')->checkLicenseKey('onestepcheckout')){
                $this->setTemplate(null);
        }
        return parent::_beforeToHtml();
    }

   
}