<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Payment schedule') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body style="background: #ffffff;">
<table style="width: 100%;">
    <tr>
        <td style="text-align: center;">
            <img src="{{ env('AWS_URL') }}{{ $configuracion->system_logo }}" style="width: 420px;">
        </td>
    </tr>
    <tr>
        <td style="text-align: center;">
            <p>{{ $address }}, TEL. {{ $telephone }}.</p>
        </td>
    </tr>
</table>

<h4 class="text-center mt-4 mb-2">{{ $title }}</h4>
<p><b>{{ __('Name') }}:</b> {{ $nombre_alumno }}</p>
<p><b>{{ __('CURP') }}:</b> {{ $curp_alumno }}</p>
<p><b>{{ __('Date') }}:</b> {{ $date }}</p>

<table class="table table-bordered mt-4">
    <tr>
        <th>
            {{ __('Invoice')  }}
        </th>
        <th>
            {{ __('Due date')  }}
        </th>
        <th>
            {{ __('Concept')  }}
        </th>
        <th>
            {{ __('Balance')  }}
        </th>
        <th>
            {{ __('State')  }}
        </th>
    </tr>
    @foreach($pagos_alumno as $pago_alumno)
        <tr>
            <td>{{ $pago_alumno->folio }}</td>
            <td>{{  date('d-m-Y', strtotime($pago_alumno->fecha_vencimiento)) }}</td>
            <td>{{ Str::limit($pago_alumno->concepto, 20) }}
            <td>
                {{ number_format(($pago_alumno->cargo - $pago_alumno->abono), 2) }}
            </td>
            <td>
                @if ($pago_alumno->fecha_vencimiento < $fecha_actual)
                    <span
                        class="badge badge-danger text-white">{{ __('Defeated') }}</span>
                @elseif ($pago_alumno->estado_pago == 1)
                    <span class="badge badge-success">{{ __('Paid') }}</span>
                @elseif ($pago_alumno->estado_pago == 0)
                    <span
                        class="badge badge-warning text-white">{{ __('Outstanding balance') }}</span>
                @endif
            </td>
        </tr>
    @endforeach
</table>

<div class="mt-5">
    <hr style="border: none; border-top: 1px solid #000; margin: 0 auto; width: 50%;">
    <p style="margin: 0 auto; width: 50%;"
       class="text-center">{{ __('Signature of conformity and knowledge of what is stated in the present document') }}</p>
</div>
</body>
</html>
