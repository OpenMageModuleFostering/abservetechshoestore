;
//dummy
Billing =  Class.create();
Shipping =  Class.create();
$j = jQuery;

var Abserve=Abserve||{};

Abserve.OPC = {
		
		agreements : null,
		saveOrderStatus:false,
		is_subscribe:false,
		
		initMessages: function(){
			$j('.close-message-wrapper, .opc-messages-action .button').click(function(){
				$j('.opc-message-wrapper').hide();
				$j('.opc-message-container').empty();
			});
		},

		/** CREATE EVENT FOR SAVE ORDER **/
		initSaveOrder: function(){
			
			$j(document).on('click', '.opc-btn-checkout', function(){

				if (Abserve.OPC.Checkout.disabledSave==true)
					return;

				// check agreements
				var mis_aggree = false;
				$j('#checkout-agreements input[name*="agreement"]').each(function(){
					if(!$j(this).is(':checked')){
						mis_aggree = true;
					}
				});
				
				if(mis_aggree){
					$j('.opc-message-container').html($j('#agree_error').html());
					$j('.opc-message-wrapper').show();
					Abserve.OPC.Checkout.hideLoader();
					Abserve.OPC.Checkout.unlockPlaceOrder();
					Abserve.OPC.saveOrderStatus = false;
					return false;
				}
				///
				
				var addressForm = new VarienForm('opc-address-form-billing');
				if (!addressForm.validator.validate()){
					return;
				}
				
				if (!$j('input[name="billing[use_for_shipping]"]').prop('checked')){
					var addressForm = new VarienForm('opc-address-form-shipping');
					if (!addressForm.validator.validate()){				
						return;
					}
				}
				
				// check if LIPP enabled
			    if(typeof(Abserve.LIPP) != 'undefined' && Abserve.LIPP != undefined && Abserve.LIPP != '' && Abserve.LIPP)
			    {
					if(Abserve.LIPP.lipp_enabled){
						var method = payment.currentMethod;
						if (method.indexOf('paypaluk_express')!=-1 || method.indexOf('paypal_express')!=-1){
							if (Abserve.OPC.Checkout.config.comment!=="0")
								Abserve.OPC.saveCustomerComment();							
							Abserve.LIPP.redirectPayment();
							return;
						}
					}			    	
			    }
			    ////
				
				Abserve.OPC.saveOrderStatus = true;
				Abserve.OPC.Plugin.dispatch('saveOrderBefore');
				if (Abserve.OPC.Checkout.isVirtual===false){
					Abserve.OPC.Checkout.lockPlaceOrder();
					Abserve.OPC.Shipping.saveShippingMethod();
				}else{
					Abserve.OPC.validatePayment();
				}
			});
			
		},
		
		
		
		/** INIT CHAGE PAYMENT METHOD **/
		initPayment: function(){
			
			Abserve.OPC.removeNotAllowedPaymentMethods();
			
			Abserve.OPC.bindChangePaymentFields();
			$j(document).on('click', '#co-payment-form input[type="radio"]', function(event){
				Abserve.OPC.removeNotAllowedPaymentMethods();
				
				Abserve.OPC.validatePayment();
			});
		},
		
		/** remove not allowed payment method **/
		removeNotAllowedPaymentMethods: function(){
			// remove p_method_authorizenet_directpost
			var auth_dp_obj = $j('#p_method_authorizenet_directpost');
			if(auth_dp_obj && auth_dp_obj.attr('id') == 'p_method_authorizenet_directpost')
			{
				if(auth_dp_obj.attr('checked'))
					auth_dp_obj.attr('checked', false);
				
				auth_dp_obj.parent('dt').remove();
				$j('#payment_form_authorizenet_directpost').parent('dd').remove();
				$j('#directpost-iframe').remove();
				$j('#co-directpost-form').remove();
			}
			////
		},
		
		/** CHECK PAYMENT IF PAYMENT IF CHECKED AND ALL REQUIRED FIELD ARE FILLED PUSH TO SAVE **/
		validatePayment: function(){	
			
			// check all required fields not empty
			var is_empty = false;
			$j('#co-payment-form .required-entry').each(function(){
				if($j(this).val() == '' && $j(this).css('display') != 'none' && !$j(this).attr('disabled'))
					is_empty = true;
			});

			if(!Abserve.OPC.saveOrderStatus){
				if(is_empty){
					Abserve.OPC.saveOrderStatus = false;
					Abserve.OPC.Checkout.hideLoader();
					Abserve.OPC.Checkout.unlockPlaceOrder();				
					return false;
				}
			}
			////

			var vp = payment.validate();
			if(!vp)
			{
				Abserve.OPC.saveOrderStatus = false;
				Abserve.OPC.Checkout.hideLoader();
				Abserve.OPC.Checkout.unlockPlaceOrder();
				return false;
			}

			var paymentMethodForm = new Validation('co-payment-form', { onSubmit : false, stopOnFirst : false, focusOnError : false});
			  	
			if (paymentMethodForm.validate()){
				Abserve.OPC.savePayment();
			}else{
				Abserve.OPC.saveOrderStatus = false;
				Abserve.OPC.Checkout.hideLoader();
				Abserve.OPC.Checkout.unlockPlaceOrder();
				
				Abserve.OPC.bindChangePaymentFields();
			}
			
			
		},
		
		/** BIND CHANGE PAYMENT FIELDS **/ 
		bindChangePaymentFields: function(){			
			Abserve.OPC.unbindChangePaymentFields();
			
			$j('#co-payment-form input').keyup(function(event){
				
				if (Abserve.OPC.Checkout.ajaxProgress!=false){
					clearTimeout(Abserve.OPC.Checkout.ajaxProgress);
				}
				
				Abserve.OPC.Checkout.ajaxProgress = setTimeout(function(){
					Abserve.OPC.validatePayment();
				}, 1000);
			});
			
			$j('#co-payment-form select').change(function(event){
				if (Abserve.OPC.Checkout.ajaxProgress!=false){
					clearTimeout(Abserve.OPC.Checkout.ajaxProgress);
				}
				
				Abserve.OPC.Checkout.ajaxProgress = setTimeout(function(){
					Abserve.OPC.validatePayment();
				}, 1000);
			});
		},
		
		/** UNBIND CHANGE PAYMENT FIELDS **/
		unbindChangePaymentFields: function(){
			$j('#co-payment-form input').unbind('keyup');
			$j('#co-payment-form select').unbind('change');
		},
				
		
		/** SAVE PAYMENT **/		
		savePayment: function(){
			
			if (Abserve.OPC.Checkout.xhr!=null){
				Abserve.OPC.Checkout.xhr.abort();
			}
			
			Abserve.OPC.Checkout.lockPlaceOrder();
			if (payment.currentMethod != 'stripe') {
				var form = $j('#co-payment-form').serializeArray();
				
				Abserve.OPC.Checkout.xhr = $j.post(Abserve.OPC.Checkout.config.baseUrl + 'onepage/json/savePayment',form, Abserve.OPC.preparePaymentResponse,'json');
			}else{
			
				if (typeof(Abserve.Stripe)!="undefined"){
					var nameValue = $('stripe_cc_owner').value;
					var numberValue = $('stripe_cc_number').value;
					var cvcValue =  $('stripe_cc_cid').value;
					var exp_monthValue = $('stripe_expiration').value;
					var exp_yearValue = $('stripe_expiration_yr').value;
				}else{
					//support stripe from ebizmets,				
					var nameValue = $('stripe_cc_owner').value;
					var numberValue = $('stripe_cc_number').value;
					var cvcValue =  $('stripe_cc_cvc').value;
					var exp_monthValue = $('stripe_cc_expiration_month').value;
					var exp_yearValue = $('stripe_cc_expiration_year').value;
				}
				
				
				Stripe.createToken({
					
					name: nameValue,
					number: numberValue,
					cvc: cvcValue,
					exp_month: exp_monthValue,
					exp_year: exp_yearValue
					
				}, function(status, response) {
					if (response.error) {
						Abserve.OPC.Checkout.hideLoader();
						Abserve.OPC.Checkout.xhr = null;
						Abserve.OPC.Checkout.unlockPlaceOrder();
						alert(response.error.message);
					} else {
						
						if (typeof(Abserve.Stripe)!="undefined"){
							var $form = $j(Abserve.Stripe.formId);
							$j('#stripe_token').remove();
							$input = $j('<input type="hidden" name="payment[stripe_token]" id="stripe_token" />').val(Abserve.Stripe.token);
							$input.appendTo($form);
							
							$('stripe_token').value = response['id'];
							var form = $j('#co-payment-form').serializeArray();
							Abserve.OPC.Checkout.xhr = $j.post(Abserve.OPC.Checkout.config.baseUrl + 'onepage/json/savePayment',form, Abserve.OPC.preparePaymentResponse,'json');
							
							
						}else{
							$('stripe_token').value = response['id'];
							var form = $j('#co-payment-form').serializeArray();
							Abserve.OPC.Checkout.xhr = $j.post(Abserve.OPC.Checkout.config.baseUrl + 'onepage/json/savePayment',form, Abserve.OPC.preparePaymentResponse,'json');
						}
					}
				});
			}
		},
		
		/** CHECK RESPONSE FROM AJAX AFTER SAVE PAYMENT METHOD **/
		preparePaymentResponse: function(response){
			Abserve.OPC.Checkout.xhr = null;
			
			Abserve.OPC.agreements = $j('#checkout-agreements').serializeArray();
			
			Abserve.OPC.getSubscribe();

			if (typeof(response.review)!= "undefined"){
				Abserve.OPC.Decorator.updateGrandTotal(response);
				$j('#opc-review-block').html(response.review);
				Abserve.OPC.Checkout.removePrice();
				
				// need to recheck subscribe and agreenet checkboxes
				Abserve.OPC.recheckItems();
			}

			if (typeof(response.error) != "undefined"){
				
				Abserve.OPC.Plugin.dispatch('error');
				
				$j('.opc-message-container').html(response.error);
				$j('.opc-message-wrapper').show();
				Abserve.OPC.Checkout.hideLoader();
				Abserve.OPC.Checkout.unlockPlaceOrder();
				Abserve.OPC.saveOrderStatus = false;
				
				return;
			}

			//SOME PAYMENT METHOD REDIRECT CUSTOMER TO PAYMENT GATEWAY
			if (typeof(response.redirect) != "undefined" && Abserve.OPC.saveOrderStatus===true){
				Abserve.OPC.Checkout.xhr = null;
				Abserve.OPC.Plugin.dispatch('redirectPayment', response.redirect);
				if (Abserve.OPC.Checkout.xhr==null){
					setLocation(response.redirect);
				}
				else
				{
					Abserve.OPC.Checkout.hideLoader();
					Abserve.OPC.Checkout.unlockPlaceOrder();					
				}
				
				return;
			}
			
			if (Abserve.OPC.saveOrderStatus===true){
				Abserve.OPC.saveOrder();				
			}
			else
			{
				Abserve.OPC.Checkout.hideLoader();
				Abserve.OPC.Checkout.unlockPlaceOrder();				
			}
			
			Abserve.OPC.Plugin.dispatch('savePaymentAfter');
			
			
		}, 
		
		/** SAVE ORDER **/
		saveOrder: function(){
			var form = $j('#co-payment-form').serializeArray();
			form  = Abserve.OPC.checkAgreement(form);
			form  = Abserve.OPC.checkSubscribe(form);
			form  = Abserve.OPC.getComment(form);
			
			Abserve.OPC.Checkout.showLoader();
			Abserve.OPC.Checkout.lockPlaceOrder();				

			if (Abserve.OPC.Checkout.config.comment!=="0"){
				Abserve.OPC.saveCustomerComment();
				
				setTimeout(function(){
					Abserve.OPC.callSaveOrder(form);				
				},600);
			}
			else
			{
				Abserve.OPC.callSaveOrder(form);
			}
		},
		
		callSaveOrder: function(form){
			Abserve.OPC.Plugin.dispatch('saveOrder');
			Abserve.OPC.Checkout.xhr = $j.post(Abserve.OPC.Checkout.saveOrderUrl ,form, Abserve.OPC.prepareOrderResponse,'json');			
		},
		
		/** SAVE CUSTOMER COMMNET **/
		saveCustomerComment: function(){
			$j.post(Abserve.OPC.Checkout.config.baseUrl + 'onepage/json/comment',{"comment": $j('#customer_comment').val()});
		}, 
		
		getComment: function(form){
			var com = $j('#customer_comment').val();
			form.push({"name":"customer_comment", "value":com});
			return form;
		},
		
		/** ADD AGGREMENTS TO ORDER FORM **/
		checkAgreement: function(form){
			$j.each(Abserve.OPC.agreements, function(index, data){
				form.push(data);
			});
			return form;
		},
		
		/** ADD SUBSCRIBE TO ORDER FORM **/
		getSubscribe: function(){
			if ($j('#is_subscribed').length){
				if ($j('#is_subscribed').is(':checked'))
					Abserve.OPC.is_subscribe = true;
				else
					Abserve.OPC.is_subscribe = false;
			}
			else
				Abserve.OPC.is_subscribe = false;			
		},
		
		checkSubscribe: function(form){
			
			if(Abserve.OPC.is_subscribe)
				form.push({"name":"is_subscribed", "value":"1"});
			else
				form.push({"name":"is_subscribed", "value":"0"});

			return form;
		},
		
		/** Check checkboxes after refreshing review section **/
		recheckItems: function(){
			// check subscribe
			if ($j('#is_subscribed').length){
				if(Abserve.OPC.is_subscribe)
					$j('#is_subscribed').prop('checked', true);
				else
					$j('#is_subscribed').prop('checked', false);
			}
			
			// check agree
			Abserve.OPC.recheckAgree();
		},
		
		recheckAgree: function(){			
			if(Abserve.OPC.agreements != null){
				$j.each(Abserve.OPC.agreements, function(index, data){
					$j('#checkout-agreements input').each(function(){
						if(data.name == $j(this).prop('name'))
							$j(this).prop('checked', true);
					});
				});
			}
		},
		
		/** CHECK RESPONSE FROM AJAX AFTER SAVE ORDER **/
		prepareOrderResponse: function(response){
			Abserve.OPC.Checkout.xhr = null;
			if (typeof(response.error) != "undefined" && response.error!=false){
				Abserve.OPC.Checkout.hideLoader();
				Abserve.OPC.Checkout.unlockPlaceOrder();				
				
				Abserve.OPC.saveOrderStatus = false;
				$j('.opc-message-container').html(response.error);
				$j('.opc-message-wrapper').show();
				Abserve.OPC.Plugin.dispatch('error');
				return;
			}
			
			if (typeof(response.error_messages) != "undefined" && response.error_messages!=false){
				Abserve.OPC.Checkout.hideLoader();
				Abserve.OPC.Checkout.unlockPlaceOrder();				
				
				Abserve.OPC.saveOrderStatus = false;
				$j('.opc-message-container').html(response.error_messages);
				$j('.opc-message-wrapper').show();
				Abserve.OPC.Plugin.dispatch('error');
				return;
			}
			
		
			if (typeof(response.redirect) !="undefined"){
				if (response.redirect!==false){
					setLocation(response.redirect);
					return;
				}
			}
			
			if (typeof(response.update_section) != "undefined"){
				Abserve.OPC.Checkout.hideLoader();
				Abserve.OPC.Checkout.unlockPlaceOrder();				
				
				//create catch for default logic  - for not spam errors to console
				try{
					$j('#checkout-' + response.update_section.name + '-load').html(response.update_section.html);
				}catch(e){
					
				}
				
				Abserve.OPC.prepareExtendPaymentForm();
				$j('#payflow-advanced-iframe').show();
				$j('#payflow-link-iframe').show();
				$j('#hss-iframe').show();
				
			}
			Abserve.OPC.Checkout.hideLoader();
			Abserve.OPC.Checkout.unlockPlaceOrder();				
			
			Abserve.OPC.Plugin.dispatch('responseSaveOrder', response);
		},
		
		
};



