<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PengaturanTampilan;
use Illuminate\Http\Request;

class PengaturanTampilanController extends Controller
{
    public function index()
    {
        $banners = PengaturanTampilan::select('gambar_banner')->whereNotNull('gambar_banner')->get();
        return response()->json($banners);
    }
}