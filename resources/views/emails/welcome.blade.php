@component('mail::message')
    # Hola {{$user->name}}

    Gracias por crear una cuenta. Por favor verifique la misma usuando el siguente boton:


    @component('mail::button', ['url' => str_replace("{token}?0=","",route("verify",$user->verification_token))])
        Verificar cuenta
    @endcomponent

    Gracias,<br>
    {{ config('app.name') }}
@endcomponent
