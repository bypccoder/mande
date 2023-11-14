<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('product_id') }}
            {{ Form::text('product_id', $inventory->product_id, ['class' => 'form-control' . ($errors->has('product_id') ? ' is-invalid' : ''), 'placeholder' => 'Product Id']) }}
            {!! $errors->first('product_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('description') }}
            {{ Form::text('description', $inventory->description, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
            {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('quantity') }}
            {{ Form::text('quantity', $inventory->quantity, ['class' => 'form-control' . ($errors->has('quantity') ? ' is-invalid' : ''), 'placeholder' => 'Quantity']) }}
            {!! $errors->first('quantity', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('voucherType') }}
            {{ Form::text('voucherType', $inventory->voucherType, ['class' => 'form-control' . ($errors->has('voucherType') ? ' is-invalid' : ''), 'placeholder' => 'Vouchertype']) }}
            {!! $errors->first('voucherType', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('voucherSerial') }}
            {{ Form::text('voucherSerial', $inventory->voucherSerial, ['class' => 'form-control' . ($errors->has('voucherSerial') ? ' is-invalid' : ''), 'placeholder' => 'Voucherserial']) }}
            {!! $errors->first('voucherSerial', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('voucherNumber') }}
            {{ Form::text('voucherNumber', $inventory->voucherNumber, ['class' => 'form-control' . ($errors->has('voucherNumber') ? ' is-invalid' : ''), 'placeholder' => 'Vouchernumber']) }}
            {!! $errors->first('voucherNumber', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('voucherTax') }}
            {{ Form::text('voucherTax', $inventory->voucherTax, ['class' => 'form-control' . ($errors->has('voucherTax') ? ' is-invalid' : ''), 'placeholder' => 'Vouchertax']) }}
            {!! $errors->first('voucherTax', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>