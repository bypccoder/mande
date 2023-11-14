<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('person_id') }}
            {{ Form::text('person_id', $order->person_id, ['class' => 'form-control' . ($errors->has('person_id') ? ' is-invalid' : ''), 'placeholder' => 'Person Id']) }}
            {!! $errors->first('person_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('date_order') }}
            {{ Form::text('date_order', $order->date_order, ['class' => 'form-control' . ($errors->has('date_order') ? ' is-invalid' : ''), 'placeholder' => 'Date Order']) }}
            {!! $errors->first('date_order', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('amount') }}
            {{ Form::text('amount', $order->amount, ['class' => 'form-control' . ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
            {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('form_method_id') }}
            {{ Form::text('form_method_id', $order->form_method_id, ['class' => 'form-control' . ($errors->has('form_method_id') ? ' is-invalid' : ''), 'placeholder' => 'Form Method Id']) }}
            {!! $errors->first('form_method_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('payment_method_id') }}
            {{ Form::text('payment_method_id', $order->payment_method_id, ['class' => 'form-control' . ($errors->has('payment_method_id') ? ' is-invalid' : ''), 'placeholder' => 'Payment Method Id']) }}
            {!! $errors->first('payment_method_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('status_id') }}
            {{ Form::text('status_id', $order->status_id, ['class' => 'form-control' . ($errors->has('status_id') ? ' is-invalid' : ''), 'placeholder' => 'Status Id']) }}
            {!! $errors->first('status_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>