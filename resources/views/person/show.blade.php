@extends('layouts.app')

@section('template_title')
    {{ $person->name ?? "{{ __('Show') Person" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Person</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('people.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $person->name }}
                        </div>
                        <div class="form-group">
                            <strong>Lastname 1:</strong>
                            {{ $person->lastname_1 }}
                        </div>
                        <div class="form-group">
                            <strong>Lastname 2:</strong>
                            {{ $person->lastname_2 }}
                        </div>
                        <div class="form-group">
                            <strong>Document Type Id:</strong>
                            {{ $person->document_type_id }}
                        </div>
                        <div class="form-group">
                            <strong>Document:</strong>
                            {{ $person->document }}
                        </div>
                        <div class="form-group">
                            <strong>Status Id:</strong>
                            {{ $person->status_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
