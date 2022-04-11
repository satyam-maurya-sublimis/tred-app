var width = $(window). width();


// Preloader
$(window).on('load', function() {
if ($('#preloader').length) {
    $('#preloader').delay(100).fadeOut('slow', function() {
    $(this).remove();
    });
}
//theme.initAnimation();
});

$(window).scroll(function() {
    if ($(this).scrollTop() > 450) {
        $('.call-action').addClass('sticky');

    } else {
        $('.call-action').removeClass('sticky');
    }
});

$(window).scroll(function() {
    if ($(this).scrollTop() > 550) {
        $('.fliter-by').addClass('filterFixed');
        $('#secondary-nav').addClass('fixednav');

    } else {
        $('.fliter-by').removeClass('filterFixed');
        $('#secondary-nav').removeClass('fixednav');
    }
});
$(window).scroll(function() {
    if ($(this).scrollTop() > 750) {
        $('#secondary-nav2').addClass('fixednav');
    } else {
        $('#secondary-nav2').removeClass('fixednav');
    }
    if(width < 767) {
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $('#secondary-nav2').addClass('fixednav');
            } else {
                $('#secondary-nav2').removeClass('fixednav');
            }
        });
        $(window).scroll(function() {
            if ($(this).scrollTop() > 150) {
                $('#secondary-nav').addClass('fixednav');
            } else {
                $('#secondary-nav').removeClass('fixednav');
            }
        });
    }
});

$(window).scroll(function() {
    if ($(this).scrollTop() > 50) {
        $('.flat-dtls-bar').addClass('fixedtop');
        $('.form-sec').addClass('fixedtop');


    } else {
         $('.flat-dtls-bar').removeClass('fixedtop');
         $('.form-sec').removeClass('fixedtop');
         
    }
});


$(window).scroll(function() {
    if ($(this).scrollTop() > 90) {
        $('header').addClass('fixedHeader');
        $('.top-filter').addClass('top-filter-fixed');


    } else {
        $('header').removeClass('fixedHeader');
        $('.top-filter').removeClass('top-filter-fixed');
        $('body').removeClass('wrapper-top');
    }
});

$(document).ready(function() {
    $(".btn-support").addClass('fixed-buttons');
    $('.btn-support').addClass('scroll-buttons');
});


$(window).scroll(function() {
    if ($(this).scrollTop() >= 0) {
        $('.btn-support').addClass('scroll-buttons');
    } else {
        $('.btn-support').removeClass('scroll-buttons');

    }
});

$(window).bind('scroll', function() {
    if($(window).scrollTop() >= $('footer').offset().top + $('footer').outerHeight() - 200 - window.innerHeight) {
        $('.btn-support').addClass('stop');
        $('.filterFixed').addClass('stop');
        $('.back-to-top').css({"bottom":"10px"});
        //$('.flat-dtls-bar, .rhs-sec').removeClass('fixedtop');
		$('.form-sec').removeClass('fixedtop');
        //$('.rhs-sec').removeClass('fixedtop');
        $('#secondary-nav').removeClass('scrollToTop');
    }else {
        $('.btn-support').removeClass('stop');
        $('.filterFixed').removeClass('stop');
        if(width > 450){
            $('.back-to-top').css({"bottom":"5px"});
        }
        else {
            $('.back-to-top').css({"bottom":"43px", "right":"5px"});


        }
    }
});




// Back to top button
$(window).scroll(function() {
    if ($(this).scrollTop() > 100) {
        $('.back-to-top').fadeIn('slow');
    } else {
        $('.back-to-top').fadeOut('slow');
    }
});
$(".navbar-toggler").click(function() {
    $(this).toggleClass("on");
    $("#menu").slideToggle();
});

$('.back-to-top').click(function() {
    $('html, body').animate({
        scrollTop: 0
    }, 1500, 'easeInOutExpo');
    return false;
});






