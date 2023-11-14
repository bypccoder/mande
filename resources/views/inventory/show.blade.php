@extends('layouts.app')

@section('template_title')
    {{ $inventory->name ?? "{{ __('Show') Inventory" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Inventory</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('inventories.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Product Id:</strong>
                            {{ $inventory->product_id }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $inventory->description }}
                        </div>
                        <div class="form-group">
                            <strong>Quantity:</strong>
                            {{ $inventory->quantity }}
                        </div>
                        <div class="form-group">
                            <strong>Vouchertype:</strong>
                            {{ $inventory->voucherType }}
                        </div>
                        <div class="form-group">
                            <strong>Voucherserial:</strong>
                            {{ $inventory->voucherSerial }}
                        </div>
                        <div class="form-group">
                            <strong>Vouchernumber:</strong>
                            {{ $inventory->voucherNumber }}
                        </div>
                        <div class="form-group">
                            <strong>Vouchertax:</strong>
                            {{ $inventory->voucherTax }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
