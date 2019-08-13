<?php
class Abserve_Settings_IndexController extends Mage_Core_Controller_Front_Action{

	public function IndexAction(){
		$this->loadLayout();
		$this->renderLayout();
	}

	public function BannerAction(){
		$this->loadLayout();
		$this->renderLayout();
	}

	public function BrandAction(){
		$this->loadLayout();
		$this->renderLayout();
	}

	public function AdAction(){
		$this->loadLayout();
		$this->renderLayout();
	}

	public function NewproductAction(){
		$this->loadLayout();
		$this->renderLayout();
	}

	public function CategoryAction(){
		$this->loadLayout();
		$this->renderLayout();
	}

	public function DeliveryAction(){
		$this->loadLayout();
		$this->renderLayout();
	}

	public function CategoryhomeAction(){
		$this->loadLayout();
		$this->renderLayout();
	}

	public function GetproductbyidAction(){
		$product_id = 18;
		$_product = Mage::getModel('catalog/product')->load($product_id);
		echo $_product->getPrice();
	}

	public function BlogAction(){
		$this->loadLayout();
		$this->renderLayout();
	}
}