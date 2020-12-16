<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HubspotController extends Controller
{

  public function contact() 
  {
    \Log::info('HubspotController.search()');
    //$response = $hubspot->contacts()->all();
    //dd($hubspot);
  }
}
