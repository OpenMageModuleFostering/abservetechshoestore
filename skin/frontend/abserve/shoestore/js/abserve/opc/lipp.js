;
var Abserve=Abserve||{};
$j = jQuery;
Abserve.LIPP = {
	config: null,
	lipp_enabled: false,
	
	init: function(){
		if (typeof(lippConfig)!="undefined"){
			this.config = $j.parseJSON(lippConfig);
			if (this.config.paypalLightBoxEnabled==true){
				this.initOPC();
				this.initOnCart();
			}
		}
	}, 
	
	initOPC: function(){
		
		Abserve.LIPP.lipp_enabled = true;
		
		$j(document).on('click', '.opc-wrapper-opc #checkout-payment-method-load .radio', function(e){
			var method = payment.currentMethod;
			if (method.indexOf('paypaluk_express')!=-1 || method.indexOf('paypal_express')!=-1){
				if (Abserve.OPC.Checkout.config.comment!=="0"){
					Abserve.OPC.saveCustomerComment();
				}
			}
		});

	},
	
	redirectPayment: function(){
		PAYPAL.apps.Checkout.startFlow(Abserve.LIPP.config.baseUrl + 'onepage/express/start');
		return false;
	},
	
	initOnCart: function(){
		$j('.checkout-types .paypal-logo a, .opc-menu .paypal-logo a').click(function(e){		
			e.preventDefault();
			PAYPAL.apps.Checkout.startFlow(Abserve.LIPP.config.baseUrl + 'onepage/express/start');
		});
	}	
};

$j(document).ready(function(){
	Abserve.LIPP.init();
});