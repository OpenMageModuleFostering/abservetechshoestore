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

class Abserve_Magenotification_Model_Source_Time extends Mage_Core_Model_Config_Data
{	
    protected function _afterLoad()
    {
        $value = (string)$this->getValue();
		if($value == '')
		{
            $timestamp = $this->getTimestamp();
			
			$html = date('Y-m-d H:i:s',$timestamp);
			
		} else {
			$html = date('Y-m-d H:i:s',intval($value));
		}
		
		$this->setValue($html);
			
    }
	
	protected function _beforeSave()
	{
		$value = $this->getValue();
		$value = strtotime($value);
		$this->setValue($value);
	}

	private function getTimestamp()
	{	
		return Mage::getModel('core/date')->timestamp(time());
	}
	
	public function toOptionArray()
	{
		
	}
}