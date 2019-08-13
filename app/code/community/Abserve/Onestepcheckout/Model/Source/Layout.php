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
class Abserve_Onestepcheckout_Model_Source_Layout {
	public function toOptionArray() {
		$options = array();

		$options[] = array(
			'label' => '2 Columns',
			'value' => '2columns'
		);
		
		$options[] = array(
			'label' => '3 Columns',
			'value' => '3columns'
		);
		
		return $options;
	}
}
