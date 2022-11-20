// CATEGORY SLIDER START 
    $(".slick-slider").slick({
        dots: false,
        arrows: true,
        autoplay: true,
        autoplaySpeed: 3000,
        slidesToShow: 7,
        slidesToScroll: 1,
      
        responsive: [
          {
            breakpoint: 1399,
            settings: {
              slidesToShow: 6,
              slidesToScroll: 1,
            },
          },
          {
            breakpoint: 1080,
            settings: {
              slidesToShow: 4,
              slidesToScroll: 1,
            },
          },
          {
            breakpoint: 780,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 1,
            },
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
            },
          },
        ],
      });



// CATEGORY SLIDER END  

// BANNER SLIDER START 
    $(".slick_banner").slick({
        dots: false,
        arrows: true,
        autoplay: true,
        autoplaySpeed: 3000,
        slidesToShow: 3,
        slidesToScroll: 1,
      
        responsive: [
          {
            breakpoint: 1399,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 1,
            },
          },
          {
            breakpoint: 1080,
            settings: {
              slidesToShow: 4,
              slidesToScroll: 1,
            },
          },
          {
            breakpoint: 780,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 1,
            },
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
            },
          },
        ],
      });



// BANNER SLIDER END     

// MOBILE SEARCH START 
$(document).ready(function() {
  $('.search_icon_new').click(function() {
    console.log('clicked');
    $('.sub_search').css("opacity", "1");
    $('.search_icon_new').hide();
  });



});

// MOBILE SEARCH END 

// HEADER STICKY 

window.addEventListener("scroll", function () {
  var header = document.querySelector(".header");
  header.classList.toggle("sticky-bar", window.scrollY > 50);
});