Abserve.OPC.Checkout = {
		config:null,
		ajaxProgress:false,
		xhr: null,
		isVirtual: false,
		disabledSave: false,
		saveOrderUrl: null,
		xhr2: null,
		updateShippingPaymentProgress: false,
		
		init:function(){		
			
			if (this.config==null){
				return;
			}
			//base config
			this.config = $j.parseJSON(this.config);
			
			Abserve.OPC.Checkout.saveOrderUrl = Abserve.OPC.Checkout.config.baseUrl + 'onepage/json/saveOrder',
			this.success = Abserve.OPC.Checkout.config.baseUrl + 'checkout/onepage/success',
			
			//DECORATE
			this.clearOnChange();
			this.removePrice();			
			
			//MAIN FUNCTION
			Abserve.OPC.Billing.init();
			Abserve.OPC.Shipping.init();	
			Abserve.OPC.initMessages();
			Abserve.OPC.initSaveOrder();
			
			
			if (this.config.isLoggedIn===1){
				var addressId = $j('#billing-address-select').val();
				if (addressId!='' && addressId!=undefined ){
					Abserve.OPC.Billing.save();
				}else{
					//FIX FOR MAGENTO 1.8 - NEED LOAD PAYTMENT METHOD BY AJAX
					Abserve.OPC.Checkout.pullPayments();
				}
			}else{
				//FIX FOR MAGENTO 1.8 - NEED LOAD PAYTMENT METHOD BY AJAX
				Abserve.OPC.Checkout.pullPayments();
			}
			
			Abserve.OPC.initPayment();
		},
		
		/** PARSE RESPONSE FROM AJAX SAVE BILLING AND SHIPPING METHOD **/
		prepareAddressResponse: function(response){
			Abserve.OPC.Checkout.xhr = null;
			
			if (typeof(response.error) != "undefined"){
				$j('.opc-message-container').html(response.message);
				$j('.opc-message-wrapper').show();
				Abserve.OPC.Checkout.hideLoader();
				Abserve.OPC.Checkout.unlockPlaceOrder();
				return;
			}
			
			/* Abserve ADDRESS VALIDATION  */
            if (typeof(response.address_validation) != "undefined"){
                $j('#checkout-address-validation-load').empty().html(response.address_validation);
                Abserve.OPC.Checkout.hideLoader();
                Abserve.OPC.Checkout.unlockPlaceOrder();
                return;
            }
			
			if (typeof(response.shipping) != "undefined"){
				$j('#shipping-block-methods').empty().html(response.shipping);
			}
			
			if (typeof(response.payments) != "undefined"){
				$j('#checkout-payment-method-load').empty().html(response.payments);
				
				Abserve.OPC.removeNotAllowedPaymentMethods();
				payment.initWhatIsCvvListeners();//default logic for view "what is this?"
			}
			
			if (typeof(response.isVirtual) != "undefined"){
				Abserve.OPC.Checkout.isVirtual = true;
			}
			
			if (Abserve.OPC.Checkout.isVirtual===false){
				var update_payments = false;
				if (typeof(response.reload_payments) != "undefined")
					update_payments = true;
				
				var reload_totals = false;
				if (typeof(response.reload_totals) != "undefined")
					reload_totals = true;
				
				Abserve.OPC.Shipping.saveShippingMethod(update_payments, reload_totals);
				
			}else{
				$j('.shipping-block').hide();
				$j('.payment-block').addClass('clear-margin');
				Abserve.OPC.Checkout.pullPayments();
			}
		},
		
		/** PARSE RESPONSE FROM AJAX SAVE SHIPPING METHOD **/
		prepareShippingMethodResponse: function(response){
			Abserve.OPC.Checkout.xhr = null;
			if (typeof(response.error)!="undefined"){
				
				Abserve.OPC.Checkout.hideLoader();
				Abserve.OPC.Checkout.unlockPlaceOrder();
				
				Abserve.OPC.Plugin.dispatch('error');
				
				$j('.opc-message-container').html(response.message);
				$j('.opc-message-wrapper').show();
				Abserve.OPC.saveOrderStatus = false;
				return;
			}
			
			if (typeof(response.review)!="undefined" && Abserve.OPC.saveOrderStatus===false){
				try{
					Abserve.OPC.Decorator.updateGrandTotal(response);
					$j('#opc-review-block').html(response.review);
				}catch(e){
					
				}
				Abserve.OPC.Checkout.removePrice();
				
//				Abserve.OPC.recheckAgree();
			}
			
			
			
			//IF STATUS TRUE - START SAVE PAYMENT FOR CREATE ORDER
			if (Abserve.OPC.saveOrderStatus==true){
				Abserve.OPC.validatePayment();
			}else{
				Abserve.OPC.Checkout.pullPayments();
			}
		},
		
		
		clearOnChange: function(){
			$j('.opc-col-left input, .opc-col-left select').removeAttr('onclick').removeAttr('onchange');
		},
		
		removePrice: function(){
			
			$j('.opc-data-table tr th:nth-child(2)').remove();
			$j('.opc-data-table tbody tr td:nth-child(2)').remove();
			$j('.opc-data-table tfoot td').each(function(){
				var colspan = $j(this).attr('colspan');
				
				if (colspan!="" && colspan !=undefined){
					colspan = parseInt(colspan) - 1;
					$j(this).attr('colspan', colspan);
				}
			});
			
			$j('.opc-data-table tfoot th').each(function(){
				var colspan = $j(this).attr('colspan');
				
				if (colspan!="" && colspan !=undefined){
					colspan = parseInt(colspan) - 1;
					$j(this).attr('colspan', colspan);
				}
			});
			
		},
		
		showLoader: function(){
			$j('.opc-ajax-loader').show();
			//$j('.opc-btn-checkout').addClass('button-disabled');
		},
		
		hideLoader: function(){
			setTimeout(function(){
				$j('.opc-ajax-loader').hide();
				//$j('.opc-btn-checkout').removeClass('button-disabled');				
			},600);
		},
		
		/** APPLY SHIPPING METHOD FORM TO BILLING FORM **/
		applyShippingMethod: function(form){
			formShippimgMethods = $j('#opc-co-shipping-method-form').serializeArray();
			$j.each(formShippimgMethods, function(index, data){
				form.push(data);
			});
			
			return form;
		},
		
		/** APPLY NEWSLETTER TO BILLING FORM **/
		applySubscribed: function(form){
			if ($j('#is_subscribed').length){
				if ($j('#is_subscribed').is(':checked')){
					form.push({"name":"is_subscribed", "value":"1"});
				}
			}
			
			return form;
		},
		
		/** PULL REVIEW **/
		pullReview: function(){
			Abserve.OPC.Checkout.lockPlaceOrder();
			Abserve.OPC.Checkout.xhr = $j.post(Abserve.OPC.Checkout.config.baseUrl + 'onepage/json/review',function(response){
				Abserve.OPC.Checkout.xhr = null;
				Abserve.OPC.Checkout.hideLoader();
				Abserve.OPC.Checkout.unlockPlaceOrder();
				if (typeof(response.review)!="undefined"){
					Abserve.OPC.Decorator.updateGrandTotal(response);
					$j('#opc-review-block').html(response.review);
					
					Abserve.OPC.Checkout.removePrice();
					
//					Abserve.OPC.recheckAgree();
				}
				Abserve.OPC.removeNotAllowedPaymentMethods();
			});
		},
		
		/** PULL PAYMENTS METHOD AFTER LOAD PAGE **/
		pullPayments: function(){
			Abserve.OPC.Checkout.lockPlaceOrder();
			Abserve.OPC.Checkout.xhr = $j.post(Abserve.OPC.Checkout.config.baseUrl + 'onepage/json/payments',function(response){
				Abserve.OPC.Checkout.xhr = null;
				
				if (typeof(response.error)!="undefined"){
					$j('.opc-message-container').html(response.error);
					$j('.opc-message-wrapper').show();
					Abserve.OPC.saveOrderStatus = false;
					Abserve.OPC.Checkout.hideLoader();
					Abserve.OPC.Checkout.unlockPlaceOrder();					
					return;
				}
				
				if (typeof(response.payments)!="undefined"){
					$j('#checkout-payment-method-load').html(response.payments);
					
					payment.initWhatIsCvvListeners();
					Abserve.OPC.bindChangePaymentFields();
					Abserve.OPC.Decorator.setCurrentPaymentActive();
				};
				
				Abserve.OPC.Checkout.pullReview();
				
			},'json');
		},
		
		lockPlaceOrder: function(mode){
			if(typeof(mode)=='undefined' || mode == undefined || !mode)
				mode = 0;
			if(mode == 0)
				$j('.opc-btn-checkout').addClass('button-disabled');
			Abserve.OPC.Checkout.disabledSave = true;
		},
		
		unlockPlaceOrder: function(){
			$j('.opc-btn-checkout').removeClass('button-disabled');
			Abserve.OPC.Checkout.disabledSave = false;
		},
	
		abortAjax: function(){
			if (Abserve.OPC.Checkout.xhr!=null){
				Abserve.OPC.Checkout.xhr.abort();
				
				Abserve.OPC.saveOrderStatus = false;
				Abserve.OPC.Checkout.hideLoader();
				Abserve.OPC.Checkout.unlockPlaceOrder();
			}
		},
		
		reloadShippingsPayments: function(form_type, delay){
			if(typeof(delay) == 'undefined' || delay == undefined || !delay)
				delay = 1400;
			
			clearTimeout(Abserve.OPC.Checkout.updateShippingPaymentProgress);
			
			Abserve.OPC.Checkout.updateShippingPaymentProgress = setTimeout(function(){
				
				var form = $j('#opc-address-form-'+form_type).serializeArray();
				form = Abserve.OPC.Checkout.applyShippingMethod(form);
				
				if (Abserve.OPC.Checkout.xhr2!=null)
					Abserve.OPC.Checkout.xhr2.abort();
				
				Abserve.OPC.Checkout.xhr2 = $j.post(Abserve.OPC.Checkout.config.baseUrl + 'onepage/json/reloadShippingsPayments',form, Abserve.OPC.Checkout.reloadShippingsPaymentsResponse,'json');
				
			}, delay);
			
		},
		
		reloadShippingsPaymentsResponse: function(response){
			
			Abserve.OPC.Checkout.xhr2 = null;
			
			if (typeof(response.error) != "undefined"){
				$j('.opc-message-container').html(response.message);
				$j('.opc-message-wrapper').show();
				Abserve.OPC.Checkout.hideLoader();
				Abserve.OPC.Checkout.unlockPlaceOrder();
				return;
			}
			
			if (typeof(response.shipping) != "undefined"){
				$j('#shipping-block-methods').empty().html(response.shipping);
			}
			
			if (typeof(response.payments) != "undefined"){
				
				if(response.payments != ''){
					$j('#checkout-payment-method-load').empty().html(response.payments);

					Abserve.OPC.removeNotAllowedPaymentMethods();
					payment.initWhatIsCvvListeners();//default logic for view "what is this?"
				}

				if (Abserve.OPC.Checkout.isVirtual===false){
					var update_payments = false;
					if (typeof(response.reload_payments) != "undefined")
						update_payments = true;

					Abserve.OPC.Shipping.saveShippingMethod(update_payments);
				}else{
					$j('.shipping-block').hide();
					$j('.payment-block').addClass('clear-margin');
					Abserve.OPC.Checkout.pullPayments();
				}
			}
			else{
				if(typeof(response.reload_totals) != "undefined")
					Abserve.OPC.Checkout.pullReview();
			}
		},
		
		checkRunReloadShippingsPayments: function(address_type){
			var zip = $j('#'+address_type+':postcode').val();
			var country = $j('#'+address_type+':country_id').val();
			var region = $j('#'+address_type+':region_id').val();
			
			if(zip != '' || country != '' || region != '')
				Abserve.OPC.Checkout.reloadShippingsPayments(address_type);
		}
};


