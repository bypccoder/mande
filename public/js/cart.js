var orderData = new FormData()
var $tempItem = null

$(document).ready(function () {

    showCartTable()

    document.querySelector('#frm-order-resume').addEventListener('submit', (e) => {
        e.preventDefault()
        if (!e.target.checkValidity()) {
            e.preventDefault()
            e.stopPropagation()
        }

        e.target.classList.add('was-validated')

        orderData.append('amount', document.querySelector('#total > span').textContent)
        orderData.append('payment_method_id', document.querySelector('input[name="paymentMethod"]:checked').value)
        orderData.append('form_method_id', document.querySelector('input[name="voucherType"]:checked').value)

        var selectedInvoiceType = $('#invoiceType').val();
        var selectedVoucherType = parseInt($('input[name="voucherType"]:checked').val());
        if (selectedVoucherType == 2) {
            orderData.append('invoiceType', selectedInvoiceType);
        }

        if (selectedInvoiceType == 1) {
            orderData.append('start_date', $('#startDate').val());
            orderData.append('end_date', $('#endDate').val());

        }

        submitHandlerOrder(orderData)

    })
})

function addToCart(element) {

    if (element.classList.contains('active')) {
        return 0
    }


    // validamos si existe un item temporal para los menu - fondo
    var productParent = ($tempItem) ? $tempItem : $(element).closest('div.product-item')

    var price = $(productParent).find('.price span').text()
    var productName = ($tempItem) ? $(productParent).find('.productname').text() + ' + ' + $(element).closest('div.product-item').find('.productname').text() : $(productParent).find('.productname').text()
    var quantity = $(productParent).find('.product-quantity').val()
    var productId = $(productParent).attr('data-product-id')
    var productCode = $(productParent).attr('data-product-code')
    var image = $(productParent).find('img').attr('src')
    var sub_category = $(productParent).attr('data-sub-category')
    var product_type = $(productParent).attr('data-product-type')
    var iced_price = $(productParent).find('.btn-iced.active').attr('data-price')

    var cartItem = {
        productId: productId,
        productName: productName,
        productCode: productCode,
        price: price,
        quantity: quantity,
        image: image,
        subCategory: sub_category,
        iced_price: iced_price,
        product_type: product_type,
        currentCategory: sessionStorage.getItem('current-category'),
        currentSubCategory: sessionStorage.getItem('current-category') + "-" + sessionStorage.getItem('current-subcategory')
    }

    var cartItemJSON = JSON.stringify(cartItem)

    var cartArray = new Array()
    // If javascript shopping cart session is not empty
    if (sessionStorage.getItem('shopping-cart')) {
        cartArray = JSON.parse(sessionStorage.getItem('shopping-cart'))
    }
    cartArray.push(cartItemJSON)

    var cartJSON = JSON.stringify(cartArray)
    sessionStorage.setItem('shopping-cart', cartJSON)
    showCountItems()

    currentSubCategoryToServer()
    element.closest('.add-to-cart-ui').classList.add('disabled', 'pe-none')
    mostrarNotificacion(200)
    //showCartTable()
    // Eliminamos el item temporal
    $tempItem = null

    if (sessionStorage.getItem('current-category')) {
        let currentCategory = sessionStorage.getItem('current-category')
        if (currentCategory == 16 || currentCategory == 17) {
            callView('home/categories', 'categoria-container')
            changeWapperShow('main')
            document.querySelector('#modal_complementary_menu .btn-close').click()

        }
    }

    // Validamos la existencia de FONDO + ENTRADA para realizar la combinacion de productos
    combineFondoEntradaMenu()
}

function emptyCart() {
    if (sessionStorage.getItem('shopping-cart')) {
        // Clear JavaScript sessionStorage by index
        sessionStorage.removeItem('shopping-cart')
        sessionStorage.removeItem('current-category')
        callView('home/categories', 'categoria-container')
        mostrarNotificacion(200)
        showCartTable()
        showCountItems()
        currentSubCategoryToServer()
    }
}

