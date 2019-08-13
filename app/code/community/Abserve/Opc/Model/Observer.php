<?php
class Abserve_Opc_Model_Observer{
	
	public function checkRequiredModules($observer){
		$cache = Mage::app()->getCache();
		
		if (Mage::getSingleton('admin/session')->isLoggedIn()) {
			if (!Mage::getConfig()->getModuleConfig('Abserve_All')->is('active', 'true')){
				//if ($cache->load("abserve_opc")===false)
				if ($cache->load("abserve_opc")===true){
					$message = 'Important: Please setup Abserve_ALL in order to finish <strong>Abserve One Page Checkout</strong> installation.<br />
						Please download <a href="http://abserveextensions.com/media/modules/abserve_all.tgz" target="_blank">Abserve_ALL</a> and setup it via Magento Connect.';
				
					Mage::getSingleton('adminhtml/session')->addNotice($message);
					$cache->save('true', 'abserve_opc', array("abserve_opc"), $lifeTime=5);
				}
			}
		}
	}
	
	
	
	public function newsletter($observer){
		$_session = Mage::getSingleton('core/session');

		$newsletterFlag = $_session->getIsSubscribed();
		if ($newsletterFlag==true){
			
			$email = $observer->getEvent()->getOrder()->getCustomerEmail();
			
			$subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
	        if($subscriber->getStatus() != Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED && $subscriber->getStatus() != Mage_Newsletter_Model_Subscriber::STATUS_UNSUBSCRIBED) {
	            $subscriber->setImportMode(true)->subscribe($email);
	            
	            $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
	            $subscriber->sendConfirmationSuccessEmail();
	        }
			
		}
		
	}
	
	public function applyComment($observer){
		$order = $observer->getData('order');
		
		$comment = Mage::getSingleton('core/session')->getOpcOrderComment();
		if (!Mage::helper('opc')->isShowComment() || empty($comment)){
			return;
		}
		try{
			$order->setCustomerComment($comment);
			$order->setCustomerNoteNotify(true);
			$order->setCustomerNote($comment);
			$order->addStatusHistoryComment($comment)->setIsVisibleOnFront(true)->setIsCustomerNotified(true);
			$order->save();
			$order->sendOrderUpdateEmail(true, $comment);
		}catch(Exception $e){
			Mage::logException($e);
		}
	}

}