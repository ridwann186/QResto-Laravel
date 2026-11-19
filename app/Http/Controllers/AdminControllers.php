<?php

namespace App\Http\Controllers;

class AdminControllers extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function menu()
    {
        return view('admin.menu');
    }

    public function pesanan()
    {
        return view('admin.pesanan');
    }

    public function meja()
    {
        return view('admin.meja');
    }
    public function laporan()
    {
    return view('admin.laporan');
}
}