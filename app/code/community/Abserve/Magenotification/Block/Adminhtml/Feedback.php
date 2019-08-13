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
class Abserve_Magenotification_Block_Adminhtml_Feedback extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_feedback';
    $this->_blockGroup = 'magenotification';
    $this->_headerText = Mage::helper('magenotification')->__('Feedbacks Manager');
    parent::__construct();
    $this->_updateButton('add', 'label', Mage::helper('magenotification')->__('Post Feedback'));	
  }
}