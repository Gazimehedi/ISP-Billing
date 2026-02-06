<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\District;
use App\Models\Thana;

class GeoController extends Controller
{
    public function divisions()
    {
        return response()->json(Division::all());
    }

    public function districts($division_id)
    {
        return response()->json(District::where('division_id', $division_id)->get());
    }

    public function thanas($district_id)
    {
        return response()->json(Thana::where('district_id', $district_id)->get());
    }
}
