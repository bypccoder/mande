@extends('layouts.app')

@section('template_title')
    {{ $product->name ?? "{{ __('Show') Product" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Product</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('products.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Sub Category Id:</strong>
                            {{ $product->sub_category_id }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $product->description }}
                        </div>
                        <div class="form-group">
                            <strong>Status Id:</strong>
                            {{ $product->status_id }}
                        </div>
                        <div class="form-group">
                            <strong>Buy Price:</strong>
                            {{ $product->buy_price }}
                        </div>
                        <div class="form-group">
                            <strong>Sales Price:</strong>
                            {{ $product->sales_price }}
                        </div>
                        <div class="form-group">
                            <strong>Cover Image:</strong>
                            {{ $product->cover_image }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
