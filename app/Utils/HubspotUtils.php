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
    
      //\Log::info("verifyOrCreateContact called");

      // Given a phone number, check that it exists as a valid hubspot contact.
      // Any phone number which is not registered is saved to Hubspot as new contact. 
      $contactVid = null;
      $contactExists = $this->hubspot->contacts()->search($phonenumber);
      if (!count($contactExists->data->contacts)){
        $properties = [
          "properties" => [
            "property" => "phone",
            "value" => $phonenumber
          ]
        ];
        $newContact = $this->hubspot->contacts()->create($properties);
        $contactVid = $newContact->vid;
        \Log::info("New Contact Created in Hubspot. phone number: $phonenumber \t vid: $contactVid");
      } else {
        $contactVid = $contactExists->data->contacts[0]->vid;
        \Log::info("Found Contact in Hubspot. Phone Number: $phonenumber \t Vid: $contactVid");
      }
      return $contactVid;
    }

    public function logOutboundToHubspot(String $phonenumber, String $message, String $type, String $callerId)
    {
        //\Log::info("logOutboundToHubspot Arguments: \t Phone Number: $phonenumber \t Message: $message \t Type: $type \t CallerId: $callerId");
        
        $vid = $this->verifyOrCreateContact($phonenumber);
        //\Log::info("logOutboundToHubspot Vid: $vid");
        //return;
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
          'contactIds' => [$vid]
        ];
        
        return $this->hubspot->engagements()->create($engagement, $associations, $metadata);
    }

    private function buildNoteBody($callerId, $type, $message) 
    {
      //This function returns a string which will be passed to the metadata key
      return "Autodialer sent an automated $type message. ".
             "Date: ".date('l jS \of F Y h:i:s A').". ".
             "From: $callerId. ".
             "Message: ".$message;
    } 
}

