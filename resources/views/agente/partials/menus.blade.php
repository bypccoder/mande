<div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
    @foreach ($menus as $menu )        
    <div class="col mb-5">
        <a href="#" class="categoria-item" data-category="{{ $category->id }}">
            <div class="card h-100 pe-none">
                <!-- Sale badge-->
                <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">{{ $category->name }}</div>
                <!-- Product image-->
                <!--<img class="card-img-top" src="{{ (empty($category->cover_image)) ? 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg' : $category->cover_image }}" alt="...">-->
                <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="...">
            </div>
        </a>
    </div>

    @endforeach
</div>