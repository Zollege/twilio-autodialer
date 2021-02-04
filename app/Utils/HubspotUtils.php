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
    
    private function verifyOrCreateContact($phonenumber)
    { 
      // Given a phone number, check that it exists as a valid hubspot contact.
      // Any phone number which is not registered is saved to Hubspot as new contact. 
      $contactVid = null;
      $contactExists = $this->hubspot->contacts()->search($phonenumber);
      if (!count($contactExists->data->contacts)){
        $properties = [
          "properties" => [
            "property" => "phone",
            "value" => $number
          ]
        ];
        $newContact = $this->hubspot->contacts()->create($properties);
        $contactVid = $newContact->vid;
        \Log::info("New Contact Created in Hubspot. phone number: $newContact->phone \t vid: $contactVid");
      } else {
        $contactVid = $contacts->data->contacts[0]->vid;
        \Log::info("Found Contact in Hubspot. Phone Number: $phonenumber \t Vid: $result");
      }
      return $contactVid;
    }

    public function logOutboundToHubspot(String $phonenumber, String $callerId, String $type, String $message)
    {
        $vid = $this->verifyOrCreateContact($phonenumber);
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
          'ContactIds' => [$vid]
        ];
        
        return $this->hubspot->engagements()->create($engagement, $associations, $metadata);
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