function removeCartItem(index) {
    if (sessionStorage.getItem('shopping-cart')) {
        var shoppingCart = JSON.parse(sessionStorage.getItem('shopping-cart'))
        shoppingCart.splice(index, 1)
        sessionStorage.setItem('shopping-cart', JSON.stringify(shoppingCart))
        showCartTable()
        showCountItems()
        mostrarNotificacion(200)
        currentSubCategoryToServer()
    }
}

function showCountItems() {
    if (sessionStorage.getItem('shopping-cart')) {
        var shoppingCart = JSON.parse(sessionStorage.getItem('shopping-cart'));
        itemCount = shoppingCart.length;

        $('#cart-counter').text(itemCount);
    }
}

function showCartTable() {
    var cartRowHTML = "";
    var grandTotal = 0, igv = 0, itemCount = 0, precioSinIGV = 0, precioConIGV = 0, montoIGV = 0
    var price = 0, quantity = 0, subTotal = 0
    var inCorpMilla = false

    shoppingCart = sessionStorage.getItem('shopping-cart')

    if (shoppingCart && shoppingCart != []) {
        var shoppingCart = JSON.parse(sessionStorage.getItem('shopping-cart'));
        const dataCart = []
        var cartRowHTML = ""
        var count = 0
        let template = `<tr data-index=":i:">
							<td :border:><img src=":url:" alt=":name:" width="50"></td>
							<td :border:>:name:</td>
							<td :border:>S/.:price:</td>
							<td :border:><input type="number" :disabled: class="form-control quantity-input" min="1" max="20" value=":quantity:"></td>
							<td :border:><button class="btn btn-lg btn-red tra-red-hover" onclick="removeCartItem(:index:)"><i class="las la-trash-alt"></i></button></td>
						</tr>`
        //itemCount = shoppingCart.length;

        shoppingCart.forEach(function (item) {
            var cartItem = JSON.parse(item);
            var disableInput = ""

            dataCart.push(cartItem);

            price = parseFloat(cartItem.price);
            quantity = parseInt(cartItem.quantity);


            if (cartItem.price == 0) {
                disableInput = 'disabled readonly style="background: #eee;"'
            }

            if( cartItem.currentCategory == 16 ){ // CorpMilla
                inCorpMilla = true;
            }

            var noBorder = ""
            if (cartItem.subCategory == '5' || cartItem.subCategory == '8') {
                noBorder = 'style="border: 0"'
                disableInput = 'disabled readonly style="background: #eee;"'
            }

            // Validacion para bebidas heladas
            if (cartItem.iced_price > 0 && cartItem.iced_price) {
                cartItem.productName += ' + HELADA'
                price += parseFloat(cartItem.iced_price)
            }

            cartRowHTML += template.replace(':name:', cartItem.productName)
                .replace(':price:', price.toFixed(2))
                .replace(':name:', cartItem.productName)
                .replace(':quantity:', quantity)
                .replace(':url:', BASE_PATH + cartItem.image)
                .replace(':index:', count)
                .replace(':i:', count)
                .replace(':disabled:', disableInput)
                .replaceAll(':border:', noBorder)

            subTotal = price * quantity

            grandTotal += subTotal;

            count++
        });
    } else {
        cartRowHTML = `<tr><td class="text-center" colspan="5">No existe datos en tu carrito &#128532;</td></tr>`
    }


    precioSinIGV = grandTotal / 1.18
    montoIGV = grandTotal - precioSinIGV
    precioConIGV = grandTotal

    if (grandTotal > 0) {
        document.querySelectorAll('input[name="paymentMethod"')[0].setAttribute('disabled', 'disabled')
    } else {
        document.querySelectorAll('input[name="paymentMethod"')[0].removeAttribute('disabled')
    }

    $('#igv span').text(montoIGV.toFixed(2))
    $('#sub-total span').text(precioSinIGV.toFixed(2))
    $('#total span').text(precioConIGV.toFixed(2))

    $('#cart-list-product').html(cartRowHTML);
    $('#itemCount').text(itemCount);


    /**
     * Para el caso de CORPORACION MILLA el pago sera GRATUITO y el metodo de apgo
     * sera NOTA DE VENTA esto pro defecto
     */
    if(inCorpMilla){
        document.querySelectorAll('.container-payment input').forEach(input => {
            input.setAttribute('disabled','disabled')
        })
        document.querySelector('#gratuito').setAttribute('checked','checked')
        
        document.querySelectorAll('.container-voucher input').forEach(input => {            
            input.setAttribute('disabled','disabled')
        })
        document.querySelector('#nota_de_venta').setAttribute('checked','checked')
    }else{
        document.querySelectorAll('.container-payment input').forEach(input => {
            input.removeAttribute('disabled')
            input.removeAttribute('checked')
        })
        document.querySelectorAll('.container-voucher input').forEach(input => {            
            input.removeAttribute('disabled')
            input.removeAttribute('checked')
        })
    }


}

