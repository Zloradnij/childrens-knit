// $(document).ready(function () {
//
//     function jobWithProduct(productBlock) {
//         let offerId = productBlock.data("offer-id");
//         let count = productBlock.find('.orderitem-count').val();
//         let toBasketButton = productBlock.find('.addToBasket');
//
//         addToBasket(toBasketButton, offerId, count);
//
//         if (count <= 0) {
//             count = 1;
//         }
//
//         productBlock.find('.orderitem-count').val(count);
//         calcPrice(productBlock);
//     }
//
//     $('.orderitem-count').bind("change keyup input click", function () {
//         if (this.value.match(/[^0-9]/g)) {
//             this.value = this.value.replace(/[^0-9]/g, '');
//         }
//     });
//
//     $(document).on('blur', '.orderitem-count', function (e) {
//         jobWithProduct($(this).parents('.add-product-block'));
//     });
//
//     $(document).on('click', '.addToBasket', function (e) {
//         jobWithProduct($(this).parents('.add-product-block'));
//     });
//
//     $(document).on('click', '.set-product-count', function (e) {
//         let count = $(this).parents('.add-product-block').find('.orderitem-count').val();
//
//         if ($(this).hasClass('product-count-minus')) {
//             count -= 1;
//             count = count < 0 ? 0 : count;
//         } else {
//             count = count * 1 + 1;
//         }
//
//         $(this).parents('.add-product-block').find('.orderitem-count').val(count);
//
//         jobWithProduct($(this).parents('.add-product-block'));
//     });
//
//     $(document).on('click', '.small-basket-open', function(e) {
//         $('.small-basket-block-items').toggle('display');    });
// });
//
// function addToBasket(toBasketButton, offerId, count) {
//     $.ajax({
//         url: '/basket/order/add-product',
//         type: 'POST',
//         data: {'offer_id': offerId, 'count': count},
//         dataType: "json",
//     });
// }
//
// function calcPrice(addProductBlock) {
//     let count = addProductBlock.find('.orderitem-count').val();
//     let wholesaleCount = addProductBlock.data("wholesale-count");
//     let wholesalePrice = addProductBlock.data("wholesale-price");
//     let retailPrice = addProductBlock.data("retail-price");
//     let price = retailPrice;
//     let fullPrice = retailPrice * count;
//
//     if (count >= wholesaleCount) {
//         price = wholesalePrice;
//         fullPrice = wholesalePrice * count;
//     }
//
//     addProductBlock.find('.product-price .show-price').html(price);
//     addProductBlock.find('.product-full-price').html(fullPrice);
// }
