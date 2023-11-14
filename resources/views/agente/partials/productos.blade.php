<div class="row">
    @if ( count($products) )
        @foreach ($products as $product )
            <div class="col-xl-3 col-xxl-2 col-sm-6">
                <div class="card dishe-bx b-hover style-1">
                    <div class="card-body pb-0 pt-3">
                        <div class="text-center mb-2">
                            <img src="{{ Storage::url($product->cover_image) }}" alt="">
                        </div>
                    </div>
                    <div class="card-footer border-0 pt-2">
                        <div class="common d-flex align-items-center justify-content-between">
                            <div>
                                <a href="javascript:void(0);">
                                    <h4>{{ $product->name }}</h4>
                                </a>
                                <h3 class=" mb-0 text-primary">{{ $product->sales_price }}</h3>
                            </div>
                            <div class="plus c-pointer">
                                <div class="sub-bx">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <h3 class="w-100 text-center">No existen datos para esta Categoria</h3>
    @endif
</div>