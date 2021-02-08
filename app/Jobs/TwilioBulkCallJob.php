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
     * Create a new job instance.
     * @param $chunk
     * @param $say
     * @param $type
     * @param $callerId
     * @param User $user
     * @param BulkFile $bulkFile
     * @param Hubspot Utils $hubspotUtils
     */
    public function __construct($chunk, $say, $type, $callerId, User $user, BulkFile $bulkFile)
    {
        $this->chunk = $chunk;
        $this->say = $say;
        $this->type = $type;
        $this->callerId = $callerId;
        $this->user = $user;
        $this->bulkFile = $bulkFile;
        
        //dd($this);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(\Rossjcooper\LaravelHubSpot\HubSpot $hubspot)
    {
        
        $hubspotUtils = new \App\Utils\HubspotUtils($hubspot);

        $iteration = rand();
        foreach($this->chunk as $row) {
            \Log::debug('Bulk Dialer - Processing row', [$iteration, $row]);
            $number = substr($row[0], -10);
            (new PlaceTwilioCallService(
                [$number,$this->say, $this->type, $this->callerId],
                $this->user->id,
                $this->bulkFile->id
            ))->call();

            //\Log::info("hubspotUtils from inside job: ".var_dump($hubspotUtils->logOutboundToHubspot));
            //$this->hubspotUtils->logOutboundToHubspot($number, $this->callerId, $this->type, $this->say);
            sleep(1);
        }
        $this->bulkFile->status = 'Completed';
        $this->bulkFile->save();
//        event(new BulkProcessUpdated($this->bulkFile));
    }
}
