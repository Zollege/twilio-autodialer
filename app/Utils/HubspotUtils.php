<?php 

namespace App\Utils;

use \Rossjcooper\LaravelHubSpot\HubSpot;

class HubspotUtils
{
    protected $hubspot;

    public function __construct(\Rossjcooper\LaravelHubSpot\HubSpot $hubspot)
    {
      $this->hubspot = $hubspot;
      date_default_timezone_set("America/Chicago");
    }
    
    private function verifyOrCreateContacts($phonenumbers)
    { 
      // Given an array of phone numbers, check that they are all valid hubspot contacts.
      // Any phone numbers which are not registered, are saved to Hubspot as new contacts. 
      $vids = [];
      foreach ($phonenumbers as $number) {
        $contacts = $this->hubspot->contacts()->search($number);
        if (!count($contacts->data->contacts)){
          $properties = [
            "properties" => [
              "property" => "phone",
              "value" => $number
            ]
          ];
          $newContact = $this->createContact($properties);
            array_push($vids, $newContact->data->vid);
        } else {
          foreach($contacts->data->contacts as $contact) {
            array_push($vids, $contact->vid);
          }
        }
      }
      return $vids;
    }

    public function createContact(Array $properties) 
    {
      return $this->hubspot->contacts()->create($properties);
    }

    public function createNote(Array $phonenumbers, String $callerId, String $type, String $message)
    {
        $vids = $this->verifyOrCreateContacts($phonenumbers);
        $body = $this->buildNoteBody($callerId, $type, $message);

        $engagement = [
          'type' => 'NOTE',
          'ownerId' => 1,
          'active' => true
        ];
        $metadata = [
          'body' => $body 
        ];
        $associations = [
          'contactIds' => $vids
        ];
        return $engagements = $this->hubspot->engagements()->create($engagement, $associations, $metadata);
    }

    private function buildNoteBody($callerId, $type, $message) 
    {
      //This function returns a string which will be passed to the metadata key
      return "Twilio sent an automated $type message. ".
             "Date: ".date('l jS \of F Y h:i:s A').". ".
             "From: $callerId. ".
             "Message: ".$message;
    } 
}