Abserve.OPC.Billing = {
		bill_need_update: true,
		need_reload_shippings_payments: false,
		validate_timeout: false,
		
		init: function(){
			Abserve.OPC.Billing.bill_need_update = true;

			//set flag use billing for shipping and init change flag
			var use_for_ship = false;
			var el = $j('input[name="billing[use_for_shipping]"]');
			if(typeof(el) != 'undefined' && el != undefined && el){
				if(el.prop('type') == 'checkbox'){
					if(el.is(':checked'))
						use_for_ship = true;
				}
				else
					use_for_ship = true;
			}
			else
				use_for_ship = true;

			if(use_for_ship)
				this.setBillingForShipping(true);
			else
				this.setBillingForShipping(false, true);
			////
			
			$j('input[name="billing[use_for_shipping]"]').change(function(){
				if ($j(this).is(':checked')){
					Abserve.OPC.Billing.setBillingForShipping(true);
					$j('#opc-address-form-billing select[name="billing[country_id]"]').change();
					Abserve.OPC.Billing.need_reload_shippings_payments = 'billing';
					Abserve.OPC.Billing.validateForm();
				}else{
					Abserve.OPC.Billing.setBillingForShipping(false);
					Abserve.OPC.Billing.need_reload_shippings_payments = 'shipping';
					Abserve.OPC.Shipping.validateForm();
				}
			});
			
			
			//update password field
			$j('input[name="billing[create_account]"]').click(function(){
				if ($j(this).is(':checked')){
					$j('#register-customer-password').removeClass('hidden');
					$j('input[name="billing[customer_password]"]').addClass('required-entry');
					$j('input[name="billing[confirm_password]"]').addClass('required-entry');
				}else{
					$j('#register-customer-password').addClass('hidden');
					$j('input[name="billing[customer_password]"]').removeClass('required-entry');
					$j('input[name="billing[confirm_password]"]').removeClass('required-entry');
					$j('#register-customer-password input').val('');
				}
			});
			
			this.initChangeAddress();
			this.initChangeSelectAddress();
		},
		
		/** CREATE EVENT FOR UPDATE SHIPPING BLOCK **/
		initChangeAddress: function(){

			$j('#opc-address-form-billing input').blur(function(){
				if(Abserve.OPC.Billing.bill_need_update)
					Abserve.OPC.Billing.validateForm();
			});

			$j('#opc-address-form-billing').mouseleave(function(){
				if(Abserve.OPC.Billing.bill_need_update)
					Abserve.OPC.Billing.validateForm();
			});
			
			$j('#opc-address-form-billing input').keydown(function(){
				Abserve.OPC.Billing.bill_need_update = true;
				clearTimeout(Abserve.OPC.Checkout.ajaxProgress);
				Abserve.OPC.Checkout.abortAjax();
				
				// check if zip
				var el_id = $j(this).attr('id');
				if(el_id == 'billing:postcode')
					Abserve.OPC.Checkout.reloadShippingsPayments('billing');

				Abserve.OPC.Billing.validateForm(3000);
			});
			
			$j('#opc-address-form-billing select').not('#billing-address-select').change(function(){
				// check if country
				var el_id = $j(this).attr('id');
				if(el_id == 'billing:country_id' || el_id == 'billing:region_id')
					Abserve.OPC.Checkout.reloadShippingsPayments('billing', 800);
				
				Abserve.OPC.Billing.bill_need_update = true;
				Abserve.OPC.Billing.validateForm();
			});			
		},
		
		validateForm: function(delay){
			clearTimeout(Abserve.OPC.Billing.validate_timeout);
			if(typeof(delay) == 'undefined' || delay == undefined || !delay)
				delay = 100;
			
			Abserve.OPC.Billing.validate_timeout = setTimeout(function(){
				var mode = Abserve.OPC.Billing.need_reload_shippings_payment;
				Abserve.OPC.Billing.need_reload_shippings_payment = false;

				var valid = Abserve.OPC.Billing.validateAddressForm();
				if (valid){
					Abserve.OPC.Billing.save();
				}
				else{
					if(mode != false)
						Abserve.OPC.Checkout.checkRunReloadShippingsPayments(mode);
				}
			},delay);
		},
		
		
		/** CREATE EVENT FOR CHANGE ADDRESS TO NEW OR FROM ADDRESS BOOK **/
		initChangeSelectAddress: function(){
			$j('#billing-address-select').change(function(){
				if ($j(this).val()==''){
					$j('#billing-new-address-form').show();
				}else{
					$j('#billing-new-address-form').hide();
					Abserve.OPC.Billing.validateForm();
				}
			});
			
			
		},
		
		/** VALIDATE ADDRESS BEFORE SEND TO SAVE QUOTE**/
		validateAddressForm: function(form){
			// check all required fields not empty
			var is_empty = false;
			$j('#opc-address-form-billing .required-entry').each(function(){
				if($j(this).val() == '' && $j(this).css('display') != 'none' && !$j(this).attr('disabled'))
					is_empty = true;
			});
			if(is_empty)
				return false;
			////

			var addressForm = new Validation('opc-address-form-billing', { onSubmit : false, stopOnFirst : false, focusOnError : false});
			if (addressForm.validate()){				  		 
				return true;
			}else{				 
				return false;
			}
		},
		
		/** SET SHIPPING AS BILLING TO TRUE OR FALSE **/
		setBillingForShipping:function(useBilling, skip_copy){
			if (useBilling==true){
				$j('input[name="billing[use_for_shipping]"]').prop('checked', true);
				$j('input[name="shipping[same_as_billing]"]').prop('checked', true);
				$j('#opc-address-form-shipping').addClass('hidden');				
			}else{
				if(typeof(skip_copy) == 'undefined' || skip_copy == undefined)
					skip_copy = false
				if(!skip_copy)
					this.pushBilingToShipping();	
				$j('input[name="billing[use_for_shipping]"]').prop('checked', false);
				$j('input[name="shipping[same_as_billing]"]').prop('checked', false);
				$j('#opc-address-form-shipping').removeClass('hidden');
			}
			
		}, 
		
		/** COPY FIELD FROM BILLING FORM TO SHIPPING **/
		pushBilingToShipping:function(clearShippingForm){
			//pull country
			var valueCountry = $j('#billing-new-address-form select[name="billing[country_id]"]').val();
			$j('#opc-address-form-shipping  select[name="shipping[country_id]"] [value="' + valueCountry + '"]').prop("selected", true);	
			shippingRegionUpdater.update();
			
			
			//pull region id
			var valueRegionId = $j('#billing-new-address-form select[name="billing[region_id]"]').val();
			$j('#opc-address-form-shipping  select[name="shipping[region_id]"] [value="' + valueRegionId + '"]').prop("selected", true);
			
			//pull other fields	
			$j('#billing-new-address-form input').not(':hidden, :input[type="checkbox"]').each(function(){
				var name = $j(this).attr('name');
				var value = $j(this).val();
				var shippingName =  name.replace( /billing/ , 'shipping');
				
				$j('#opc-address-form-shipping input[name="'+shippingName+'"]').val(value);

			});
			
			//pull address field
			$j('#billing-new-address-form input[name="billing[street][]"]').each(function(indexBilling){
				var valueAddress = $j(this).val();
				$j('#opc-address-form-shipping input[name="shipping[street][]"]').each(function(indexShipping){
					if (indexBilling==indexShipping){
						$j(this).val(valueAddress);
					}
				});				
			});
			
			//init trigger change shipping form
			$j('#opc-address-form-shipping select[name="shipping[country_id]"]').change();
		},

		/** METHOD CREATE AJAX REQUEST FOR UPDATE BILLING ADDRESS **/
		save: function(){
			if (Abserve.OPC.Checkout.ajaxProgress!=false){
				clearTimeout(Abserve.OPC.Checkout.ajaxProgress);
			}

			// stop reload shippings/payments logic
			if (Abserve.OPC.Checkout.updateShippingPaymentProgress!=false)
				clearTimeout(Abserve.OPC.Checkout.updateShippingPaymentProgress);
			
			if (Abserve.OPC.Checkout.xhr2!=null)
				Abserve.OPC.Checkout.xhr2.abort();
			////
			
			Abserve.OPC.Checkout.ajaxProgress = setTimeout(function(){
					var form = $j('#opc-address-form-billing').serializeArray();
					form = Abserve.OPC.Checkout.applyShippingMethod(form);					
					form = Abserve.OPC.Checkout.applySubscribed(form); 
					
					if (Abserve.OPC.Checkout.xhr!=null){
						Abserve.OPC.Checkout.xhr.abort();
					}
					
					if($j('input[name="billing[use_for_shipping]"]').is(':checked'))
						Abserve.OPC.Checkout.showLoader();
					else
						Abserve.OPC.Checkout.lockPlaceOrder(1);
					
					Abserve.OPC.Billing.bill_need_update = false;		
					Abserve.OPC.Checkout.xhr = $j.post(Abserve.OPC.Checkout.config.baseUrl + 'onepage/json/saveBilling',form, Abserve.OPC.Checkout.prepareAddressResponse,'json');
			}, 500);
		},
		
};

