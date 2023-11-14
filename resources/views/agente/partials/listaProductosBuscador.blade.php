<div class="container">
    <div id="product_list" class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
        @if (count($products))
            @foreach ($products as $product)
                <div class="col-sm-6 col-lg-3">
                    <div class="menu-6-item bg-white position-relative product-item"
                        data-sub-category="{{ $product->sub_category_id ? $product->sub_category_id : '' }}"
                        data-product-id="{{ $product->id }}" data-product-type="{{ $product->product_type_id }}"
                        data-category-id="{{ $product->category_id }}" data-product-code="{{ $product->code }}">

                        <!-- IMAGE -->
                        <div class="menu-6-img position-relative">
                            @if ($product->is_iced)
                                <a href="#"
                                    class="btn {{ $product->in_cart && $product->in_iced ? 'btn-info pe-none' : 'btn-secondary' }} btn-iced text-white"
                                    data-price="{{ $product->iced_price }}" data-coreui-toggle="tooltip"
                                    data-coreui-placement="top"
                                    title="HELADA, precio adicional {{ $product->iced_price }}"><i
                                        class="las la-mountain pe-none"></i></a>
                            @endif
                            <div class="hover-overlay">
                                <!-- Image -->
                                <img class="img-fluid" src="{{ asset('storage/' . $product->cover_image) }}"
                                    alt="{{ $product->name }}">
                                @if ($product->stock_enable == 1 && $product->product_type_id == 0)
                                    <!-- Item Stock -->
                                    <span class="item-code {{ $product->stock ? 'bg-tra-dark' : 'bg-danger' }}">Stock:
                                        {{ $product->stock ? $product->stock : 0 }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- TEXT -->
                        <div class="menu-6-txt rel">
                            <!-- Title -->
                            <h5 class="productname">{{ $product->name }}</h5>
                            <!-- Description -->
                            <!--<p class="grey-color">{{ $product->description }}</p>-->
                            @php
                                $price = in_array($product->category_id, $discount) ? '0' : $product->sales_price;
                                if ($generalPrice !== null) {
                                    $price = $generalPrice;
                                }
                            @endphp
                            <!-- Price -->
                            <div class="menu-6-price bg-coffee">
                                <h5 class="price">S/.<span>{{ $price }}</span></h5>
                            </div>


                            <!-- Add To Cart -->
                            @php
                                $inCart = in_array($product->id, $cartProducts) && $product->product_type_id == 1 ? 'disabled pe-none' : '';
                            @endphp
                            <input type="text" class="product-quantity d-none" name="quantity" value="1"
                                size="2">
                            <div class="add-to-cart-ui btn-salmon {{ isset($product->in_cart) && $product->in_cart || $product->stock <= 0 ? 'disabled pe-none' : '' }}">
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

</div>
