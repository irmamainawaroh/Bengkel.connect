<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $email = strtolower(trim($data['email']));
        $password = $data['password'];

        $user = User::whereRaw('lower(email) = ?', [$email])->first();

        $passwordValid = false;
        if ($user) {
            $passwordValid = Hash::check($password, $user->password);
        }

        // Backward compatibility: seed/import lama bisa menyimpan password plaintext.
        // Selain itu, kadang terjadi variasi formatting (whitespace) pada plaintext.
        if (!$passwordValid && $user && is_string($user->password)) {
            $stored = $user->password;

            // Jika format tersimpan bukan hash bcrypt/argon, anggap ini plaintext dari seed/import lama.
            $looksLikeHash =
                str_starts_with($stored, '$2y$') || str_starts_with($stored, '$2a$') || str_starts_with($stored, '$2b$') ||
                str_starts_with($stored, '$argon2');

            if (!$looksLikeHash) {
                $storedTrim = trim($stored);
                $passwordTrim = trim($password);

                // Bandingkan plaintext dengan beberapa normalisasi minimal.
                $plainMatches = hash_equals($stored, $password)
                    || hash_equals($storedTrim, $password)
                    || hash_equals($stored, $passwordTrim)
                    || hash_equals($storedTrim, $passwordTrim);

                // Jika match, upgrade menjadi hash modern.
                if ($plainMatches) {
                    $user->password = Hash::make($passwordTrim);
                    $user->save();
                    $passwordValid = true;
                } else {
                    // fallback: hapus whitespace berlebih (misal tersimpan ada newline ganda)
                    $storedNormalized = preg_replace('/\s+/', '', $stored);
                    $passwordNormalized = preg_replace('/\s+/', '', $password);
                    if ($storedNormalized !== null && $passwordNormalized !== null && hash_equals($storedNormalized, $passwordNormalized)) {
                        $user->password = Hash::make($passwordTrim);
                        $user->save();
                        $passwordValid = true;
                    }
                }
            } else {
                // Jika terlihat seperti hash tapi gagal Hash::check, coba ambil sisi lain (defensive).
                // Ini jarang, tapi membantu jika ada encoding/whitespace kecil di DB.
                $storedTrim = trim($stored);
                if ($storedTrim !== $stored) {
                    $passwordValid = Hash::check($password, $storedTrim);
                }
            }
        }



        if (!$user || !$passwordValid) {
            return back()->with('error', 'Email atau password salah');
        }


        Session::put('id_user', $user->id);
        Session::put('nama', $user->name);
        Session::put('role', $user->role ?? 'customer');

        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        if ($user->role === 'mekanik') {
            return redirect('/mekanik/dashboard');
        }

        return redirect('/customer/dashboard');
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'no_hp' => 'required|string|max:20',
            'plat_nomor' => 'required|string|max:20',
        ]);

        User::create([
            'name' => $data['nama'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'no_hp' => $data['no_hp'],
            'plat_nomor' => $data['plat_nomor'],
            'role' => 'customer',
        ]);

        return redirect('/login')
            ->with('success', 'Registrasi berhasil');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout()
    {
        Session::flush();

        return redirect('/login');
    }
}

