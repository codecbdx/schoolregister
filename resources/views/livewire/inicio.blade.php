@section('title', __('Home'))

<div>
    <h4 class="mb-3 mb-md-0 text-dark">
        {{ __('Welcome back!') }} {{ Auth::user()->name }} {{ Auth::user()->paternal_lastname }} {{ Auth::user()->maternal_lastname }}</h4>
    @if ($adminSection === true)
        <div class="row mt-4">
            <div class="col-12 col-xl-12 stretch-card">
                <div class="row flex-grow">
                    <div class="col-md-4 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-9">
                                        <div class="d-flex justify-content-between align-items-baseline">
                                            <h6 class="card-title text-dark mb-2">{{ __('Students') }}</h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-md-12 col-xl-12">
                                                @php
                                                    $totalStudents = 0;
                                                @endphp
                                                @foreach ($students as $alumno)
                                                    @php
                                                        $totalStudents++;
                                                    @endphp
                                                @endforeach
                                                @php
                                                    // Obtener el último y el penúltimo mes
                                                    $studentData = collect($studentData);
                                                    $lastMonth = $studentData->last();
                                                    $previousMonth = $studentData->slice(-2, 1)->first();

                                                    // Obtener los valores del último y penúltimo mes
                                                    $lastMonthCount = $lastMonth['student_count'] ?? 0;
                                                    $previousMonthCount = $previousMonth['student_count'] ?? 0;

                                                    // Calcular el porcentaje de crecimiento
                                                    $growthPercentage = 0;
                                                    if ($previousMonthCount > 0) {
                                                        $growthPercentage = (($lastMonthCount - $previousMonthCount) / $previousMonthCount) * 100;
                                                    }
                                                    $formattedPercentage = number_format($growthPercentage, 1); // Formato a una cifra decimal
                                                @endphp
                                                @if ($growthPercentage > 0)
                                                    <h4 class="mb-2 text-primary d-inline-block">{{ $totalStudents }}</h4>
                                                    <span class="text-success">
                                                +{{ $formattedPercentage }}%
                                                <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                            </span>
                                                @elseif ($growthPercentage < 0)
                                                    <h4 class="mb-2 text-primary d-inline-block">{{ $totalStudents }}</h4>
                                                    <span class="text-danger">
                                                {{ $formattedPercentage }}%
                                                <i data-feather="arrow-down" class="icon-sm mb-1"></i>
                                            </span>
                                                @else
                                                    <h4 class="mb-2 text-primary d-inline-block">{{ $totalStudents }}</h4>
                                                    <span class="text-dark">
                                                {{ $formattedPercentage }}%
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 d-flex justify-content-center align-items-center">
                                        <div
                                            class="bg-primary text-center text-white rounded d-flex justify-content-center align-items-center"
                                            style="width: 100%; height: 100%;">
                                            <i class="mdi mdi-school" style="font-size: 32px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-9">
                                        <div class="d-flex justify-content-between align-items-baseline">
                                            <h6 class="card-title text-dark mb-2">{{ __('Balance') }}</h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 col-md-12 col-xl-5">
                                                @php
                                                    $totalCargo = 0;
                                                    $totalAbono = 0;
                                                @endphp
                                                @foreach ($list_conceptos_pago_alumnos as $concepto_pago_alumno)
                                                    @php
                                                        $totalCargo += $concepto_pago_alumno->cargo;
                                                        $totalAbono += $concepto_pago_alumno->abono;
                                                    @endphp
                                                @endforeach
                                                @php
                                                    $totalSaldo = $totalCargo - $totalAbono;

                                                    if ($totalSaldo < 0) {
                                                        $displaySaldo = number_format(abs($totalSaldo), 2);
                                                        $saldoStyle = 'text-success';
                                                        $sign = '+';
                                                        $icon = 'arrow-up';
                                                    } elseif ($totalSaldo > 0) {
                                                        $displaySaldo = number_format($totalSaldo, 2);
                                                        $saldoStyle = 'text-danger';
                                                        $sign = '-';
                                                        $icon = 'arrow-down';
                                                    } else {
                                                        $displaySaldo = number_format($totalSaldo, 2);
                                                        $saldoStyle = 'text-muted';
                                                        $sign = '';
                                                        $icon = '';
                                                    }
                                                @endphp
                                                <h5 class="mb-2 {{ $saldoStyle }}">
                                                    {{ $sign }}{{ $displaySaldo }}
                                                    <i data-feather="{{ $icon }}" class="icon-sm mb-1"></i>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 d-flex justify-content-center align-items-center">
                                        <div
                                            class="bg-danger text-center text-white rounded d-flex justify-content-center align-items-center"
                                            style="width: 100%; height: 100%;">
                                            <i class="mdi mdi-trending-down" style="font-size: 32px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-9">
                                        <div class="d-flex justify-content-between align-items-baseline">
                                            <h6 class="card-title text-dark mb-2">{{ __('toPay') }}</h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 col-md-12 col-xl-5">
                                                @php
                                                    $totalAbono = 0;
                                                @endphp
                                                @foreach ($list_conceptos_pago_alumnos as $concepto_pago_alumno)
                                                    @php
                                                        $totalAbono += $concepto_pago_alumno->abono; // Sumar abonos
                                                    @endphp
                                                @endforeach
                                                @php
                                                    if ($totalAbono < 0) {
                                                        $displayAbono = number_format(abs($totalAbono), 2);
                                                        $abonoStyle = 'text-danger';
                                                        $sign = '-';
                                                        $icon = 'arrow-down';
                                                    } elseif ($totalAbono > 0) {
                                                        $displayAbono = number_format($totalAbono, 2);
                                                        $abonoStyle = 'text-success';
                                                        $sign = '+';
                                                        $icon = 'arrow-up';
                                                    } else {
                                                        $displayAbono = number_format($totalAbono, 2);
                                                        $abonoStyle = 'text-muted';
                                                        $sign = '';
                                                        $icon = '';
                                                    }
                                                @endphp
                                                <h5 class="mb-2 {{ $abonoStyle }}">
                                                    {{ $sign }}{{ $displayAbono }}
                                                    <i data-feather="{{ $icon }}" class="icon-sm mb-1"></i>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 d-flex justify-content-center align-items-center">
                                        <div
                                            class="bg-success text-center text-white rounded d-flex justify-content-center align-items-center"
                                            style="width: 100%; height: 100%;">
                                            <i class="mdi mdi-trending-up" style="font-size: 32px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-12 grid-margin stretch-card">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline mb-4 mb-md-3">
                                    <h6 class="card-title text-dark mb-0">{{ __('Registered users per month') }}</h6>
                                </div>
                                <div class="flot-wrapper">
                                    <div id="apexChartStudents" class="flot-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@if ($adminSection === true)
    <script src="{{ asset('assets/vendors/apexcharts/apexcharts.min.js') }}"></script>
@endif
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>

@if ($adminSection === true)
    <script>
        const studentData = @json($studentData);
        const lastSixMonths = studentData.slice(-12);
        const categories = lastSixMonths.map(item => item.month_year);
        const values = lastSixMonths.map(item => item.student_count);

        if ($('#apexChartStudents').length) {
            const options = {
                chart: {
                    type: 'line',
                    height: 280,
                    sparkline: {
                        enabled: false,
                    },
                },
                stroke: {
                    width: 3,
                    curve: "smooth",
                    lineCap: "round"
                },
                colors: ['#727cf5'],
                series: [{
                    name: '{{ __('Enrolled Students') }}',
                    data: values,
                }],
                xaxis: {
                    categories,
                    crosshairs: {
                        width: 1,
                    },
                },
                tooltip: {
                    fixed: {
                        enabled: false,
                    },
                    x: {
                        show: true,
                    },
                    y: {
                        formatter: function (value) {
                            return Math.floor(value);
                        },
                        title: {
                            formatter: function () {
                                return 'Total';
                            },
                        },
                    },
                    marker: {
                        show: false,
                    },
                },
            };

            new ApexCharts(document.querySelector("#apexChartStudents"), options).render();
        }
    </script>
@endif
