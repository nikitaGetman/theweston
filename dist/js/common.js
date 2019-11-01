// footer
$(window).on('load', function(){
var hh = $('header').height();
var fh = $('footer').height();
var wh = $(window).height();
var сh = wh - hh - fh; 
$('main').css('min-height', сh);
});

$(window).on('load', function () { 
	$('#preload').find(); 
	$('#preload').fadeOut();
});


// header fixed
$(window).scroll(function(){
  if ($(this).scrollTop() > 350) {
      $('header').addClass('fixed header-inner');
      $('.header-fix-band').addClass('fixed');
	}
   else {
      $('header').removeClass('fixed');
      $('.header-fix-band').removeClass('fixed');
      if ($('header').hasClass('header-main')) {
      	$('header').removeClass('header-inner');
      }
  }
});






$(document).on('keypress', function(evt) {
  if(evt.isDefaultPrevented()) {
    $(evt.target).trigger('input');
  }
});
// fancy owl fix
$('.owl-next').click(function() {
    owl.trigger('next.owl.carousel');
})
// Go to the previous item
$('.customPrevBtn').click(function() {
    owl.trigger('prev.owl.carousel', [300]);
})

$(function(){ // Mask-Input
	$.mask.definitions['~']='[1-79]'
	$(".phone").mask("+7 (~99) 999-99-99");
});





$(document).ready(function() {

// svg

svg4everybody({
validate: function (src, svg, use) {
	return true;
	}
});

// lazyload

$(function() {
    $('.lazy').lazy({
      effect: "fadeIn",
      effectTime: 500,
      threshold: 0
    });
});

/* Mask-Input */

$('.phone').on('focusout', function(){
	var chars = $(this).val();
	var number = chars.match( /\d/g );
	number = number ? number = number.length : 0;
	if(number < 11){
		$(this).removeClass('is-active');
	}
});
$('.txt-input .input-clear').click(function(){
	$(this).siblings('.txt-input__field').val('');
	$(this).siblings('.txt-input__field').removeClass('is-active');
	$(this).siblings('.txt-input__field').removeClass('parsley-success');
	$(this).parents('.txt-input').removeClass('is-active');
	return false;
});
$('.txt-input input, .txt-input, .txt-input textarea').on('focusin', function(){
	$(this).addClass('is-active');

});
$('.txt-input input, .txt-input, .txt-input textarea').on('focusout', function(){
	if(!$(this).val()){
		$(this).removeClass('is-active');
	}
});

// fancybox

$().fancybox({
    selector : '.owl-item:not(.cloned) .gallery-item'
});

});

// Animations
$( window ).on('load', function() {
	if ($(window).width() > 1024) {
		// inView
		$('.main-slide__slider').on('inview', function() {
		  $('.main-slide-item__info').addClass('animated fadeInLeft');
		});
		$('.project-section').on('inview', function() {
		  $('.projects-block__heading, .projects-block__text').addClass('animated fadeInLeft');
		});
		$('.project-slide__info').on('inview', function() {
		  $(this).addClass('animated fadeInUp');
		});
		$('.profile-block').on('inview', function() {
		  $(this).addClass('animated fadeIn slow');
		});
		$('.statistic-block__heading').on('inview', function() {
		  $(this).addClass('animated fadeInLeft faster');
		});
		$('.articles-block, .articles-slide__slider').on('inview', function() {
		  $(this).addClass('animated fadeIn slow');
		});
  	}

});