Abserve.OPC.Shipping = {
		ship_need_update: true,
		validate_timeout: false,
		
		init: function(){
			Abserve.OPC.Shipping.ship_need_update = true;
			this.initChangeAddress();
			this.initChangeSelectAddress();
			this.initChangeShippingMethod();
		},

		/** CREATE EVENT FOR UPDATE SHIPPING BLOCK **/
		initChangeAddress: function(){
			
			$j('#opc-address-form-shipping input').blur(function(){
				if(Abserve.OPC.Shipping.ship_need_update)
					Abserve.OPC.Shipping.validateForm();
			});

			$j('#opc-address-form-shipping').mouseleave(function(){
				if(Abserve.OPC.Shipping.ship_need_update)
					Abserve.OPC.Shipping.validateForm();
			});
			
			$j('#opc-address-form-shipping input').keydown(function(){
				Abserve.OPC.Shipping.ship_need_update = true;
				clearTimeout(Abserve.OPC.Checkout.ajaxProgress);
				Abserve.OPC.Checkout.abortAjax();

				// check if zip
				var el_id = $j(this).attr('id');
				if(el_id == 'shipping:postcode')
					Abserve.OPC.Checkout.reloadShippingsPayments('shipping');

				Abserve.OPC.Shipping.validateForm(3000);
				
			});
			
			$j('#opc-address-form-shipping select').not('#shipping-address-select').change(function(){
				// check if country
				var el_id = $j(this).attr('id');
				if(el_id == 'shipping:country_id' || el_id == 'shipping:region_id')
					Abserve.OPC.Checkout.reloadShippingsPayments('shipping', 800);
				
				Abserve.OPC.Shipping.ship_need_update = true;
				Abserve.OPC.Shipping.validateForm();
			});
		},
		
		/** CREATE VENT FOR CHANGE ADDRESS TO NEW OR FROM ADDRESS BOOK **/
		initChangeSelectAddress: function(){
			$j('#shipping-address-select').change(function(){
				if ($j(this).val()==''){
					$j('#shipping-new-address-form').show();
				}else{
					$j('#shipping-new-address-form').hide();
					Abserve.OPC.Shipping.validateForm();
				}
			});
			
			
		},
		
		//create observer for change shipping method. 
		initChangeShippingMethod: function(){
			$j('.opc-wrapper-opc #shipping-block-methods').on('change', 'input[type="radio"]', function(){
				Abserve.OPC.Shipping.saveShippingMethod();
			});
		},
		
		validateForm: function(delay){
			clearTimeout(Abserve.OPC.Shipping.validate_timeout);
			if(typeof(delay) == 'undefined' || delay == undefined || !delay)
				delay = 100;
			
			Abserve.OPC.Shipping.validate_timeout = setTimeout(function(){
				var mode = Abserve.OPC.Billing.need_reload_shippings_payment;
				Abserve.OPC.Billing.need_reload_shippings_payment = false;

				var valid = Abserve.OPC.Shipping.validateAddressForm();
				if (valid){
					Abserve.OPC.Shipping.save();
				}
				else{
					if(mode != false)
						Abserve.OPC.Checkout.checkRunReloadShippingsPayments(mode);
				}
			},delay);
		},
		
		/** VALIDATE ADDRESS BEFORE SEND TO SAVE QUOTE**/
		validateAddressForm: function(form){
			// check all required fields not empty
			var is_empty = false;
			$j('#opc-address-form-shipping .required-entry').each(function(){
				if($j(this).val() == '' && $j(this).css('display') != 'none' && !$j(this).attr('disabled'))
					is_empty = true;
			});
			
			if(is_empty)
				return false;
			////
			
			var addressForm = new Validation('opc-address-form-shipping', { onSubmit : false, stopOnFirst : false, focusOnError : false});
			if (addressForm.validate()){				  		 
				return true;
			}else{				 
				return false;
			}
		},
		
		/** METHOD CREATE AJAX REQUEST FOR UPDATE SHIPPIN METHOD **/
		save: function(){
			if (Abserve.OPC.Checkout.ajaxProgress!=false){
				clearTimeout(Abserve.OPC.Checkout.ajaxProgress);
			}
			
			// stop reload shippings/payments logic
			if (Abserve.OPC.Checkout.updateShippingPaymentProgress!=false)
				clearTimeout(Abserve.OPC.Checkout.updateShippingPaymentProgress);
			
			if (Abserve.OPC.Checkout.xhr2!=null)
				Abserve.OPC.Checkout.xhr2.abort();
			////
			
			Abserve.OPC.Checkout.ajaxProgress = setTimeout(function(){
					var form = $j('#opc-address-form-shipping').serializeArray();
					form = Abserve.OPC.Checkout.applyShippingMethod(form);
					if (Abserve.OPC.Checkout.xhr!=null){
						Abserve.OPC.Checkout.xhr.abort();
					}
					Abserve.OPC.Checkout.lockPlaceOrder(1);
					
					Abserve.OPC.Shipping.ship_need_update = false;
					Abserve.OPC.Checkout.xhr = $j.post(Abserve.OPC.Checkout.config.baseUrl + 'onepage/json/saveShipping',form, Abserve.OPC.Checkout.prepareAddressResponse,'json');
			}, 500);
		},
		
		saveShippingMethod: function(update_payments, reload_totals){
			
			if (Abserve.OPC.Shipping.validateShippingMethod()===false){

				if (Abserve.OPC.saveOrderStatus){	
					$j('.opc-message-container').html($j('#pssm_msg').html());
					$j('.opc-message-wrapper').show();
				}
				Abserve.OPC.saveOrderStatus = false;
					
				Abserve.OPC.Checkout.hideLoader();
				
				if(typeof(update_payments) != 'undefined' && update_payments != undefined && update_payments) // if was request to reload payments
					Abserve.OPC.Checkout.pullPayments();
				else{
					if(typeof(reload_totals) == 'undefined' || reload_totals == undefined)
						reload_totals = false;
					
					if(reload_totals)
						Abserve.OPC.Checkout.pullReview();
					else
						Abserve.OPC.Checkout.unlockPlaceOrder();
				}
				
				return;
			}
			
			if (Abserve.OPC.Checkout.ajaxProgress!=false){
				clearTimeout(Abserve.OPC.Checkout.ajaxProgress);
			}
			
			Abserve.OPC.Checkout.ajaxProgress = setTimeout(function(){
				var form = $j('#opc-co-shipping-method-form').serializeArray();
				form = Abserve.OPC.Checkout.applySubscribed(form); 
				if (Abserve.OPC.Checkout.xhr!=null){
					Abserve.OPC.Checkout.xhr.abort();
				}
				Abserve.OPC.Checkout.showLoader();
				Abserve.OPC.Checkout.xhr = $j.post(Abserve.OPC.Checkout.config.baseUrl + 'onepage/json/saveShippingMethod',form, Abserve.OPC.Checkout.prepareShippingMethodResponse);
			}, 600);
		},
		
		validateShippingMethod: function(){			
			var shippingChecked = false;
			$j('#opc-co-shipping-method-form input').each(function(){				
				if ($j(this).prop('checked')){							
					shippingChecked =  true;
				}
			});
			
			return shippingChecked;
		}		
};


