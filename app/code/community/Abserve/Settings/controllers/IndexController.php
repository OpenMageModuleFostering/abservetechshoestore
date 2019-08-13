<?php
class Abserve_Settings_IndexController extends Mage_Core_Controller_Front_Action{

	public function IndexAction(){
		$this->loadLayout();
		$this->renderLayout();
	}

	public function GetorderAction(){
		?>
		<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
		<script type="text/javascript">
			stLight.options({
				publisher:'12345',
			});
		</script>
		<span class="st_facebook" st_summary="Nice meet"  st_image="http://shoppezzy.com/yudala/skin/frontend/ma_bonnie/ma_bonnie3/images/logo.jpg" st_url="http://192.168.1.11/shoestore/settings/index/getorder" st_title="Testing!">
			<img src="http://shoppezzy.com/yudala/skin/frontend/ma_bonnie/ma_bonnie3/images/logo.jpg" width="100">
		</span>


		<!-- <span class="st_twitter" st_url="http://sharethis.com" st_title="Testing!"></span>
		<span class="st_linkedin" st_url="http://sharethis.com" st_title="Testing!"></span> -->
		<?php
		echo $currentUrl = Mage::helper('core/url')->getCurrentUrl();
		echo "<br>";
		echo $url = Mage::getSingleton('core/url')->parseUrl($currentUrl);
		echo "<br>";
		echo $path = $url->getPath();

		/*$orderIncrementId = 100000006;
		$order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
		echo "order subtotal: ".$order->getSubtotal()."<br>";
		echo "shipping: ".$order->getShippingAmount()."<br>";
		echo "discount: ".$order->getDiscountAmount()."<br>";
		echo "tax: ".$order->getTaxAmount()."<br>";
		echo "grand total".$order->getGrandTotal()."<br><br><br>";

		$orderItems = array();
		foreach($order->getItemsCollection() as $item)
		{
		    
		    $row=array();
		    $row['sku'] = $item->getSku();
		    $row['original_price'] = $item->getOriginalPrice();
		    $row['price'] = $item->getPrice();
		    $row['qty_ordered']= (int)$item->getQtyOrdered();
		    $row['subtotal']= $item->getSubtotal();
		    $row['tax_amount']= $item->getTaxAmount();
		    $row['tax_percent']= $item->getTaxPercent();
		    $row['discount_amount']= $item->getDiscountAmount();
		    $row['row_total']= $item->getRowTotal();
		    $orderItems[]=$row;
		}*/
		/*echo "All items in the order:<br>".print_r($orderItems,true)."<br><br><br>";
		echo 'Payment Method - '.$payment_method_code = $order->getPayment()->getMethodInstance()->getCode(); */
		/*echo "name : ";
		if($order->getCustomerId() === NULL){
			echo "vddddd";
			echo $order->getBillingAddress()->getFirstname();
			echo "<br/>";
			echo $order->getBillingAddress()->getLastname();
		} else {
			$customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
			echo $customer->getDefaultBillingAddress()->getFirstname();
			echo "name : ";
			echo "<br/>";
			echo $customer->getDefaultBillingAddress()->getLastname();
		}
		echo "<br/>";echo "<br/>";echo "<br/>";echo "<br/>";

		echo "sybmol".$orderSymbolCode = Mage::app()->getLocale()->currency($order->getOrderCurrencyCode())->getSymbol();*/

		/*echo $order->getId();
		echo "<br>";
		echo $order->getCreatedAt();
		$ordered_items = $order->getAllItems();
		foreach($ordered_items as $item){     
			echo $item->getItemId(); 
			echo "<br>";
			echo $item->getSku();  
			echo "<br>";   
			echo $item->getQtyOrdered(); 
			echo "<br>";
			echo $item->getName();   
			echo "<br>";  
		} */
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