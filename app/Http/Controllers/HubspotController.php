<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Rossjcooper\LaravelHubSpot\HubSpot;

class HubspotController extends Controller
{

  protected $hubspot;

  public function __construct(\Rossjcooper\LaravelHubSpot\HubSpot $hubspot)
  {
    \Log::info('hubspot constructor called');
    $this->hubspot = $hubspot;
  }

  public function createCallLogNote(Request $request, $phonenumber) 
  {
    $contacts = $this->hubspot->contacts()->search($phonenumber);
   
    if (!count($contacts->data->contacts)){
      \Log::error("Contact phone number: $phonenumber was not found in HubSpot");
      return "not a valid contact";
    }

    $contact = $contacts->data->contacts[0]->properties;
    $email = $contact->email->value;
    $splitEmail = explode('@', $email);
    $domain = $splitEmail[1];

    if ($domain != 'call.com' && $domain != 'twl.com') {

      $vid = $contacts->data->contacts[0]->vid; 
      $engagement = [
        'type' => 'NOTE',
        'ownerId' => 1, 
        'active' => true
      ]; 
      $metadata = [  
        'body' => 'test'
      ];
      $associations = [
        'contactIds' => [$vid]
      ];
      $engagements = $this->hubspot->engagements()->create($engagement, $associations, $metadata);
      dd($engagements);
      return json_encode($engagements);
    } else {
      \Log::error("Contact did not contain a valid email for phone number: $phonenumber");
      return 'No valid email associated with phone number';
    }
  }
}
