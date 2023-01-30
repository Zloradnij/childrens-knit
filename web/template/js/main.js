(function ($) {
    "use strict";
    
    // Dropdown on mouse hover
    $(document).ready(function () {

        /** only number */
        $('.orderitem-count, .offer-count').bind("change keyup input click", function () {
            if (this.value.match(/[^0-9]/g)) {
                this.value = this.value.replace(/[^0-9]/g, '');
            }
        });

        function addToBasket(toBasketButton, offerId, count) {
            $.ajax({
                url: '/basket/order/add-product',
                type: 'POST',
                data: {'offer_id': offerId, 'count': count, '_csrf': $('meta[name=csrf-token]').attr('content')},
                dataType: "json",
                success: function(response) {
                    let activeOfferBlock = $('.offers-list div[data-offer-id="' + offerId + '"]');

                    activeOfferBlock.data('offer-in-basket', count > 0 ? 1 : 0);
                    activeOfferBlock.attr('data-offer-in-basket', count > 0 ? 1 : 0);

                    if (count > 0) {
                        $('.addToBasket').html('В корзине');
                        $('.addToBasket').data('active', 0);
                        $('.addToBasket').attr('data-active', 0);
                    } else {
                        $('.addToBasket').html('В корзину');
                        $('.addToBasket').data('active', 1);
                        $('.addToBasket').attr('data-active', 1);
                    }

                    setSmallBasketCount();
                }
            });
        }

        function setSmallBasketCount() {
            $('.small-basket-count').html($('.offers-list div[data-offer-in-basket="1"]').length);
        }

        function toggleNavbarMethod() {
            if ($(window).width() > 992) {
                $('.navbar .dropdown').on('mouseover', function () {
                    $('.dropdown-toggle', this).trigger('click');
                }).on('mouseout', function () {
                    $('.dropdown-toggle', this).trigger('click').blur();
                });
            } else {
                $('.navbar .dropdown').off('mouseover').off('mouseout');
            }
        }
        toggleNavbarMethod();
        $(window).resize(toggleNavbarMethod);
    });
    
    
    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Vendor carousel
    $('.vendor-carousel').owlCarousel({
        loop: true,
        margin: 29,
        nav: false,
        autoplay: true,
        smartSpeed: 1000,
        responsive: {
            0:{
                items:2
            },
            576:{
                items:3
            },
            768:{
                items:4
            },
            992:{
                items:5
            },
            1200:{
                items:6
            }
        }
    });


    // Related carousel
    $('.related-carousel').owlCarousel({
        loop: true,
        margin: 29,
        nav: false,
        autoplay: true,
        smartSpeed: 1000,
        responsive: {
            0:{
                items:1
            },
            576:{
                items:2
            },
            768:{
                items:3
            },
            992:{
                items:4
            }
        }
    });

})(jQuery);