function updateQuantity(index, quantity) {

    if (sessionStorage.getItem('shopping-cart')) {
        var shoppingCart = JSON.parse(sessionStorage.getItem('shopping-cart'));
        var shoppingCartUpdate = []

        var grandTotal = 0, precioSinIGV = 0, montoIGV = 0
        var price = 0, subTotal = 0
        var itemAux = ''

        shoppingCart.forEach((item, indx) => {
            itemAux = JSON.parse(item)
            if (indx == index) {
                itemAux.quantity = quantity
            }

            price = parseFloat(itemAux.price);
            quantity = parseInt(itemAux.quantity);
            subTotal = price * quantity

            grandTotal += subTotal;

            shoppingCartUpdate.push(JSON.stringify(itemAux))
        })


        precioSinIGV = grandTotal / 1.18
        montoIGV = grandTotal - precioSinIGV
        precioConIGV = grandTotal + montoIGV

        $('#igv span').text(montoIGV.toFixed(2))
        $('#sub-total span').text(grandTotal.toFixed(2))
        $('#total span').text(precioConIGV.toFixed(2))

        let cartJSON = JSON.stringify(shoppingCartUpdate)
        sessionStorage.setItem('shopping-cart', cartJSON)
    }

}

function submitHandlerOrder(formData) {

    if (!sessionStorage.getItem('shopping-cart')) {
        let message = document.querySelector('#cart-list-product td').textContent
        mostrarMensajeAlerta(400, message)
        return false
    }

    let data = JSON.parse(sessionStorage.getItem('shopping-cart'))
    let formDataJSON = {};

    for (let [clave, valor] of formData.entries()) {
        formDataJSON[clave] = valor;
    }

    CRUD.sendData('order/store', { order: formDataJSON, orderDetail: data }).then(response => {
        if (response.code == 200) {
            emptyCart()
            //alert(JSON.stringify(response.printData));

            if (response.printData) {
                response.printData.forEach(element => {
                    $.ajax({
                        type: "POST",
                        data: element,
                        url: element.print_data_url,
                        dataType: "Json",
                        success: function (response) {
                            console.log(response)
                        }
                    });

                    // alert(JSON.stringify(response.printData));
                    setTimeout(function () {
                        window.location.href = BASE_PATH
                    }, 5000);
                });
            }


        } else {
            mostrarMensajeAlerta(response.code, response.message)
        }
    })


}

/**
 * FUNCIONES PARA MENU
 */

function validateExistInCart(element) {
    var validate = false
    if (sessionStorage.getItem('shopping-cart')) {
        const productId = element.closest('.product-item').getAttribute('data-product-id')
        const cartArray = JSON.parse(sessionStorage.getItem('shopping-cart'))
        for (let i = 0; i < cartArray.length; i++) {
            let itemString = cartArray[i];
            let item = JSON.parse(itemString);

            if (item.productId == productId) {
                validate = true
                break
            }
        }
    }
    return validate
}

