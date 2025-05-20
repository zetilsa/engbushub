<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function getCities($provinceId)
    {
        // Cari provinsi berdasarkan ID
        $province = Province::find($provinceId);

        if (!$province) {
            return response()->json(['message' => 'Provinsi tidak ditemukan'], 404);
        }

        // Ambil semua kota yang berhubungan dengan provinsi
        $cities = $province->cities;

        // Kembalikan sebagai response JSON
        return response()->json($cities);
    }
}