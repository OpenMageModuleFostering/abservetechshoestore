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
class Abserve_OneStepCheckout_Model_Source_Ajaxbilling {
	public function toOptionArray() {
		$fields = array('Country', 'Postcode', 'State/region', 'City');
		$options = array();		
		foreach($fields as $field)	{
			$options[] = array('label' => $field, 'value' => strtolower($field));
		}
		return $options;
	}
}