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

class Abserve_AutoCurrency_Model_System_Config_Source_Database
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'ip2country', 'label'=>Mage::helper('autocurrency')->__('IP to Country')),
            array('value' => 'maxmind', 'label'=>Mage::helper('autocurrency')->__('Max Mind')),            
        );
    }

	/**
	 * Get options in "key-value" format
	 *
	 * @return array
	 */
	public function toArray()
	{
		return array(
			'ip2country' => Mage::helper('autocurrency')->__('IP to Country'),
			'maxmind' => Mage::helper('autocurrency')->__('Max Mind'),			
		);
	}
}
