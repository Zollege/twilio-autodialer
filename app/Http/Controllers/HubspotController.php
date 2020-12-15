<?php

namespace App\Http\Controllers;

use Rossjcooper\LaravelHubSpot\HubSpot; 
use Illuminate\Http\Request;

class HubspotController extends Controller
{
  public function __contsruct() 
  {
    \Log::info('HubspotController.construct()');
  }

  public function contact(Rossjcooper\LaravelHubSpot\HubSpot $hubspot) 
  {
    //$response = $hubspot->contacts()->all();
    dd($hubspot);
  }
}
