<div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
    @foreach ($categories as $category)
        <div class="col mb-5" style="height:190px; overflow: hidden">
            <a href="#" class="categoria-item" onclick="currentCategory(this)" data-category="{{ $category->id }}">
                <div class="card h-100 pe-none overflow-hidden">
                    <!-- Sale badge-->
                    <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">
                        {{ $category->name }}</div>
                    <!-- Product image-->
                    <img class="card-img-top"
                        src="{{ empty($category->cover_image) ? 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg' : asset('storage/' . $category->cover_image) }}"
                        alt="...">
                    <!--<img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="...">-->
                </div>
            </a>
        </div>
    @endforeach
</div>

<ul class="nav bg radius nav-pills nav-fill mb-3 bg" role="tablist">
    <li class="nav-item">
        <a class="nav-link active show" data-toggle="pill" href="#nav-tab-card">
            <i class="fa fa-tags"></i> All</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="pill" href="#nav-tab-paypal">
            <i class="fa fa-tags "></i> Category 1</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="pill" href="#nav-tab-bank">
            <i class="fa fa-tags "></i> Category 2</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="pill" href="#nav-tab-bank">
            <i class="fa fa-tags "></i> Category 3</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="pill" href="#nav-tab-bank">
            <i class="fa fa-tags "></i> Category 4</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="pill" href="#nav-tab-bank">
            <i class="fa fa-tags "></i> Category 5</a>
    </li>
</ul>
