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

});