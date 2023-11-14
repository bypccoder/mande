@extends('layouts.app')

@section('template_title')
    XML/CDR
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card" style="border-color: #8e1f4f;">
                <div class="card-header" style="background-color: #8e1f4f; color: white;">
                    <h3 class="card-title fs-4 text-center">Descargar Archivos por Rango de Fechas</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('descargar_xml_cdr.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                            <input type="date" name="fecha_fin" class="form-control" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" style="background-color: #8e1f4f; border-color: #8e1f4f;">Descargar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
