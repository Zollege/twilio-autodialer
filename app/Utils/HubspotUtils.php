<?php 

namespace App\Utils;

use \Rossjcooper\LaravelHubSpot\HubSpot;

class HubspotUtils
{
    protected $hubspot;

    public function __construct()
    {
      \Log::info('hubspot constructor called');
      $this->hubspot = new \Rossjcooper\LaravelHubSpot\HubSpot();
    }

    
    public function verifyOrCreateContacts($phonenumbers)
    { 
      \Log::info('verifyOrCreateCotacts()');

      // Given an array of phone numbers, check that they are all hubspot contacts.
      // For any phone numbers which are not already Hubspot contacts, create them in 
      // Hubspot. Return an array of vids for each contact that matches a phone
      // number in the array. 
      $vids = [];

      foreach ($phonenumbers as $number) {
        $contacts = $this->hubspot->contacts()->search($number);
        if (!count($contacts->data->contacts)){
          return "Could not find a valid contact for: $number";
        } else {
          foreach($contacts->data->contacts as $contact) {
            array_push($vids, $contact->vid);
          }
        }
      }
      dd($vids);
      return $vids;
    }

    private function createContactVid(String $phonenumber) 
    {
      // Create a hubspot contact with a phonenumber 
      \Log::info('createContactVid()');
    }

    public function createNote(Array $phonenumbers, String $callerId, String $type)
    {
        \Log::info('createNote()');

        $vids = $this->verifyOrCreateContacts($phonenumbers);
        $body = $this->buildNoteBody($callerId, $type, $message);

        $engagement = ['type' => 'NOTE','active' => true]; 
        $associations = ['contactIds' => [$vids]];
        $metadata = ['body' => $body];

        $engagements = $this->hubspot->engagements()->create($engagement, $associations, $metadata);
        return json_encode($engagements);
    }

    private function buildNoteBody($callerId, $type, $message) 
    {
      //This function returns a string which will be passed to the metadata key
     
      //$body = "Twilio sent an automated $type. \n"."Date: " . date('l jS \of F Y h:i:s A')."\n"."From: $callerId \n";
    } 

}

