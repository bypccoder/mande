<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            <label for="category_id">Categoria</label>
            <select class="form-control" name="category_id" id="">
            @foreach ($categorias as $categoria )
                <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
            @endforeach
            </select>
        </div>
        <div class="form-group">
            {{ Form::label('name') }}
            {{ Form::text('name', $subCategory->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('description') }}
            {{ Form::text('description', $subCategory->description, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
            {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Imagen de portada') }}
            {{ Form::file('cover', ['class' => 'form-control' . ($errors->has('cover') ? ' is-invalid' : '')]) }}
            {!! $errors->first('cover', '<div class="invalid-feedback">:message</div>') !!}
        </div>
       
    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>