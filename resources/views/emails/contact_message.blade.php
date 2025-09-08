@component('mail::message')
# Nuevo mensaje de contacto

**Nombre:** {{ $name }}
**Email:** {{ $email }}

**Mensaje:**
@component('mail::panel')
{!! nl2br(e($message)) !!}
@endcomponent

---

_IP:_ {{ $ip }}
_User-Agent:_ {{ $userAgent }}
_Recibido:_ {{ $createdAt }}

@component('mail::button', ['url' => config('app.url')])
Ir al sitio
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
