<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Menangani permintaan masuk.
     *
     * Metode ini digunakan untuk menampilkan halaman profil pengguna.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function __invoke(Request $request)
    {
        
        $pageTitle = 'Profile';

       
        return view('profile', ['pageTitle' => $pageTitle]);
    }
}
