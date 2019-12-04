$(document).ready(function () {
    
    //add product btn
    $('.add-product-btn').on('click', function (e) {

        e.preventDefault();
        var name = $(this).data('name');
        var id = $(this).data('id');
        var price = $.number($(this).data('price'), 2);

        $(this).removeClass('btn-success').addClass('btn-default disabled');//disabled button after once click

        var html =
            `<tr>
                <td>${name}</td>
                <td><input type="number" name="products[${id}][quantity]" data-price="${price}" class="form-control input-sm product-quantity" min="1" value="1"></td>
                <td class="product-price">${price}</td>               
                <td><button class="btn btn-danger btn-sm remove-product-btn" data-id="${id}"><span class="fa fa-trash"></span></button></td>
            </tr>`;

        $('.order-list').append(html);

        //to calculate total price
        calculateTotal();
    });

    //disabled btn
    $('body').on('click', '.disabled', function(e) {

        e.preventDefault();

    });//end of disabled

    //remove product btn
    $('body').on('click', '.remove-product-btn', function(e) {

        e.preventDefault();
        var id = $(this).data('id');
      //remove row when click btn remove
        $(this).closest('tr').remove();
        //when click add btn make disabled and click remove btn return enabled
        $('#product-' + id).removeClass('btn-default disabled').addClass('btn-success');

        //to calculate total price
        calculateTotal();

    });//end of remove product btn

    //change product quantity hire change price and total
    $('body').on('keyup change', '.product-quantity', function() {
// jquery number plugin decimal number
        var quantity = Number($(this).val()); //2
        var unitPrice = parseFloat($(this).data('price').replace(/,/g, '')); //convert string with comma to number crash app NAN not a number after 1000
        console.log(unitPrice);
        $(this).closest('tr').find('.product-price').html($.number(quantity * unitPrice, 2));
        calculateTotal();

    });//end of product quantity change

    //list all order products
    $('.order-products').on('click', function(e) {

        e.preventDefault();

        $('#loading').css('display', 'flex'); // center loading
        
        var url = $(this).data('url');
        var method = $(this).data('method');
        $.ajax({
            url: url,
            method: method,
            success: function(data) {
//make loading
                $('#loading').css('display', 'none');
                $('#order-product-list').empty();
                $('#order-product-list').append(data);//show product//append get order data

            }
        })

    });//end of order products click

    //print order
    $(document).on('click', '.print-btn', function() {

        $('#print-area').printThis();

    });//end of click function

});//end of document ready

//calculate the total
function calculateTotal() {

    var price = 0;

    $('.order-list .product-price').each(function(index) {
        
        price += parseFloat($(this).html().replace(/,/g, ''));

    });//end of product price

    $('.total-price').html($.number(price, 2));

    //check if price > 0
    //add order
    if (price > 0) {

        $('#add-order-form-btn').removeClass('disabled')

    } else {

        $('#add-order-form-btn').addClass('disabled')

    }//end of else

}//end of calculate total
