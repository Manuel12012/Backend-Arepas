<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{


    public function forgot(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Respuesta genérica (seguridad)
        if (!$user) {
            return response()->json([
                'message' => 'Si el correo existe, recibirás un enlace.'
            ]);
        }

        // 1. generar token manualmente
        $token = Password::broker()->createToken($user);

        // 2. construir URL hacia Vue
        $frontendUrl = config('app.frontend_url', 'http://localhost:5173');

        $resetUrl = $frontendUrl . '/reset-password?token=' . $token . '&email=' . $user->email;

        // 3. enviar correo con Resend (simple HTML)
        Mail::raw("Haz clic para restablecer tu contraseña: $resetUrl", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Recuperación de contraseña');
        });

        return response()->json([
            'message' => 'Si el correo existe, recibirás un enlace.'
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password)
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password actualizado correctamente'
            ]);
        }

        return response()->json([
            'message' => 'Token inválido o expirado'
        ], 400);
    }
}
