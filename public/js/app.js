
const mainWrapper = document.querySelector('#main-wrapper')

const productsWrapper = document.querySelector('#productos-wrapper')
const resumeWrapper = document.querySelector('#resumen-wrapper')
const cart = sessionStorage.getItem('cart')
const btnCart = document.querySelector('.btn-cart')
const searchInput = document.querySelector('#search-input')


callView('home/categories', 'categoria-container')

// Muestra la cantidad de elementos en el carrito
showCountItems()

document.querySelector('#categoria-container').addEventListener('click', (e) => {
    e.preventDefault()
    if (e.target.classList.contains('categoria-item')) {
        let category = e.target.getAttribute('data-category')
        
        // Carga de combos
        if (category == 26) {
            //sessionStorage.setItem('current-subcategory', subcategory)

            callView('home/combos', 'productos-wrapper')

            //mainWrapper.className = 'd-none'
            resumeWrapper.className = 'd-none'
            productsWrapper.className = 'd-block'            
            return 0
        }

        let url = 'home/subcategories/' + category

        callView(url, 'categoria-container')
    }

    if (e.target.classList.contains('btn-back-view')) {
        callView('home/categories', 'categoria-container')
    }

    if (e.target.classList.contains('productos-mostrar')) {
        let subcategory = e.target.getAttribute('data-subcategory')
        let url = 'home/products/' + subcategory + '?subcategory=' + subcategory

        sessionStorage.setItem('current-subcategory', subcategory)

        callView(url , 'productos-wrapper')

        //mainWrapper.className = 'd-none'
        resumeWrapper.className = 'd-none'
        productsWrapper.className = 'd-block'
    }
})
document.querySelector('#productos-wrapper').addEventListener('click', (e) => {
    e.preventDefault()

    if (e.target.classList.contains('add-to-cart')) {
        let productType = e.target.closest('.product-item').getAttribute('data-product-type')

        if (validateExistInCart(e.target)) {
            return 0
        }

        addToCart(e.target)
/*
        if (productType > 0) {

            addMenuComplementary(e.target) // show modal
        } else if (e.target.classList.contains('add-to-cart')) {
            //console.log(e.target.closest('.product-item'))
            addToCart(e.target)
        }*/
    } else if (e.target.classList.contains('btn-iced')) {
        e.target.classList.toggle('active')
    }

})
/**
 * KEY EVENTS
 */
document.querySelector('#productos-wrapper').addEventListener('keyup', (e) => {
    e.preventDefault()
    if (e.target.classList.contains('input_filter')) {
        searchProducts()
    }
})
/**
document.querySelector('#resumen-wrapper').addEventListener('click', (e) => {
    e.preventDefault()

    if (e.target.classList.contains('btn-back-list')) {
        callView('home', 'productos-wrapper')

        mainWrapper.className = 'd-none'
        resumeWrapper.className = 'd-none'
        productsWrapper.className = 'd-block'
    }
})*/

document.querySelector('#productos-wrapper').addEventListener('click', (e) => {
    e.preventDefault()
    if (e.target.classList.contains('btn-back-view')) {
        /*productsWrapper.innerHTML = ''
        productsWrapper.className = 'd-none'
        mainWrapper.className = 'd-block'*/

        changeWapperShow('main')
    }
})

document.querySelector('.resumen-mostrar').addEventListener('click', (e) => {
    e.preventDefault()

    //callView('home/resume', 'resumen-wrapper')
    showCartTable()

    changeWapperShow('resume')
})

document.querySelector('#cart-list-product').addEventListener('input', (e) => {
    if (e.target.classList.contains('quantity-input')) {
        let index = e.target.closest('tr').getAttribute('data-index')

        updateQuantity(index, e.target.value)
    }
})

document.querySelector('#btn-cancel-order').addEventListener('click', (e) => {
    e.preventDefault()
    Swal.fire({
        title: 'Cancelar Orden',
        text: 'Confirme si desea cancelar su orden.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, deseo cancelar!'
    }).then((result) => {
        if (result.isConfirmed) {
            emptyCart()
            window.location.href = e.target.getAttribute('href')
        }
    })
})

// Buscador
document.querySelector('#search-input').addEventListener('input', (e) => {
    e.preventDefault()
    let searchValue = e.target.value.trim()
    // minimo 2 caracteres
    if (searchValue.length < 3) {
        productsWrapper.className = 'd-none'
        return 0
    }

    callView('home/productSearch/' + encodeURIComponent(searchValue), 'productos-wrapper')

    resumeWrapper.className = 'd-none'
    productsWrapper.className = 'd-block'
})

async function callView(url, container) {
    /**
     * Guardamos la ultima vista cargada para utilizar la carga
     * del boton atras o volver
     **/
    var productIds = '', urlAux = url
    var cartArray = ""
    var categoriesArray = ""
    localStorage.setItem('back-view', urlAux)


    if (sessionStorage.getItem('shopping-cart')) {
        /*cartArray = JSON.parse(sessionStorage.getItem('shopping-cart'))
        currentCategories = cartArray.map(item => JSON.parse(item).currentCategory)

        urlAux += `currentCategory=${currentCategories}`;*/

    }

    if (sessionStorage.getItem('shopping-cart')) {
        cartArray = JSON.parse(sessionStorage.getItem('shopping-cart'))

        productIds = cartArray.map(item => JSON.parse(item).productId)
        url = '?' + urlAux + `&productIds=${productIds.join(',')}`;
    }


    url = urlAux

    const response = await fetch(url, {
        method: 'GET',
    });

    /*const response = await fetch(url, {
        method: 'GET',
        body: JSON.stringify(cartArray)
    })*/

    const responseHtml = await response.text()
    document.getElementById(container).innerHTML = responseHtml;


    const tooltipTriggerList = document.querySelectorAll('[data-coreui-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new coreui.Tooltip(
        tooltipTriggerEl))
    //return responseHtml
}


function changeWapperShow(elemento) {

    mainWrapper.className = 'd-none'
    productsWrapper.className = 'd-none'
    resumeWrapper.className = 'd-none'

    btnCart.classList.add('d-none')
    searchInput.classList.add('d-none')

    if (elemento === 'main') {
        mainWrapper.className = 'd-block'
        btnCart.classList.remove('d-none')
        searchInput.classList.remove('d-none')
    } else if (elemento === 'products') {
        productsWrapper.className = 'd-block'
        btnCart.classList.remove('d-none')
        searchInput.classList.remove('d-none')
    } else if (elemento === 'resume') {
        resumeWrapper.className = 'd-block'
    }
}

function searchProducts() {

    const filterInput = document.querySelector('#input_filter')
    const cards = document.getElementById('product_list').querySelectorAll('.product-item')

    filterInput.addEventListener('keyup', () => {
        var filterValue = filterInput.value.toLowerCase()

        cards.forEach(function (card) {
            var title = card.querySelector('.productname').textContent.toLowerCase()
            if (title.includes(filterValue)) {
                card.style.display = 'block'
            } else {
                card.style.display = 'none'
            }
        })
    })
}

function hiddenModals() {
    var modals = document.querySelectorAll('.modal');
    modals.forEach(function (modal) {
        const myModal = new coreui.Modal(modal, {
            keyboard: false
        })
        myModal.hide(); // Ocultar el modal.
    });
}