var theme = function() {
    function handlePreventEmptyLinks() {
        $('a[href=#]').click(function(event) {
            event.preventDefault();
        });
    }
    return {
        onResize: function() {
            resizePage();
        },
        initAnimation: function() {
            var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            if (isMobile == false) {
                $('*[data-animation]').addClass('animated');
                $('*[data-animation]').addClass('animated');
                $('.animated').waypoint(function(down) {
                    var elem = $(this);
                    var animation = elem.data('animation');
                    if (!elem.hasClass('visible')) {
                        var animationDelay = elem.data('animation-delay');
                        if (animationDelay) {
                            setTimeout(function() {
                                elem.addClass(animation + ' visible');
                            }, animationDelay);
                        } else {
                            elem.addClass(animation + ' visible');
                        }
                    }
                }, {
                    offset: $.waypoints('viewportHeight') - 60
                });
            }
        }
    }
}();

/******Menu Hover******/




/******Menu Hover******/

$(".footer-toggler .footer-links-btn").click(function() {
    $(this).toggleClass("footer-hide");
    $(".footer-links").slideToggle();
});
// $(window).scroll(function() {
//     if ($(this).scrollTop() > 550) {
//         $('.search_box').addClass('sticky');
//     } else {
//         $('.search_box').removeClass('sticky');
//     }
// });

if(width < 767) {
    $(window).scroll(function() {
        if ($(this).scrollTop() > 550) {
            $('.search_box').removeClass('sticky');
        } else {
            $('.search_box').removeClass('sticky');
        }
    });
    $('.nav-item').click(function(){
        $(this).parents().find('.search_box').addClass('open');
    });
    $('.nav-item.nav-link').removeClass('active');
    // $('.nav-item').removeClass('active');

    $('#call-back').click(function(){
        $(this).parents().find('.form-sec').addClass('open');
    })
    $('.close-icon').click(function(){
        $(this).parents().find('.form-sec').removeClass('open');
    })
    $('#close-search').click(function(){
        $(this).parent().removeClass('open');
    })

    // $(window).scroll(function() {
    //     if ($(this).scrollTop() > 550) {
    //         $('#secondary-nav2').addClass('fixednav');
    //     } else {
    //         $('#secondary-nav2').removeClass('fixednav');
    //     }
    // });
    // $(window).scroll(function() {
    //     if ($(this).scrollTop() > 100) {
    //         alert('kkk');
    //         $('#secondary-nav').addClass('fixednav');
    //     } else {
    //         $('#secondary-nav').removeClass('fixednav');
    //     }
    // });
}
if(width < 991) {

    $(".menu").click(function(e) {
        e.preventDefault();
        $(this).toggleClass('open');
        $(this).siblings(".main-nav").toggleClass("menu-open");
        $('body').toggleClass("menu-open");
        /*setTimeout(function(){
          $('body').toggleClass("menu-open");
        },500)*/
    });
    // $(window).scroll(function() {
    //     if ($(this).scrollTop() > 300) {
    //         $('#secondary-nav').addClass('fixednav');
    //     } else {
    //         $('#secondary-nav').removeClass('fixednav');
    //     }
    // });
    // $(window).scroll(function() {
    //     if ($(this).scrollTop() > 30) {
    //         $('.flat-dtls-bar, .rhs-sec').addClass('fixedtop');


    //     } else {
    //          $('.flat-dtls-bar, .rhs-sec').removeClass('fixedtop');

    //     }
    // });


}
$(document).ready(function(){
    $('.inner-pg').removeClass('overlay');
    $('.main-nav > li a').hover(function(){
        //$(this).parents('header').toggleClass('overlay');
        $('body').toggleClass('overlay');
    });
})


