<?php

namespace App\Http\Controllers;

use DB;
use App\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $r){
      $logs = Log::select('*');

      if(!empty($r->from))
        $logs = $logs->where('date', '>', $r->from);
      if(!empty($r->to))
        $logs = $logs->where('date', '<', $r->to);
      if($r->group == 'ip')
        $logs = $logs->select(DB::raw('ip , count(*) as date'))->groupBy($r->group);
      if($r->group == 'date')
        $logs = $logs->select(DB::raw('date as ip , count(*) as date'))->groupBy($r->group);

      $logs = $logs->get();
      return view('Logs', ['logs' => $logs, 'group' => $r->group]);
    }
}
