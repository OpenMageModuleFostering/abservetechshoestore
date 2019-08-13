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

class Abserve_Magenotification_Model_Config extends Varien_Simplexml_Config
{
    const COMMERCIAL_LICENSE    = 'commercial';
    const DEPEND_LICENSE        = 'depend';
    const TRIAL_LICENSE         = 'trial';
    const FREE_LICENSE          = 'free';
    
    /**
     * Init License Config Information
     * XML Path: app/code/community/Abserve/license_certificates
     * 
     * @param type $sourceData
     */
    public function __construct($sourceData=null)
    {
        $certificateFolder = 'app/code/community/Abserve/license_certificates';
        $certificateFolder = str_replace('/', DS, $certificateFolder);
        $configFiles = glob(BP . DS . $certificateFolder . DS . '*.xml');
        $this->loadFile(current($configFiles));
        while ($file = next($configFiles)) {
            $merge = new Varien_Simplexml_Config;
            $merge->loadFile($file);
            $this->extend($merge);
        }
    }
}
