<?php

namespace App\Jobs;

use App\User;
use App\Models\BulkFile;
use Illuminate\Bus\Queueable;
use App\Events\BulkProcessUpdated;
use Illuminate\Queue\SerializesModels;
use App\Services\PlaceTwilioCallService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use \Rossjcooper\LaravelHubSpot\HubSpot;
use App\Utils\HuspotUtils;

class TwilioBulkCallJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, DispatchesJobs;

    /**
     * @var $call
     */
    protected $call;
    /**
     * @var
     */
    private $chunk;
    /**
     * @var
     */
    private $say;
    /**
     * @var
     */
    private $type;
    /**
     * @var
     */
    private $callerId;
    /**
     * @var User
     */
    private $user;
    /**
     * @var BulkFile
     */
    private $bulkFile;
    /**
     * @var 
     */
    private $bulkTitle;


    public $timeout = 120;

    /**
     * Create a new job instance.
     * @param $chunk
     * @param $say
     * @param $type
     * @param $callerId
     * @param User $user
     * @param BulkFile $bulkFile
     * @param $bulkTitle 
     */
    public function __construct($chunk, $say, $type, $callerId, User $user, BulkFile $bulkFile, $bulkTitle)
    {
        $this->chunk = $chunk;
        $this->say = $say;
        $this->type = $type;
        $this->callerId = $callerId;
        $this->user = $user;
        $this->bulkFile = $bulkFile;
        $this->bulkTitle = $bulkTitle;
        //dd($this);
    }

    /*
     * Execute the job.
     *
     * @return void
     */
    public function handle(\Rossjcooper\LaravelHubSpot\HubSpot $hubspot)
    {
        $execStart = microtime(true);    
        
        $hubspotUtils = new \App\Utils\HubspotUtils($hubspot);
        $iteration = rand();
        foreach($this->chunk as $row) {
            \Log::info('Bulk Dialer - Processing row', [$this->bulkTitle, $row]);
            $number = substr($row, -10);
            \Log::info("number: $number");
            \Log::info("row: $row");
            (new PlaceTwilioCallService(
                [$number,$this->say, $this->type, $this->callerId],
                $this->user->id,
                $this->bulkFile->id, 
                $this->bulkTitle
            ))->call();

            $hubspotUtils->logOutboundToHubspot($number, $this->say, $this->type, $this->callerId);
            sleep(1);
        }
        $this->bulkFile->status = 'Completed';
        $this->bulkFile->save();
        //event(new BulkProcessUpdated($this->bulkFile));
        
        $execEnd = microtime(true);
        $execTime = $execEnd - $execStart;
        \Log::info("Bulk Title: $this->bulkTitle, job iteration: $iteration took: $execTime seconds to execute");

    }
}
