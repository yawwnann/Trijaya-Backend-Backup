<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
class WilayahController extends Controller
{
    public function provinces()
    {
        return response()->json(Province::all());
    }
    public function regencies(Request $request)
    {
        $query = Regency::query();
        if ($request->input('province_id')) {
            $query->where('province_id', $request->input('province_id'));
        }
        return response()->json($query->get());
    }
    public function districts(Request $request)
    {
        $query = District::query();
        if ($request->input('regency_id')) {
            $query->where('regency_id', $request->input('regency_id'));
        }
        return response()->json($query->get());
    }
}