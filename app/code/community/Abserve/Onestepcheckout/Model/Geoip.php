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
class Abserve_Onestepcheckout_Model_Geoip extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('onestepcheckout/geoip');
    }
	public function import()
	{
		$data = $this->getData();				
		if($data['city']){
			$data['city']  = mb_convert_encoding($data['city'], "UTF-8");	
			$data['city'] = trim($data['city'], ' ');
			$data['city'] = trim($data['city'], '"');
		}
		if($data['region']){
			$data['region']  = mb_convert_encoding($data['region'], "UTF-8");
			$data['region'] = trim($data['region'], ' ');
			$data['region'] = trim($data['region'], '"');
		}
		if($data['country']){			
			$data['country'] = trim($data['country'], ' ');
			$data['country'] = trim($data['country'], '"');
		}
		if($data['postcode']){			
			$data['postcode'] = trim($data['postcode'], ' ');
			$data['postcode'] = trim($data['postcode'], '"');
		}
		//prepare status
		$data['status'] = 1;
			//check exited row
		if(!$data['postcode'] && !$data['city']){
			return false;				
		}elseif($data['city'] && !$data['postcode']){			
			$collection = $this->getCollection()						
							->addFieldToFilter('country',$data['country'])
							->addFieldToFilter('region',$data['region'])							
							->addFieldToFilter('city',$data['city'])						
							;
		}elseif($data['postcode'] && !$data['city']){			
			$collection = $this->getCollection()						
							->addFieldToFilter('country',$data['country'])
							->addFieldToFilter('region',$data['region'])															
							->addFieldToFilter('postcode',$data['postcode'])
							;
		}else{			
			$collection = $this->getCollection()						
							->addFieldToFilter('country',$data['country'])
							->addFieldToFilter('region',$data['region'])
							->addFieldToFilter('postcode',$data['postcode'])
							->addFieldToFilter('city',$data['city'])						
							;        
		}
		
		if(count($collection))
			return false;
			
			
		$this->setData($data);
		$this->save();
		
		return $this->getId();
	}
}