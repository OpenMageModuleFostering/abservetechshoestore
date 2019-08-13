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
class Abserve_OneStepCheckout_Model_Source_Reloadpayment {

	public function toOptionArray() 
	{
		$options = array();		
		$options[] = array('label' => 'When all required fields are filled', 'value' => '1');
		$options[] = array('label' => 'When any triggering fields change', 'value' => '2');
		return $options;
	}
}