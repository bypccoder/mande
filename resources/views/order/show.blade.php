@extends('layouts.app')

@section('template_title')
    {{ $order->name ?? "{{ __('Show') Order" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Order</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('orders.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Person Id:</strong>
                            {{ $order->person_id }}
                        </div>
                        <div class="form-group">
                            <strong>Date Order:</strong>
                            {{ $order->date_order }}
                        </div>
                        <div class="form-group">
                            <strong>Amount:</strong>
                            {{ $order->amount }}
                        </div>
                        <div class="form-group">
                            <strong>Form Method Id:</strong>
                            {{ $order->form_method_id }}
                        </div>
                        <div class="form-group">
                            <strong>Payment Method Id:</strong>
                            {{ $order->payment_method_id }}
                        </div>
                        <div class="form-group">
                            <strong>Status Id:</strong>
                            {{ $order->status_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
