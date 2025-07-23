<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with('kategoriProduk')->get();
        return response()->json($produks);
    }

    /**
     * Menampilkan detail satu produk.
     */
    public function show($id)
    {
        $produk = Produk::with('kategoriProduk')->find($id);

        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        return response()->json($produk);
    }
}