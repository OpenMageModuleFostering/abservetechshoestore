jQuery(document).ready(function() {

//  ********* Newproduct ********* //

  var newproduct = jQuery(".product-carousel .products-grid");
  if ((newproduct).length != 0){
    newproduct.owlCarousel({
        items : 6,
        autoPlay: true,
        lazyLoad: true
    });
    // Custom Navigation Events
    jQuery(".product-carousel .customNavigation .next").click(function(){
      newproduct.trigger('owl.next');
    })
    jQuery(".product-carousel .customNavigation .prev").click(function(){
      newproduct.trigger('owl.prev');
    })
  }

//  ********* End Newproduct ********* //

//  ********* categorylist ********* //

  var categorylist = jQuery(".category-carousel .products-grid");
  if ((categorylist).length != 0){
    categorylist.owlCarousel({
        items : 6,
        autoPlay: true,
        lazyLoad: true
    });
    // Custom Navigation Events
    jQuery(".category-carousel .customNavigation .next").click(function(){
      categorylist.trigger('owl.next');
    })
    jQuery(".category-carousel .customNavigation .prev").click(function(){
      categorylist.trigger('owl.prev');
    })
  }

//  ********* End categorylist ********* //

//  ********* Banner ********* //   

  jQuery("#owl-demo").owlCarousel({     
    navigation : false, // Show next and prev buttons
    slideSpeed : 300,
    paginationSpeed : 400,
    singleItem:true,
    autoPlay: true,
    pagination: false
  });

//  ********* End Banner ********* //   

  jQuery('.new-products .title h2').appear(function() {
    jQuery('.new-products .title h2').addClass('animated rotateIn');  
  });

  jQuery('.category-products .title h2').appear(function() {
    jQuery('.category-products .title h2').addClass('animated rotateIn');  
  });

  jQuery('.blog_container .title h2').appear(function() {
    jQuery('.blog_container .title h2').addClass('animated rotateIn');  
  });

  jQuery('.brand-products .title h2').appear(function() {
    jQuery('.brand-products .title h2').addClass('animated rotateIn');  
  });

  jQuery('.new-products .title p').appear(function() {
    jQuery('.new-products .title p').addClass('animated zoomIn');  
  });

  jQuery('.category-products .title p').appear(function() {
    jQuery('.category-products .title p').addClass('animated zoomIn');  
  });

  jQuery('.blog_container .title p').appear(function() {
    jQuery('.blog_container .title p').addClass('animated zoomIn');  
  });

  jQuery('.brand-products .title p').appear(function() {
    jQuery('.brand-products .title p').addClass('animated zoomIn');  
  });
  
  jQuery('.new-products .products-grid .item').appear(function() {
    jQuery('.new-products .products-grid .item').addClass('animated flipInY');  
  });

  jQuery('.brand_img').appear(function() {
    jQuery('.brand_img').addClass('animated flipInY');  
  });

  jQuery('.category-products .products-grid .item').appear(function() {
    jQuery('.category-products .products-grid .item').addClass('animated flipInY');  
  });

  jQuery('.blog_left').appear(function() {
    jQuery('.blog_left').addClass('animated bounceInLeft');  
  });

  jQuery('.blog_right').appear(function() {
    jQuery('.blog_right').addClass('animated bounceInRight');  
  });

  jQuery('.call').appear(function() {
    jQuery('.call').addClass('animated slideInLeft');  
  });

  jQuery('.subscribe').appear(function() {
    jQuery('.subscribe').addClass('animated slideInRight');  
  });

  jQuery('.chat').appear(function() {
    jQuery('.chat').addClass('animated zoomIn');  
  });

  jQuery('.advertisement_block1').appear(function() {
    jQuery('.advertisement_block1').addClass('animated slideInLeft');  
  });

  jQuery('.advertisement_block3').appear(function() {
    jQuery('.advertisement_block3').addClass('animated slideInRight');  
  });

  jQuery('.advertisement_block2').appear(function() {
    jQuery('.advertisement_block2').addClass('animated zoomIn');  
  });

  jQuery('#tabs li').appear(function() {
    jQuery('#tabs li').addClass('animated zoomIn');  
  });

  jQuery('.services i').appear(function() {
    jQuery('.services i').addClass('animated tada');  
  });

});