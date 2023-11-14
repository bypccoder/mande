<ul class="nav bg radius nav-pills nav-fill mb-3 bg">
    @foreach ($categories as $category)
        <li class="nav-item">
            <a class="nav-link categoria-item" onclick="currentCategory(this)" data-category="{{ $category->id }}"
                href="#">
                <i class="fa fa-tags"></i> {{ $category->name }}
            </a>
        </li>
    @endforeach
</ul>
