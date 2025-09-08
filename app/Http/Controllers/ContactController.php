<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Validación (ajusta mensajes si quieres)
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:120'],
            'email'   => ['required', 'email', 'max:160'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'min:5'],
            // Honeypot opcional:
            'website' => ['nullable', 'size:0'], // campo oculto en el form
        ], [
            'website.size' => 'SPAM detectado.', // por si acaso
        ]);

        // Enriquecer con metadata
        $validated['ip'] = $request->ip();
        $validated['user_agent'] = substr((string) $request->userAgent(), 0, 1000);
        $validated['subject'] = 'Nuevo mensaje de ' . ($validated['name'] ?? ' Contacto Web');

        // Guardar en BD
        $contact = ContactMessage::create($validated);

        // Enviar email
        Mail::to('hazael_gomez@yahoo.com')->send(new ContactMessageMail($contact));

        // Redirección / respuesta
        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'message' => 'Mensaje enviado'], 201);
        }

        return back()->with('status', '¡Gracias! Tu mensaje fue enviado correctamente.');
    }
}
