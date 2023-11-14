@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Sub Category
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default bg-white">
                    <div class="card-header">
                        <span class="card-title">{{ __('Editar') }} Categoria</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('sub-categories.update', $subCategory->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('sub-category.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
