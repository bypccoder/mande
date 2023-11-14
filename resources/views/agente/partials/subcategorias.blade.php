    <ul class="nav bg radius nav-pills nav-fill mb-3 bg">
        <li class="nav-item">
            <a class="nav-link btn-back-view" href="#"><i class="las la-home pe-none"></i></a>
        </li>
        @if (count($subcategories))
            @foreach ($subcategories as $subcategory)
                <li class="nav-item">
                    <a class="nav-link productos-mostrar" data-subcategory="{{ $subcategory->id }}" href="#">
                        <i class="fa fa-tags"></i> {{ $subcategory->name }}
                    </a>
                </li>
            @endforeach
        @else
            <li class="nav-item d-flex align-items-center justify-content-center">
                <span class="text-light">No existen productos en esta categoria.</a>
            </li>
        @endif

    </ul>