Abserve.OPC.Coupon = {
		init: function(){
			
			$j(document).on('click', '.apply-coupon', function(){
				Abserve.OPC.Coupon.applyCoupon(false);
			});
			
			
			$j(document).on('click', '.remove-coupon', function(){
				Abserve.OPC.Coupon.applyCoupon(true);
			});
			
			
			$j(document).on('click','.discount-block h3', function(){
				if ($j(this).hasClass('open-block')){
					$j(this).removeClass('open-block');
					$j(this).next().addClass('hidden');
				}else{
					$j(this).addClass('open-block');					
					$j(this).next().removeClass('hidden');
				}
			});
			
		},
		
		applyCoupon: function(remove){
			
			var form = $j('#opc-discount-coupon-form').serializeArray();
			if (remove===false){				
				form.push({"name":"remove", "value":"0"});
			}else{
				form.push({"name":"remove", "value":"1"});
			}
			
			Abserve.OPC.Checkout.showLoader();
			Abserve.OPC.Checkout.xhr = $j.post(Abserve.OPC.Checkout.config.baseUrl + 'onepage/coupon/couponPost',form, Abserve.OPC.Coupon.prepareResponse,'json');
		},
		
		prepareResponse: function(response){
			Abserve.OPC.Checkout.xhr = null;
			Abserve.OPC.Checkout.hideLoader();
			if (typeof(response.message) != "undefined"){
				$j('.opc-message-container').html(response.message);
				$j('.opc-message-wrapper').show();
				
				Abserve.OPC.Checkout.pullReview();
			}
			if (typeof(response.coupon) != "undefined"){
				$j('#opc-discount-coupon-form').replaceWith(response.coupon).show();				
				$j('#opc-discount-coupon-form').show();
			}
			if (typeof(response.payments)!="undefined"){
				$j('#checkout-payment-method-load').html(response.payments);
				
				Abserve.OPC.removeNotAllowedPaymentMethods();
				
				payment.initWhatIsCvvListeners();
				Abserve.OPC.bindChangePaymentFields();
			};			
		}
};

