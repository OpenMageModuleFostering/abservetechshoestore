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

class Abserve_CustomMenu_Block_Toggle extends Mage_Core_Block_Template
{
    public function _prepareLayout()
    {
        if (!Mage::getStoreConfig('custom_menu/general/enabled')) return;
        $layout = $this->getLayout();
        $topnav = $layout->getBlock('catalog.topnav');
        $head   = $layout->getBlock('head');
        if (is_object($topnav) && is_object($head)) {
            $topnav->setTemplate('abservemegamenu/custommenu/top.phtml');
            $head->addItem('skin_js', 'js/abservemegamenu/custommenu/custommenu.js');
            $head->addItem('skin_css', 'css/abservemegamenu/custommenu/custommenu.css');

             // --- Carousel slider ---
            /*$head->addItem('skin_css', 'owl-carousel/owl.carousel.css');
            $head->addItem('skin_css', 'owl-carousel/owl.theme.css');            
            $head->addItem('skin_js', 'owl-carousel/owl.carousel.min.js');*/
            

            // --- Insert menu content ---
            if (!Mage::getStoreConfig('custom_menu/general/ajax_load_content')) {
                $menuContent = $layout->getBlock('custommenu-content');
                if (!is_object($menuContent)) {
                    $menuContent = $layout->createBlock('core/template', 'custommenu-content')
                        ->setTemplate('abservemegamenu/custommenu/menucontent.phtml');
                }
                $positionTarget = $layout->getBlock('before_body_end');
                if (is_object($positionTarget)) $positionTarget->append($menuContent);
            }
        }
    }
}
