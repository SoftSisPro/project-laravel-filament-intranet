{{-- Pending --}}
@if(isset($data['action']) && $data['action'] == 'pending')
    <h4>Solicitud de Vacaciones</h4>
    <hr>
    <p>
        El empleado <strong>{{ $data['name']??"nombre" }}</strong> ha solicitado el siguiente dia de vacaciones: <strong>{{ $data['day']??"0000-00-00" }}</strong>
    </p>
@endif


{{-- Aproved --}}
@if(isset($data['action']) && $data['action'] == 'approved')
    <h4>Vacaciones Aprovadas</h4>
    <hr>
    <p>
        Hola {{ $data['name']??"nombre" }}
        su solicitud de vacaciones para el día <strong>{{ $data['day']??"0000-00-00" }}</strong>
        han sido <strong style="color: green">APROVADAS</strong>
    </p>
@endif

{{-- Declined --}}
@if(isset($data['action']) && $data['action'] == 'decline')
    <h4>Vacaciones Rechazadas</h4>
    <hr>
    <p>
        Hola {{ $data['name']??"nombre" }}
        su solicitud de vacaciones para el día <strong>{{ $data['day']??"0000-00-00" }}</strong>
        han sido <strong style="color: red">RECHAZADAS</strong>
    </p>
@endif
{{-- Credit plataform --}}
<hr>
&copy; 2021 - {{ date('Y') }} :: {{ config('app.name') }}
