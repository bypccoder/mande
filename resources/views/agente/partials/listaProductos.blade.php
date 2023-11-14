<div class="container">
    <!--<div class="row justify-content-center">
        <div class="col-12 mb-3">
            <form class="card card-sm search-box border-0">
                <div class="card-body row no-gutters align-items-center">
                    <div class="col">
                        <input id="input_filter"
                            class="form-control form-control-lg form-control-borderless input_filter" type="search"
                            placeholder="Buscar por nombre..">
                    </div>

                    <div class="col-auto">
                        <button class="btn btn-lg btn-primary text-white pe-none" type="submit"><i
                                class="las la-search"></i> Buscar</button>
                    </div>

                </div>
            </form>
        </div>

    </div>
<!-- /.row -->
    <div id="product_list" class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
        @if (count($products))
            @foreach ($products as $product)
            
                <div class="col-sm-6 col-lg-3">
                    <div class="menu-6-item bg-white position-relative product-item"
                        data-sub-category="{{ $product->sub_category_id ? $product->sub_category_id : $sub_category_id }}"
                        data-product-id="{{ $product->id }}" data-product-type="{{ $product->product_type_id }}"
                        data-category-id="{{ $product->category_id }}" data-product-code="{{ $product->code }}">

                        <!-- IMAGE -->
                        <div class="menu-6-img position-relative">
                            @if ($product->is_iced )
                                <a href="#"
                                    class="btn {{ $product->in_cart && $product->in_iced ? 'btn-info pe-none' : 'btn-secondary' }} btn-iced text-white"
                                    data-price="{{ $sub_category->iced_price }}" data-coreui-toggle="tooltip"
                                    data-coreui-placement="top"
                                    title="HELADA, precio adicional {{ $sub_category->iced_price }}"><i class="las la-ice-cream pe-none"></i></a>
                            @endif
                            <div class="hover-overlay">
                                <!-- Image -->
                                <img class="img-fluid" src="{{ asset('storage/' . $product->cover_image) }}"
                                    alt="{{ $product->name }}">

                                @if ($product->stock_enable == 1)
                                    <!-- Item Stock -->
                                    <span class="item-code {{ $product->stock ? 'bg-tra-dark' : 'bg-danger' }}">Stock:
                                        {{ $product->stock ? $product->stock : 0 }}</span>
                                    @if (in_array($product->product_type_id, [1, 2, 3]))
                                        <!-- Item Type -->
                                        <span class="item-code bg-tra-dark" style="margin-top: 25px;">{{ $product->product_type }}</span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <!-- TEXT -->
                        <div class="menu-6-txt rel">
                            <!-- Title -->
                            <h6 class="productname">{{ $product->name }}</h6>
                            <!-- Description -->
                            <!--<p class="grey-color">{{ $product->description }}</p>-->
                            @php
                                $price = in_array($product->category_id, $discount) ? '0' : $product->sales_price;
                                if ($generalPrice !== null) {

                                    $price = (in_array($product->product_type_id, [1,2])) ? $generalPrice[$product->product_type_id] : $product->sales_price;
                                }
                            @endphp
                            <!-- Price -->
                            <div class="menu-6-price bg-coffee">
                                <h5 class="price">S/.<span>{{ $price }}</span></h5>
                            </div>


                            <!-- Add To Cart -->
                            <input type="text" class="product-quantity d-none" name="quantity" value="1"
                                size="2">
                            <div
                                class="add-to-cart-ui btn-salmon {{ (isset($product->in_cart) && $product->in_cart) || $product->stock <= 0 ? 'disabled pe-none' : '' }} ">
                                <a href="#" class="text-white add-to-cart"
                                    data-product-type="{{ $product->product_type_id }}"><i
                                        class="las la-shopping-bag pe-none"></i>
                                    Agregar</a>
                            </div>

                        </div>

                    </div>
                </div>
            @endforeach
        @else
            <div class="col-4">
                <div class="text-center text-white">
                    <div class="icon">
                        <i class="las la-bone"></i>
                    </div>
                    <p>No existen datos para esta Categoria</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Complementary Menu -->
    <div class="modal fade" id="modal_complementary_menu" tabindex="-1"
        aria-labelledby="modal_complementary_menu_Label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_complementary_menu_Label">Menu</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="bg-light p-2 mb-3 entry_title">Platos de Entrada</h5>
                    <div id="menu_entrada">
                    </div>
                    <h5 class="bg-light p-2 mb-3 additional_title">Adicionales</h5>
                    <div id="menu_adicional">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