Abserve.OPC.Comment = {
		init: function(){
			
			$j(document).on('click','.comment-block h3', function(){
				if ($j(this).hasClass('open-block')){
					$j(this).removeClass('open-block');
					$j(this).next().addClass('hidden');
				}else{
					$j(this).addClass('open-block');					
					$j(this).next().removeClass('hidden');
				}
			});
		}
};
			
Abserve.OPC.SignatureAtCheckout = {
    init: function(){
        $j(document).on('click','.signature-block h3', function(){
            if ($j(this).hasClass('open-block')){
                $j(this).removeClass('open-block');
                $j(this).next().addClass('hidden');
            }else{
                $j(this).addClass('open-block');
                $j(this).next().removeClass('hidden');
            }
        });

    }
};

Abserve.OPC.Agreement ={
		
		init: function(){
			
			$j(document).on('click', '.view-agreement', function(e){
				e.preventDefault();
				$j('.opc-review-actions #modal-agreement').addClass('md-show');
				
				var id = $j(this).data('id');
				var title = $j(this).html();
				var content = $j('.opc-review-actions #agreement-block-'+id).html();
				
				$j('.opc-review-actions #agreement-title').html(title);
				$j('.opc-review-actions #agreement-modal-body').html(content);
			});
			
			$j(document).on('click', '#checkout-agreements input[name*="agreement"]', function(){
				var cur_el = $j(this);
				$j('#checkout-agreements input').each(function(){
					
					if(cur_el.prop('name') == $j(this).prop('name')){
						$j(this).prop('checked', cur_el.prop('checked'));
					}
				});
				
				// save agreements statuses
				Abserve.OPC.agreements = $j('#checkout-agreements').serializeArray();
			});
		}
};

