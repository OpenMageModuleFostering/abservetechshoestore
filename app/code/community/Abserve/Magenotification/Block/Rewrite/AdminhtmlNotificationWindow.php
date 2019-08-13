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
class Abserve_Magenotification_Block_Rewrite_AdminhtmlNotificationWindow extends Mage_Adminhtml_Block_Notification_Window
{
    protected function _construct()
    {
        parent::_construct();

		if(Mage::getModel('magenotification/magenotification')->is_existedUrl($this->getLastNotice()->getUrl()))
		{
			$url = $this->getUrl('magenotification/adminhtml_magenotification/readdetail',array('id'=>$this->getLastNotice()->getId()));
			
			$this->setHeaderText(addslashes($this->__('Abserve Message')));
	
			$this->setNoticeMessageUrl(addslashes($url));
		}
	}

}