function addMenuComplementary(element) {

    //addToCart(element)
    const product_type_id = element.getAttribute('data-product-type')
    $tempItem = $(element).closest('div.product-item')

    CRUD.sendData('home/products/complementary', {}).then(response => {
        const template = `<div class="product-item row" data-product-id=":id:" data-product-type="0"><div class="col-md-2 col-sm-2"><img src=":cover_image:" alt="user" class="img-thumbnail"><input class="product-quantity d-none" type="number" value="1" />
								</div><div class="col-md-7 col-sm-7"><h5 class="productname">:name:</h5><div class="price">S./<span>:price:</span></div></div><div class="col-md-3 col-sm-3 d-flex justify-content-center align-items-center">
								<div class="add-to-cart-ui"><a class="btn btn-success text-white pull-right rounded-pill add-to-cart"><i class="las la-plus-circle pe-none"></i> Agregar a orden</a></div></div></div><hr>`
        var entradasHtml = "", adicionalesHtml = ""
        response.products.forEach(item => {
            if (item.product_type_id == 2) {
                entradasHtml += template.replace(':id:', item.id).replace(':name:', item.name).replace(':price:', 0).replace(':cover_image:', BASE_PATH + 'storage/' + item.cover_image)
            } else {
                adicionalesHtml += template.replace(':id:', item.id).replace(':name:', item.name).replace(':price:', item.sales_price).replace(':cover_image:', BASE_PATH + 'storage/' + item.cover_image)
            }
        })

        if (product_type_id == 1) {
            document.getElementById('menu_entrada').innerHTML = entradasHtml
            document.getElementById('modal_complementary_menu').querySelector('.entry_title').className = 'bg-light p-2 mb-3 entry_title'
        } else {
            document.getElementById('modal_complementary_menu').querySelector('.entry_title').className = 'bg-light p-2 mb-3 entry_title d-none'
        }
        document.getElementById('menu_adicional').innerHTML = adicionalesHtml

        let modal = new coreui.Modal('#modal_complementary_menu', { keyboard: false })
        modal.show()
    })
}

// En caso que haya FONDO + ENTRADA en el carrito, estos se combinan para formar solo el MENU
function combineFondoEntradaMenu() {
    let shoppingCart = (sessionStorage.getItem('shopping-cart')) ? JSON.parse(sessionStorage.getItem('shopping-cart')) : [];
    let menu = [];
    let menuIndex = [];

    if (shoppingCart.length < 1) return 0;

    // Encuentra los elementos que son de tipo 1 o 2 y guarda sus índices
    shoppingCart.forEach((el, index) => {
        let item = JSON.parse(el);
        if (item.product_type == 1 || item.product_type == 2) {
            menu.push(item);
            menuIndex.push(index);
        }
    });

    if (menu.length > 1) {
        // Elimina los elementos del carrito de compras utilizando sus índices
        menuIndex.reverse().forEach((index) => {
            shoppingCart.splice(index, 1);
        });

        let str_extra_title = "";
        let obj_menu = null;

        // Encuentra el elemento de tipo 1 y modifica su nombre y precio
        menu.forEach((el) => {
            if (el.product_type == 1) {
                obj_menu = el;                
            } else if (el.product_type == 2) {
                str_extra_title = ' + ' + el.productName;
            }
        });

        obj_menu.productName += str_extra_title;

        obj_menu.price = 14

        if( obj_menu.currentCategory == 17 ){
            obj_menu.price = 8
        }
        
        // Agrega el elemento modificado de nuevo al carrito de compras
        shoppingCart.push(JSON.stringify(obj_menu));

        // Actualiza el carrito de compras en sessionStorage
        sessionStorage.setItem('shopping-cart', JSON.stringify(shoppingCart));
    }
}

/**
 * Functions of Util
 */
function currentCategory(element) {
    sessionStorage.setItem('current-category', element.getAttribute('data-category'))
}

function currentSubCategory(element) {
    sessionStorage.setItem('current-subcategory', element.getAttribute('data-subcategory'))
}

/**
 * Functions of Server
 */
function currentSubCategoryToServer() {
    try {
        var shoppingCart = []
        if (sessionStorage.getItem('shopping-cart')) {
            shoppingCart = JSON.parse(sessionStorage.getItem('shopping-cart'))
        }
        let formData = new FormData()
        let strIds = ''

        if (shoppingCart.length > 0) {
            shoppingCart.forEach(function (item) {
                var cartItem = JSON.parse(item);
                strIds += cartItem.currentSubCategory + ','
            })
        }

        formData.append('categories', strIds.slice(0, -1))
        formData.append('cart', sessionStorage.getItem('shopping-cart'))

        CRUD.sendDataNoJson('updateCart', formData, (response) => {
            console.log(response)
        })
    } catch (error) {
        console.log('Se genero un error al actualizar carrito en servidor' + error)
    }
}
