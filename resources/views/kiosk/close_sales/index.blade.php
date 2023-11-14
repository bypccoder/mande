@extends('layouts.app')

@section('template_title')
    Close Sales
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <!-- Page header -->
                <div class="mb-5">
                    <h3 class="mb-0"><span id="card_title">{{ __('Cierre de Ventas') }}</span></h3>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card bg-white">
                    <div class="card-body p-5">
                        <div class="row">
                            <div class="col-6">
                                <div class="chart-container">
                                    <canvas id="chart"></canvas>
                                </div>
                            </div>
                            <div class="col-6">
                                <table class="table">
                                    <tbody>
                                        @foreach ($total_sales as $item)
                                            <tr>
                                                <td>{{ $item->payment_method }}</td>
                                                <td>{{ $item->total_amount }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td>Total : </td>
                                            <td>{{ $total }}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 d-flex justify-content-center align-items-center p-2">
                                <a href="#" class="btn btn-success btn-lg text-white">Cerrar Ventas</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ url('') }}/js/util.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"></script>
    <script>
        CRUD.sendData('close-sales',{}).then(response => {
            
            if( response.code !== 200 ){
                mostrarMensajeAlerta('400');
                return 0
            }

            const PLATFORMS = response.data.methods
            const COLORS = response.data.colors
            const HOVERCOLORS = response.data.hoverColors
            const VALUES = response.data.values.map(item => item.porcentaje)
            console.log(VALUES)

            const data = {
                labels: PLATFORMS,
                datasets: [{
                    label: "Devices data",
                    backgroundColor: COLORS,
                    borderWidth: 0,
                    hoverBackgroundColor: HOVERCOLORS,
                    data: VALUES,
                }]
            };

            const options = {
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Ventas por Método de Pago',
                        font: {
                            size: 24
                        },
                    },
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 20,
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return `${label}: ${percentage}%`;
                            }
                        }
                    },
                },
                layout: {
                    padding: {
                        top: 20,
                        left: 30,
                        right: 30,
                        bottom: 20
                    }
                },
            };

            new Chart('chart', {
                type: 'doughnut',
                options: options,
                data: data
            });
        })
    </script>
@endsection
