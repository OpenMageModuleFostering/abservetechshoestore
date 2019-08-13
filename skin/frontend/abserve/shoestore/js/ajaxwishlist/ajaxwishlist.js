function ajaxCompare(url,id){
	url = url.replace("catalog/product_compare/add","ajaxwishlist/index/compare");
	url += 'isAjax/1/';
	jQuery('#ajax_loading'+id).show();
	jQuery.ajax( {
		url : url,
		dataType : 'json',
		success : function(data) {
			jQuery('#ajax_loading'+id).hide();
			if(data.status == 'ERROR'){
				jQuery("body").append('<ul class="messages msg_notification fixedmsg" style="margin-top: -30px ; left: 50% ; margin-left: -195px ; text-align: center ; top: 50% ; width: 100% ; max-width: 406px ; position: fixed;"><li class="notice-msg"><ul><li><span>'+data.message+'.</span></li></ul></li></ul>');
				/*setTimeout(function() {
			        jQuery(".msg_notification").hide('blind', {}, 3000) 
			    }, 1000);*/

				setTimeout(function() {
			        jQuery(".msg_notification").hide() 
			    }, 1000);

 				/*setTimeout(function() {
			         jQuery(".msg_notification").hide()
			         jQuery('.msg_notification').fadeOut(1000);  
			    }, 1000);*/

			}else{
				jQuery("body").append('<ul class="messages msg_notification fixedmsg" style="margin-top: -30px ; left: 50% ; margin-left: -195px ; text-align: center ; top: 50% ; width: 100% ; max-width: 406px ; position: fixed;"><li class="success-msg"><ul><li><span>'+data.message+'.</span></li></ul></li></ul>');				
				if(jQuery('.block-compare').length){
                    jQuery('.block-compare').replaceWith(data.sidebar);
                }else{
                    if(jQuery('.col-right').length){
                    	jQuery('.col-right').prepend(data.sidebar);
                    }
                }
       //          setTimeout(function() {
			    //     jQuery(".msg_notification").hide('blind', {}, 3000) 
			    // }, 1000);

 				/*setTimeout(function() {
			         jQuery(".msg_notification").hide()
			          jQuery('.msg_notification').fadeOut(1000); 
			    }, 1000);*/

				setTimeout(function() {
			        jQuery(".msg_notification").hide() 
			    }, 1000);
				
			}
		}
	});
}
function ajaxWishlist(url,id){
	url = url.replace("wishlist/index","ajaxwishlist/index");
	url += 'isAjax/1/';
	jQuery('#ajax_loading'+id).show();
	jQuery.ajax( {
		url : url,
		dataType : 'json',
		success : function(data) {
			jQuery('#ajax_loading'+id).hide();
			if(data.status == 'ERROR'){
				jQuery("body").append('<ul class="messages msg_notification fixedmsg" style="margin-top: -30px ; left: 50% ; margin-left: -195px ; text-align: center ; top: 50% ; width: 100% ; max-width: 406px ; position: fixed;"><li class="notice-msg"><ul><li><span>'+data.message+'.</span></li></ul></li></ul>');
				// setTimeout(function() {
			 //        jQuery(".msg_notification").hide('blind', {}, 3000) 
			 //    }, 1000);

 				/*setTimeout(function() {
			         jQuery(".msg_notification").hide()
			          jQuery('.msg_notification').fadeOut(1000); 
			    }, 1000);*/
				setTimeout(function() {
			        jQuery(".msg_notification").hide() 
			    }, 1000);

			}else{
				//alert(data.message);
				jQuery("body").append('<ul class="messages msg_notification fixedmsg"  style="margin-top: -30px ; left: 50% ; margin-left: -195px ; text-align: center ; top: 50% ; width: 100% ; max-width: 406px ; position: fixed;"><li class="success-msg"><ul><li><span>'+data.message+'.</span></li></ul></li></ul>');
				if(jQuery('.block-wishlist').length){
                    jQuery('.block-wishlist').replaceWith(data.sidebar);
                }else{
                    if(jQuery('.col-right').length){
                    	jQuery('.col-right').prepend(data.sidebar);
                    }
                }                
                /*setTimeout(function() {
			         jQuery(".msg_notification").hide()
			          jQuery('.msg_notification').fadeOut(1000); 
			    }, 1000);*/
				setTimeout(function() {
			        jQuery(".msg_notification").hide() 
			    }, 1000);
				
			}
		}
	});
}