Abserve.OPC.Login ={
		
		init: function(){
			$j('.login-trigger').click(function(e){
				e.preventDefault();
				$j('#modal-login').addClass('md-show');
			});
			
			$j(document).on('click','.md-modal .close', function(e){
				e.preventDefault();
				$j('.md-modal').removeClass('md-show');
			});
			
			$j(document).on('click', '.restore-account', function(e){
				e.preventDefault();
				$j('#login-form').hide();$j('#login-button-set').hide();
				$j('#form-validate-email').fadeIn();$j('#forgotpassword-button-set').show();
			});
			
			
			$j('#login-button-set .btn').click(function(){
				$j('#login-form').submit();
			});
			
			$j('#forgotpassword-button-set .btn').click(function(){
				var form = $j('#form-validate-email').serializeArray();
				Abserve.OPC.Checkout.showLoader();
				Abserve.OPC.Checkout.xhr = $j.post(Abserve.OPC.Checkout.config.baseUrl + 'onepage/json/forgotpassword',form, Abserve.OPC.Login.prepareResponse,'json');
			});
			
			
			$j('#forgotpassword-button-set .back-link').click(function(e){
				e.preventDefault();
				$j('#form-validate-email').hide();$j('#forgotpassword-button-set').hide();
				$j('#login-form').fadeIn();$j('#login-button-set').show();
				
			});
			
		},
		
		prepareResponse: function(response){
			Abserve.OPC.Checkout.xhr = null;
			Abserve.OPC.Checkout.hideLoader();
			if (typeof(response.error)!="undefined"){
				alert(response.message);
			}else{
				alert(response.message);
				$j('#forgotpassword-button-set .back-link').click();
			}
		}
};

