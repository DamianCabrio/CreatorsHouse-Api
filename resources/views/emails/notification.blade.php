@component('mail::message')
    # Hola

    Un creador que sigue ha creado un nuevo post, haga click en el siguiente post para ir a verlo


    @component('mail::button', ['url' => "localhost:8080/#/creator/" . $creator->id . "/post/" . $post->id])
        Ver post
    @endcomponent

    Gracias,<br>
    {{ config('app.name') }}
@endcomponent