$(window).ready(function($) {
    var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
    // alert(path);
    $('.main-nav li a').each(function() {
        if (this.href === path) {
            $(this).parent().addClass('active');
            $(this).closest('.top-level-link').addClass('active');
            $(this).parent().parent().addClass('active');
        }
    });


    // $('.sub-menu-lists li a').each(function() {
    //  if (this.href === path) {
    //   $(this).parent().addClass('active');
    //   $(this).closest('.top-level-link').addClass('active');
    //  }
    // });


    $('#share').click(function(e){
        e.preventDefault();
        $('.share-icons').show(500);
    });

    $('.social-media .fa-close').click(function(e){
        e.preventDefault();
        $('.share-icons').hide(500);
    });

    $('.open-dropdown').hide();
    $('.top-filter-sec > ul > li').removeClass('active');
    $('.top-filter-sec > ul > li > a').click(function(e){
        e.preventDefault();
        $('.open-dropdown').hide();
        $(this).parent('.top-filter-sec > ul > li').toggleClass('active').siblings().removeClass('active');
        $(this).parent().find('.open-dropdown').toggle();
        //niceScroll();
        //$('body').toggleClass('overlay');
    });

    // $('.btn-done').click(function(e){
    //     e.preventDefault();
    //     $('.open-dropdown').hide();
    // });

    //$('.list-type li .filter-lists li a').removeClass('active');
    $('.list-type li .filter-lists li a').click(function(e){
        e.preventDefault();
        //$(this).removeClass('active');
        $(this).toggleClass('active');
        //$(this).parent().removeClass('active');
    });

    $('#residential-properties').hide();
    $('#commercial-properties').hide();
    $('#plots-properties').hide();
    $('#paying-properties').hide();
    $('#rent_residential-properties').hide();
    $('#buy-properties').hide();
    $('#rent-properties').hide();


    $('#customRadio1').click(function() {
        $('#residential-properties').show();
        $('#commercial-properties').hide();
        $('#plots-properties').hide();
        $(this).parent().find('.custom-control-label').addClass('active');
    });

    $('#customRadio2').click(function(){
        $('#residential-properties').hide();
        $('#commercial-properties').show();
        $('#plots-properties').show();
    });
    $('#customRadio3').click(function(){
        $('#residential-properties').hide();
        $('#commercial-properties').hide();
    });

    $('#rentRadio1').click(function() {

        $('#rent_residential-properties').show();
        $('#paying-properties').hide();
        $(this).parent().find('.custom-control-label').addClass('active');
    });
    $('#rentRadio2').click(function(){
        $('#rent_residential-properties').hide();
        $('#paying-properties').show();
    });
    $('#rentRadio3').click(function(){
        $('#rent_residential-properties').hide();
        $('#paying-properties').hide();
    });
    $('#commercialRadio1').click(function() {

        $('#rent-properties').show();
        $('#buy-properties').hide();
        $(this).parent().find('.custom-control-label').addClass('active');
    });
    $('#commercialRadio2').click(function(){
        $('#rent-properties').hide();
        $('#buy-properties').show();
    });

});



$('.open-dropdown').scrollspy({ target: '#list-example' })
// $(body).scrollspy({ target: '#secondary-nav' })
// $('.open-dropdown').scrollspy({ target: '#list-example2' })

// $('.scrollvTab').scrollspy({ target: '#list-example3' })


// Home page Search Option 13 Sept 21 //
$('.open-dropdown1').scrollspy({ target: '#list-example' })
$('.open-dropdown1').hide();


$('.buy_select .open-dropdown1').hide();
$('.buy_select').click(function(e){
    e.preventDefault();
    $('.buy-section .open-dropdown1').toggle();
    $(this).parent('.buy_select').toggleClass('active');
    $('.select-budget .open-dropdown1').hide();
});
$('.search_city').click(function(){
    $('.buy-section .open-dropdown1').hide();
})


$('.area-section .location-block').hide();
$('.area').click(function(e){
    e.preventDefault();
    $('.area-section .location-block').toggle();
    $(this).parent('.area-section').toggleClass('active');
});

// var minRange = ".minRange";
// var maxRange = ".maxRange";



// $(function() {
//     $('.multiple-select').multipleSelect({
//         ellipsis: true,
//         minimumCountSelected: 2,
//         //showClear: true,
//         selectAll: false,
//         filter: true,
//         placeholder: 'Location (upto 3)',
//     });
// })

