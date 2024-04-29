<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Credential') }}</title>
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

<h4 class="text-center mt-4 mb-4">{{ $title }} {{ __('of') }} {{ $alumno->nombre }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}</h4>

<div style="width: 400px; height: 260px; margin: 0 auto; border: 1px solid #000!important;">
    <div
        style="width: 400px; height: 90px; margin: 0 auto; border-bottom: 1px solid #000!important; text-align: center;">
        <img src="{{ env('AWS_URL') }}{{ $configuracion->system_logo }}"
             style="max-width: 63%; max-height: 63%; margin-top: 3px;">
        <p style="width:80%; margin: 0 auto; text-align: center; font-size: 0.8em;">{{ $address }},
            TEL. {{ $telephone }}.</p>
    </div>
    <div style="display: block; float: left; width: 37%; text-align: center;">
        <img
            src="{{ $usuario && $usuario->user_image ? (env('AWS_URL') . $usuario->user_image) : asset('assets/images/user.png') }}"
            style="width: 113px; height: 113px; margin-top: 8px">
        <p style="font-size: 0.9em;">{{ __('Validity') }}</p>
        <p style="font-size: 0.8em;"><b>{{ $date }}</b></p>
    </div>
    <div style="display:block; float: right; width: 63%;">
        <p style="font-size: 0.9em; margin-top: 20px;"><b>{{ __('Name') }}
                :</b> {{ $alumno->nombre }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}</p>
        <p style="font-size: 0.9em;"><b>{{ __('CURP') }}:</b> {{ $alumno->curp }}</p>
        <p style="font-size: 0.9em;"><b>{{ __('Email Address') }}:</b> {{ $alumno->correo }}</p>
        <p style="font-size: 0.9em;"><b>{{ __('Tutor') }}
                :</b> {{ $alumno->nombre_tutor }} {{ $alumno->apellido_paterno_tutor }} {{ $alumno->apellido_materno_tutor }}
        </p>
        <p style="font-size: 0.9em;"><b>{{ __('Emergency Phone') }}:</b> {{ $alumno->telefono_emergencia }}</p>
    </div>
</div>


<div style="width: 400px; height: 260px; margin: 0 auto; margin-top: 20px; border: 1px solid #000!important;">
    <div
        style="width: 400px; height: 90px; margin: 0 auto; border-bottom: 1px solid #000!important; text-align: center;">
        <img src="{{ env('AWS_URL') }}{{ $configuracion->system_logo }}"
             style="max-width: 63%; max-height: 63%; margin-top: 3px;">
        <p style="width:80%; margin: 0 auto; text-align: center; font-size: 0.8em;">{{ $address }},
            TEL. {{ $telephone }}.</p>
    </div>
    <div style="display: block; float: left; width: 37%; text-align: center;">
        <img
            src="{{ $usuario && $usuario->user_image ? (env('AWS_URL') . $usuario->user_image) : asset('assets/images/user.png') }}"
            style="width: 113px; height: 113px; margin-top: 8px">
        <p style="font-size: 0.9em;">{{ __('Validity') }}</p>
        <p style="font-size: 0.8em;"><b>{{ $date }}</b></p>
    </div>
    <div style="display:block; float: right; width: 63%;">
        <p style="font-size: 0.9em; margin-top: 20px;"><b>{{ __('Name') }}
                :</b> {{ $alumno->nombre }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}</p>
        <p style="font-size: 0.9em;"><b>{{ __('CURP') }}:</b> {{ $alumno->curp }}</p>
        <p style="font-size: 0.9em;"><b>{{ __('Email Address') }}:</b> {{ $alumno->correo }}</p>
        <p style="font-size: 0.9em;"><b>{{ __('Tutor') }}
                :</b> {{ $alumno->nombre_tutor }} {{ $alumno->apellido_paterno_tutor }} {{ $alumno->apellido_materno_tutor }}
        </p>
        <p style="font-size: 0.9em;"><b>{{ __('Emergency Phone') }}:</b> {{ $alumno->telefono_emergencia }}</p>
    </div>
</div>

<div style="width: 400px; height: 260px; margin: 0 auto; margin-top: 20px; border: 1px solid #000!important;">
    <div
        style="width: 400px; height: 90px; margin: 0 auto; border-bottom: 1px solid #000!important; text-align: center;">
        <img src="{{ env('AWS_URL') }}{{ $configuracion->system_logo }}"
             style="max-width: 63%; max-height: 63%; margin-top: 3px;">
        <p style="width:80%; margin: 0 auto; text-align: center; font-size: 0.8em;">{{ $address }},
            TEL. {{ $telephone }}.</p>
    </div>
    <div style="display: block; float: left; width: 37%; text-align: center;">
        <img
            src="{{ $usuario && $usuario->user_image ? (env('AWS_URL') . $usuario->user_image) : asset('assets/images/user.png') }}"
            style="width: 113px; height: 113px; margin-top: 8px">
        <p style="font-size: 0.9em;">{{ __('Validity') }}</p>
        <p style="font-size: 0.8em;"><b>{{ $date }}</b></p>
    </div>
    <div style="display:block; float: right; width: 63%;">
        <p style="font-size: 0.9em; margin-top: 20px;"><b>{{ __('Name') }}
                :</b> {{ $alumno->nombre }} {{ $alumno->apellido_paterno }} {{ $alumno->apellido_materno }}</p>
        <p style="font-size: 0.9em;"><b>{{ __('CURP') }}:</b> {{ $alumno->curp }}</p>
        <p style="font-size: 0.9em;"><b>{{ __('Email Address') }}:</b> {{ $alumno->correo }}</p>
        <p style="font-size: 0.9em;"><b>{{ __('Tutor') }}
                :</b> {{ $alumno->nombre_tutor }} {{ $alumno->apellido_paterno_tutor }} {{ $alumno->apellido_materno_tutor }}
        </p>
        <p style="font-size: 0.9em;"><b>{{ __('Emergency Phone') }}:</b> {{ $alumno->telefono_emergencia }}</p>
    </div>
</div>

</body>
</html>
