(function ($) {
    "use strict";

    // Dropdown on mouse hover
    $(document).ready(function () {

        /** Уведомление об использовании телефона**/

        /** END => Уведомление об использовании телефона**/

        let setSmallBasketCount;
        let setBasketPrice;

        if ($('.basket-page').length > 0) {

            $('#create-order, #create-order-bottom').on('click', function() {

                $.ajax({
                    url: '/basket/order/finish-order',
                    type: 'POST',
                    data: {'comment': $('#comment-filed').val(), '_csrf': $('meta[name=csrf-token]').attr('content')},
                    dataType: "json",
                    success: function(response) {
                        if (response.success === 200) {
                            window.location.href = "/";
                        }
                    }
                });
            });

            setBasketPrice = function setBasketPrice() {
                $('.basket-all-price').html(
                      $('.basket-products-price').html() * 1
                    + $('.basket-delivery-price').html() * 1
                );
            };

            setSmallBasketCount = function setSmallBasketCount() {
                $('.small-basket-count').html($('.product-item').length);
            };

            $(document).on('blur', '.offer-count', function (e) {
                let activeOfferBlock = $(this).parents('.product-item');
                let count = $(this).val();

                activeOfferBlock.data('offer-count', count < 0 ? 0 : count);
                activeOfferBlock.attr('data-offer-count', count < 0 ? 0 : count);

                jobWithProductPage(activeOfferBlock);
            });

            $(document).on('click', '.delete-from-basket', function (e) {
                let activeBlock = $(this).parents('.product-item');

                let count = 0;

                activeBlock.data('offer-count', count);
                activeBlock.attr('data-offer-count', count);

                jobWithProductPage(activeBlock);
                activeBlock.remove();
            });

            $(document).on('click', '.quantity i.fa', function (e) {
                let activeBlock = $(this).parents('.product-item');

                let count = activeBlock.data('offer-count');

                if ($(this).hasClass('fa-minus')) {
                    count -= 1;
                    count = count < 0 ? 0 : count;
                } else {
                    if (count === 0) {
                        count = 1;
                    }

                    count = count * 1 + 1;
                }

                activeBlock.data('offer-count', count < 0 ? 0 : count);
                activeBlock.attr('data-offer-count', count < 0 ? 0 : count);

                jobWithProductPage(activeBlock);
            });

            $('.delivery-control-input').on('change', function() {

                setLoader(true);
                setBasketParams('delivery_id', $(this).val());

                $('.delivery-select .delivery-info').addClass('hidden');
                $(this).parent().find('.delivery-info').removeClass('hidden');

                if ($(this).data('address') == 1) {
                    $('#address-container').removeClass('hidden');
                } else {
                    $('#address-container').addClass('hidden');
                }

                if ($(this).data('calculate') == 1) {
                    $('.basket-delivery-price').html($('#delivery-price').val());
                    setBasketParams('delivery_price', $('#delivery-price').val());
                } else {
                    $('.basket-delivery-price').html(0);
                    setBasketParams('delivery_price', 0);
                }

                setBasketPrice();
                setLoader(false);
            });

            ymaps.ready(init);

            function init() {
                // Стоимость за километр.
                var DELIVERY_TARIFF = 20,
                    // Минимальная стоимость.
                    MINIMUM_COST = 100,
                    myMap = new ymaps.Map('map', {
                        center: [55.030204, 82.920430],
                        zoom: 9,
                        controls: []
                    }),
                    // Создадим панель маршрутизации.
                    routePanelControl = new ymaps.control.RoutePanel({
                        options: {
                            // Добавим заголовок панели.
                            showHeader: true,
                            title: 'Расчёт доставки'
                        }
                    }),
                    zoomControl = new ymaps.control.ZoomControl({
                        options: {
                            size: 'small',
                            float: 'none',
                            position: {
                                bottom: 145,
                                right: 10
                            }
                        }
                    });
                // Пользователь сможет построить только автомобильный маршрут.
                routePanelControl.routePanel.options.set({
                    types: {auto: true}
                });

                // Если вы хотите задать неизменяемую точку "откуда", раскомментируйте код ниже.
                routePanelControl.routePanel.state.set({
                    fromEnabled: false,
                    from: 'Новосибирская область, Тогучинский район, с. Карпысак',
                    toEnabled: false
                });

                if ($('#delivery-address').val().length > 0) {
                    routePanelControl.routePanel.state.set({
                        to: $('#delivery-address').val()
                    });
                }

                $("#delivery-address").suggestions({
                    token: "a88e8cdd3d3d0cd032a3a8b3d4a4e9de2e27d98e",
                    type: "ADDRESS",
                    /* Вызывается, когда пользователь выбирает одну из подсказок */
                    onSelect: function(suggestion) {
                        setLoader(true);

                        routePanelControl.routePanel.state.set({
                            to: suggestion.unrestricted_value
                        });

                        setBasketParams('delivery_address', suggestion.unrestricted_value);
                        $('#delivery-address-hidden').val(suggestion.unrestricted_value);

                        setLoader(false);
                    }
                });

                myMap.controls.add(routePanelControl).add(zoomControl);

                // Получим ссылку на маршрут.
                routePanelControl.routePanel.getRouteAsync().then(function (route) {

                    // Зададим максимально допустимое число маршрутов, возвращаемых мультимаршрутизатором.
                    route.model.setParams({results: 1}, true);

                    // Повесим обработчик на событие построения маршрута.
                    route.model.events.add('requestsuccess', function () {

                        var activeRoute = route.getActiveRoute();

                        if (activeRoute) {
                            setLoader(true);

                            // Получим протяженность маршрута.
                            var length = route.getActiveRoute().properties.get("distance"),
                                // Вычислим стоимость доставки.
                                price = calculate(Math.round(length.value / 1000)),
                                // Создадим макет содержимого балуна маршрута.
                                balloonContentLayout = ymaps.templateLayoutFactory.createClass(
                                    '<span>Расстояние: ' + length.text + '.</span><br/>' +
                                    '<span style="font-weight: bold; font-style: italic">Стоимость доставки: ' + price + ' р.</span>');
                            // Зададим этот макет для содержимого балуна.
                            route.options.set('routeBalloonContentLayout', balloonContentLayout);
                            // Откроем балун.
                            activeRoute.balloon.open();
                            $('.basket-delivery-price').html(price);
                            setBasketParams('delivery_price', price);

                            $('#delivery-price').val(price);

                            setBasketPrice();

                            setLoader(false);
                        }
                    });
                });
                // Функция, вычисляющая стоимость доставки.
                function calculate(routeLength) {
                    return Math.max(routeLength * DELIVERY_TARIFF, MINIMUM_COST);
                }
            }
        } else {

            setSmallBasketCount = function setSmallBasketCount() {
                $('.small-basket-count').html($('.offers-list div[data-offer-in-basket="1"]').length);
            };

            function startProductPage() {
                let activeOfferContainer = $('.offers-list div[data-offer-active="1"]');
                let activeOfferId = activeOfferContainer.data('offer-id');

                let imageCount = $('#product-carousel div.carousel-block[data-offer-id="' + activeOfferId + '"]').length;

                if (imageCount <= 0) {
                    $('#product-carousel div.carousel-block')
                        .addClass('hidden')
                        .addClass('carousel-item-hidden')
                        .removeClass('carousel-item');
                    $('#product-carousel div.carousel-block[data-offer-id="product"]')
                        .removeClass('hidden')
                        .removeClass('carousel-item-hidden')
                        .addClass('carousel-item')
                        .first()
                        .addClass('active');
                } else {
                    $('#product-carousel div.carousel-block[data-offer-id="' + activeOfferId + '"]')
                        .first()
                        .addClass('active');
                }
            }


            startProductPage();

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

                setCount($('#product-item-' + activeOfferBlock.data('product-id')), activeOfferBlock.data('offer-count'));
                calcPrice(activeOfferBlock);

                if (activeOfferBlock.data('offer-in-basket') === 1) {
                    $('.addToBasket').html('В корзине');
                    $('.addToBasket').data('active', 0);
                } else {
                    $('.addToBasket').html('В корзину');
                    $('.addToBasket').data('active', 1);
                }

                $('#product-carousel div.carousel-item')
                    .removeClass('active')
                    .removeClass('carousel-item')
                    .addClass('carousel-item-hidden')
                    .addClass('hidden');

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
        }


        function calcPrice(productBlock) {
            let count = productBlock.data('offer-count');
            let wholesaleCount = productBlock.data("wholesale-count");
            let wholesalePrice = productBlock.data("wholesale-price");
            let retailPrice = productBlock.data("retail-price");
            let price = retailPrice;

            if (count >= wholesaleCount) {
                price = wholesalePrice;
            }

            if($('span.product-price').length === 1 && $('.basket-page').length === 0) {
                $('span.product-price').html(price);
            }

            if (productBlock.find('span.product-result-price').length > 0) {
                productBlock.find('span.product-result-price').html(price * count);
            }

            if ($('.basket-page').length > 0) {
                let basketSum = 0;

                $("span.product-result-price").each(function(){
                    basketSum += parseFloat($(this).html());
                });

                $('.basket-products-price').html(basketSum);
            }
        }

        function setCount(productBlock, count) {
            if (productBlock.find('.offer-count').length > 0) {
                productBlock.find('.offer-count').val(count > 0 ? count : 1);

                return;
            }

            if ($('.offer-count').length === 1) {
                $('.offer-count').val(count > 0 ? count : 1);

                return;
            }
        }

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

                    if ($('.addToBasket').length > 0) {
                        if (count > 0) {
                            $('.addToBasket').html('В корзине');
                            $('.addToBasket').data('active', 0);
                            $('.addToBasket').attr('data-active', 0);
                        } else {
                            $('.addToBasket').html('В корзину');
                            $('.addToBasket').data('active', 1);
                            $('.addToBasket').attr('data-active', 1);
                        }
                    }

                    setSmallBasketCount();
                }
            });
        }

        function jobWithProductPage(productBlock) {
            let offerId = productBlock.data("offer-id");
            let count = productBlock.data('offer-count');
            let toBasketButton = productBlock.parents('.product-page').find('.addToBasket');
            addToBasket(toBasketButton, offerId, count);

            setCount(productBlock, productBlock.data('offer-count'));
            calcPrice(productBlock);
        }

        function setLoader(visible) {
            if (visible === true) {
                $('.loader-container').show();
            } else {
                $('.loader-container').hide();
            }
        }

        function setBasketParams(param, value) {
            $.ajax({
                url: '/basket/order/add-param',
                type: 'POST',
                data: {'param': param, 'value': value, '_csrf': $('meta[name=csrf-token]').attr('content')},
                dataType: "json",
            });
        }
    });

})(jQuery);

