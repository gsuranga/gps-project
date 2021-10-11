<?php

namespace App\Http\Controllers\Gmap;

use App\Http\Controllers\Controller;
use App\Models\TourItinerary\TsoItinerary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GmapController extends Controller
{
    public function index(){
        return view('gmap.gmap_view');
    }

    public function gmapView(){
        return view('gmap.gmap_view');
    }
    public function gmapOutletsView(){
        return view('gmap.gmap_outlets');
    }

}
