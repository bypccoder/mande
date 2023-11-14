<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            <select class="form-control" name="sub_category_id" id="sub_category_id">
            @foreach ($subcategories as $subcategory)
                <option {{ $subcategory->id == $product->sub_category_id ? 'selected' : '' }} value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
            @endforeach
            </select>            
        </div>
        <div class="form-group">
            {{ Form::label('name') }}
            {{ Form::text('name', $product->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'name']) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('description') }}
            {{ Form::text('description', $product->description, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
            {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('buy_price') }}
            {{ Form::text('buy_price', $product->buy_price, ['class' => 'form-control' . ($errors->has('buy_price') ? ' is-invalid' : ''), 'placeholder' => 'Buy Price']) }}
            {!! $errors->first('buy_price', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('sales_price') }}
            {{ Form::text('sales_price', $product->sales_price, ['class' => 'form-control' . ($errors->has('sales_price') ? ' is-invalid' : ''), 'placeholder' => 'Sales Price']) }}
            {!! $errors->first('sales_price', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('cover') }}
            {{ Form::file('cover', ['class' => 'form-control' . ($errors->has('cover') ? ' is-invalid' : '')]) }}
            {!! $errors->first('cover', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>