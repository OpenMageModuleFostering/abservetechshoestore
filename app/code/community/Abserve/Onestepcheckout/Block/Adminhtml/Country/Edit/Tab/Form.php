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

class Abserve_Onestepcheckout_Block_Adminhtml_Country_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
     protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('import_form', array('legend'=>Mage::helper('onestepcheckout')->__('Upload File')));
     
      $fieldset->addField('csv_country', 'file', array(
          'label'     => Mage::helper('onestepcheckout')->__('CSV File'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'csv_country',
		  'note'	  => Mage::helper('onestepcheckout')->__("Use Zip code database only to avoid error
						<br/>Link download").": <a href='http://www.abserve.com/geoip-databases.html'>Here</a>",
      ));
	  
      return parent::_prepareForm();
  }
}