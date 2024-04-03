<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ProjectsModel;
use App\Http\Controllers\ZohoCreatorController;

class ProcessProjectTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    protected $project;
    public $tries = 3;
    
    public function __construct(ProjectsModel $project)
    {
        $this->project = $project;
    }

    /**
     * Execute the job.
     */
    public function handle(ZohoCreatorController $creator): void
    {
        $creator->processProjectTasks($this->project);

    }

    // public function failed(Exception $exception)
    // {
    //     // Handle the failure, e.g., notify the developer or log the failure
    // }

}
