@component('mail::message')
    # Hola {{$user->name}}

    Cambio su mail, asi que tenemos que verificar su nuevo email. Por favor use este boton:

    @component('mail::button', ['url' => route("verify",$user->verification_token)])
        Verificar cuenta
    @endcomponent

    Gracias,<br>
    {{ config('app.name') }}
@endcomponent