$('#buy').click(function(e){
    e.preventDefault();
    $('#nav-buy').show().addClass('show active').fadeIn(3000);
    $('#nav-rent').hide().removeClass('show active');
    $('#frmbuy').show();
    $('#frmrent').hide();
    $('#nav-commercial').hide().removeClass('show active');
    $(this).addClass('active');
    $(this).siblings().removeClass('active');
})
$('#rent').click(function(e){
    e.preventDefault();
    $('#nav-buy').hide().removeClass('show active');
    $('#nav-rent').show().addClass('show active').fadeIn(3000);
    $('#frmrent').show();
    $('#frmbuy').hide();
    $('#nav-commercial').hide();
    $(this).addClass('active');
    $(this).siblings().removeClass('active');
})
$('#commercial').click(function(e){
    e.preventDefault();
    $('#nav-buy').hide().removeClass('show active');
    $('#nav-rent').hide().removeClass('show active');
    $('#nav-commercial').show().addClass('show active').fadeIn(3000);
    $(this).addClass('active');
    $(this).siblings().removeClass('active');
})

/* Characters Remaining Countdown */
$('.character-count').keypress(function(){

    if(this.value.length > 1000){
        return false;
    }
    $(".remainingC").html((1000 - this.value.length));


});

// Tooltip

// $('.tooltiptext').hide();
// $('.tooltip-social a.call').click(function(e){
//     e.preventDefault();
//     $('.tooltip-social .tooltiptext').toggle();
// });
// $('.tooltiptext-schedule').hide();
// $('.tooltip-social a.schedule').click(function(e){
//     e.preventDefault();
//     $('.tooltip-social .tooltiptext-schedule').toggle();
//     $(this).parent('.tooltiptext-schedule').toggleClass('active');
// });

// $('.open-dropdown li a').click(function(){
//     $(this).addClass('active');
// })

$('.popover').hide();
$('.tooltip-social li.tooltip-list>a').click(function(){

    $(this).addClass('active').parent().siblings().find('a').removeClass('active');
    $(this).parent('li').find('.popover').show();
    $(this).parent().siblings().find('.popover').hide();
    $('html, body').animate({
        scrollTop: 700
    }, 2000, 'easeInOutExpo');
    return false;
});



function formcontrol(){
    $(".form-control").filter(function() {
        if (this.value.length !==0){
            $(this).siblings('label').addClass('clicked');
        }
    });
}

formcontrol();
$('.form-group label').click(function () {
    $(this).addClass('clicked');
});

$('.form-control').click(function () {
    $(this).siblings('label').addClass('clicked');
});
$('.form-control').keyup(function () {
    $(this).siblings('label').addClass('clicked');
});
$('.form-control').blur(function () {
    if ($(this).val()) {
        $(this).siblings('label').addClass('clicked');
    }
    else if (!$(this).val()) {
        $(this).siblings('label').removeClass('clicked');
    }

});
$('.form-control.date').click(function () {
    $(this).parent().find('label').hide();
});


$(function () {
    if ($("#datepicker").length > 0 ){
        $("#datepicker").datepicker({
            autoclose: true,
            todayHighlight: true
        }).datepicker();
    }
});

$('.cust-calender input').click(function(){
    $(this).parent().find('label').addClass('clicked');
})


$('.thankyou').hide();
$('#schedule-meeting').click(function(){
    $(this).parents().find('.form-sec').hide();
    $('.thankyou').show();
})
$('#close-popup').click(function(){
    $('.popover').hide();
})

$(document).ready(function(){
    $(".modal-contact").click(function(){
        $('#verify-number').modal('show');
        $('#contact-form').modal('hide');
    });

    $("p.terms a").click(function(){
        $('#terms-conditions').modal('show');
        $('#contact-form').modal('hide');
        $('#view-contact').modal('hide');
        $('#enquiry-form').modal('hide');
    });


    $(".modal-contact").click(function(){
        $('#verify-number').modal('show');
        $('#view-contact').modal('hide');
        $('#enquiry-form').modal('hide');
    });

    $(".modal-contact").click(function(){
        $('#verify-number').modal('show');

    });

    //   $('.cust-calender input[type="email"]').click(function(){
    //       //alert('label click');
    //       $('label').addClass('clicked');
    //   })

});
