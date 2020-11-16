@component('mail::message')
    # Hola {{$user->name}}

    Gracias por crear una cuenta. Por favor verifique la misma usuando el siguente boton:

    @component('mail::button', ['url' => $url])
        Verificar cuenta
    @endcomponent

    Gracias,
    {{ config('app.name') }}
@endcomponent