Abserve.OPC.Decorator = {
		initReviewBlock: function(){
			$j('a.review-total').click(function(){
				if ($j(this).hasClass('open')){
					$j(this).removeClass('open')
					$j('#opc-review-block').addClass('hidden');
				}else{
					$j(this).addClass('open')
					$j('#opc-review-block').removeClass('hidden');
				}
			});
		},
		updateGrandTotal: function(response){
			$j('.opc-review-actions h5 span').html(response.grandTotal);
			$j('.review-total span').html(response.grandTotal);
		},
		
		setActivePayment: function(){
			//check and setup current active method 
			this.setCurrentPaymentActive();
			
			$j(document).on('click','#checkout-payment-method-load dt', function(){
				$j('#checkout-payment-method-load dt').removeClass('active');
				$j(this).addClass('active');
			});
		},
		
		setCurrentPaymentActive: function(){
			var method = payment.currentMethod;
			$j('#p_method_'+method).parent().addClass('active');
		}
};

$j(document).ready(function(){
	Abserve.OPC.Checkout.init();
	Abserve.OPC.Coupon.init();
	Abserve.OPC.Comment.init();
	Abserve.OPC.Agreement.init();
	Abserve.OPC.Login.init();
	Abserve.OPC.Decorator.initReviewBlock();
	Abserve.OPC.Decorator.setActivePayment();
});
