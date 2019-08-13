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

if (!Mage::getStoreConfig('custom_menu/general/enabled')) {
    class Abserve_CustomMenu_Block_Topmenu extends Mage_Page_Block_Html_Topmenu {}
    return;
}

class Abserve_CustomMenu_Block_Topmenu extends Abserve_CustomMenu_Block_Navigation {}
