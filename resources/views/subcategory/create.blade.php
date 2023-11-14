@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Sub Category
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default bg-white">
                    <div class="card-header">
                        <span class="card-title">{{ __('Añadir') }} Categoria</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('sub-categories.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('sub-category.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
