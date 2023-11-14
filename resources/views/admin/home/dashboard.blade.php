@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="text-dark fs-1 me-3">
                    <i class="las la-clock"></i>
                </div>
                <div>
                    <div class="fs-6 fw-semibold text-primary">{{ $ordersToday }}</div>
                    <div class="text-medium-emphasis text-uppercase fw-semibold small">Ventas del Dia</div>
                </div>
            </div>
        </div>
    </div>
    <!-- END CARD -->
    <div class="col-6 col-lg-3">
        <div class="card">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="text-dark fs-1 me-3">
                    <i class="las la-calendar"></i>
                </div>
                <div>
                    <div class="fs-6 fw-semibold text-primary">{{ $ordersWeek }}</div>
                    <div class="text-medium-emphasis text-uppercase fw-semibold small">Ventas de la Semana</div>
                </div>
            </div>
        </div>
    </div>
    <!-- END CARD -->
</div>

<div class="row mt-3">
    <div class="col-sm-12">
        <div class="card bg-white">
            <div class="card-header d-flex justify-content-between">
                <span id="card_title">
                    {{ __('Stock en Sistema') }}
                </span>                
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table">
                        <thead class="thead">
                            <tr>
                                <th>#</th>
                                <th>PRODUCTO</th>
                                <th>CANTIDAD</th>
                                <th>ESTADO</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script src="{{ url('') }}/js/util.js"></script>
<script>
    $(function() {


        var table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.index') }}",
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'product',
                    name: 'product'
                },
                {
                    data: 'quantity',
                    name: 'quantity'
                },
                {
                    data: 'alert',
                    name: 'alert'
                },
            ],

            language: {
                paginate: {
                    previous: '<<',
                    next: '>>'
                }
            }
        });

    })
</script>
@endsection