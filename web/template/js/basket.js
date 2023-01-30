(function ($) {
    "use strict";

    // Dropdown on mouse hover
    $(document).ready(function () {


        function calcPrice(productBlock) {
            let count = productBlock.data('offer-count');
            let wholesaleCount = productBlock.data("wholesale-count");
            let wholesalePrice = productBlock.data("wholesale-price");
            let retailPrice = productBlock.data("retail-price");
            let price = retailPrice;
            let fullPrice = retailPrice * count;

            if (count >= wholesaleCount) {
                price = wholesalePrice;
                fullPrice = wholesalePrice * count;
            }

            productBlock.parents('.product-page').find('span.product-price').html(price);
        }

        function jobWithProductPage(productBlock) {
            let offerId = productBlock.data("offer-id");
            let count = productBlock.data('offer-count');
            let toBasketButton = productBlock.parents('.product-page').find('.addToBasket');

            addToBasket(toBasketButton, offerId, count);

            if (count <= 0) {
                count = 1;
            }

            setCount(productBlock, count);
            calcPrice(productBlock);
        }

        $('.custom-control-input').on('change', function () {
            let activeOfferSearchString = '.offers-list div';

            $('.offer-properties input:checked').each(function (index, element) {
                activeOfferSearchString += '[data-offer-' + $(element).prop('name') +  '="' + $(element).val() + '"]';
            });

            $('.offers-list div').data('offer-active', 0);
            $('.offers-list div').attr('data-offer-active', 0);

            let activeOfferBlock = $(activeOfferSearchString);
            activeOfferBlock.data('offer-active', 1);
            activeOfferBlock.attr('data-offer-active', 1);

            setCount(productBlock, activeOfferBlock.data('offer-count'));
            calcPrice(activeOfferBlock);

            if (activeOfferBlock.data('offer-in-basket') == 1) {
                $('.addToBasket').html('В корзине');
            } else {
                $('.addToBasket').html('В корзину');
            }

            $('#product-carousel div.carousel-item')
                .removeClass('active')
                .removeClass('carousel-item')
                .addClass('carousel-item-hidden')
                .addClass('hidden');
            // $('#product-carousel div.carousel-item[data-offer-id=' + activeOfferBlock.data('offer-id') + ']')
            //     .removeClass('hidden');

            if ($('#product-carousel div.carousel-block[data-offer-id=' + activeOfferBlock.data('offer-id') + ']').length > 0) {
                $('#product-carousel div.carousel-block[data-offer-id=' + activeOfferBlock.data('offer-id') + ']')
                    .addClass('carousel-item')
                    .removeClass('carousel-item-hidden')
                    .removeClass('hidden')
                    .first()
                    .addClass('active');
            } else {
                $('#product-carousel div.carousel-block[data-offer-id="product"]')
                    .addClass('carousel-item')
                    .removeClass('carousel-item-hidden')
                    .removeClass('hidden')
                    .first()
                    .addClass('active');
            }
        });

        $(document).on('click', '.quantity i.fa', function (e) {
            let activeOfferBlock = $('.offers-list div[data-offer-active="1"]');

            let count = activeOfferBlock.data('offer-count');

            if ($(this).hasClass('fa-minus')) {
                count -= 1;
                count = count < 0 ? 0 : count;
            } else {
                if (count === 0) {
                    count = 1;
                }

                count = count * 1 + 1;
            }

            activeOfferBlock.data('offer-count', count < 0 ? 0 : count);
            activeOfferBlock.attr('data-offer-count', count < 0 ? 0 : count);

            jobWithProductPage(activeOfferBlock);
        });

        $(document).on('click', '.addToBasket', function (e) {
            if ($(this).data('active') !== 1) {
                return;
            }

            let activeOfferBlock = $('.offers-list div[data-offer-active="1"]');
            let count = activeOfferBlock.data('offer-count');

            if (count === 0) {
                count = 1;
            }

            activeOfferBlock.data('offer-count', count);
            activeOfferBlock.attr('data-offer-count', count);

            jobWithProductPage(activeOfferBlock);
        });

        $(document).on('blur', '.offer-count', function (e) {
            let activeOfferBlock = $('.offers-list div[data-offer-active="1"]');
            let count = $(this).val();

            activeOfferBlock.data('offer-count', count < 0 ? 0 : count);
            activeOfferBlock.attr('data-offer-count', count < 0 ? 0 : count);

            jobWithProductPage(activeOfferBlock);
        });
    });

})(jQuery);

