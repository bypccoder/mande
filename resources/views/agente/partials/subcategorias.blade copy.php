<a href="#" class="btn btn-lg btn-salmon tra-salmon-hover position-absolute btn-back-view" style="top:10px;left:3rem"><i class="la la-angle-left"></i> Volver</a>

<div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">

    @if ( count($subcategories) )
    @foreach ($subcategories as $subcategory )

    <div class="col mb-5" style="height:190px; overflow: hidden">
        <a href="#" class="productos-mostrar" data-subcategory="{{ $subcategory->id }}">
            <div class="card h-100 pe-none overflow-hidden">
                <!-- Sale badge-->
                <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">{{ $subcategory->name }}</div>
                <!-- Product image-->
                <img class="card-img-top" src="{{ (empty($subcategory->cover_image)) ? 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg' : asset("storage/".$subcategory->cover_image) }}" alt="...">
                <!--<img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="...">-->
            </div>
        </a>
    </div>

    @endforeach
    @else
    <div class="w-75 m-auto">
            <div class="text-center text-white">
                <div class="icon mb-2" style="font-size:6rem">
                    <i class="las la-bone"></i>
                </div>
                <p class="fs-3">No existen datos para esta Categoria</p>
            </div>
        </div>
    @endif

</div>