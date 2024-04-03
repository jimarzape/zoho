<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\PortalModel;
use App\Models\ProjectsModel;
use App\Models\Tasks;
use App\Models\TasksTeam;
use App\Services\ZohoService;
use App\Config\Zoho\TransactionConfig;
use App\Config\Zoho\TasksConfig;
use App\Jobs\ProcessProjectTask;


class ZohoCreatorController extends Controller
{
    protected $zohoservice;

    public function __construct()
    {
        $this->zohoservice = new ZohoService();
    }

    public function index()
    {
        $transactions = Transaction::paginate(10);
        return view('creator.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getPortals()
    {
        try{
            $portal = $this->zohoservice->getPortalListId();
        
            if(isset($portal['portals']))
            {
                foreach($portal['portals'] as $portal)
                {
                    PortalModel::updateOrCreate(
                        [
                            'portal_id'     => $portal['id']
                        ],
                        [
                            'plan'          => $portal['plan'],
                            'owner_id'      => $portal['portal_owner']['id'],
                            'owner_email'   => $portal['portal_owner']['email'],
                            'owner_name'    => $portal['portal_owner']['name'],
                        ]
                        );
                }
            }

            return redirect()->route('creators.projects');
        }
        catch(\Exception $e)
        {
            return redirect()->route('creators.index')->withErrors(['message' => $e->getMessage()]);
        }

        
    }

    public function getProjects()
    {
        try{
            $portals = PortalModel::all();

            foreach($portals as $portal)
            {
                $projects = $this->zohoservice->getAllProjects($portal->portal_id);
                foreach($projects as $project)
                {
                    ProjectsModel::updateOrCreate(
                        [
                            'project_id' => $project['id']
                        ],
                        [
                            'portal_id'         => $portal->id,
                            'name'              => $project['name'],
                            'owner_id'          => $project['owner_id'],
                            'owner_email'       => $project['owner_email'],
                            'owner_name'        => $project['owner_name'],
                            'description'       => $project['description'],
                            'status'            => $project['status'],
                            'end_date'          => Self::formatDate($project['end_date']),
                            'start_date'        => Self::formatDate($project['start_date']),
                        ]
                    );
                }
                
            }
            return redirect()->route('creators.tasks');
        }
        catch(\Exception $e)
        {
           return redirect()->route('creators.index')->withErrors(['message' => $e->getMessage()]);
        }
    }

    public function getTasks()
    {
        try
        {
            $delayInSeconds = 10;
            ProjectsModel::with('portals')->chunk(100, function($projects) use($delayInSeconds){
                foreach($projects as $key => $project)
                {
                    $delay = now()->addSeconds($delayInSeconds * $key);
                    ProcessProjectTask::dispatch($project)->delay($delay);
                }
            });            
            return redirect()->route('creators.index')->with('success', 'Projects are now on queue, please comeback in few minutes to see the updates');
        }
        catch(\Exception $e)
        {
            return redirect()->route('creators.index')->withErrors(['message' => $e->getMessage()]);
        }
    }


    public function processProjectTasks($project)
    {
        $config = $this->getConfig($project);
        $tasks = $this->zohoservice->getTasksForProject($config);

        $internal_hours_approver        = '';
        $customer_hours_approver_arr    = [];
        $total_billable_hours           = 0;
        $total_non_billable_hours       = 0;
        $monthly_hours_budget           = 0;

        foreach($tasks as $task)
        {
            
            $customer_hours_approver = '';
            $monthly_hour_budget = 0;
            $total_billable_hours += $task['log_hours']['non_billable_hours'];
            $total_non_billable_hours += $task['log_hours']['billable_hours'];

            if(isset($task['custom_fields']))
            {
                foreach($task['custom_fields'] as $field)
                {
                    if($field['label_name'] == 'Customer hours approver')
                    {
                        $customer_hours_approver = $field['value'];
                        array_push($customer_hours_approver_arr, $field['value']);
                    }
                    if($field['label_name'] == 'Monthly hours budget')
                    {
                        $monthly_hour_budget = $field['value'];
                        $monthly_hours_budget += $field['value'];
                    }
                    
                }
            }
            
            $data = Tasks::updateOrCreate(
                [
                    'task_id' => $task['id']
                ],
                [
                    'project_id'                => $project->id,
                    'portal_id'                 => $project->portals->id,
                    'name'                      => $task['name'],
                    'end_date'                  => Self::formatDate($task['end_date']),
                    'start_date'                => Self::formatDate($task['start_date']),
                    'description'               => $task['description'],
                    'owner_id'                  => $task['created_by'],
                    'owner_name'                => $task['created_person'],
                    'owner_email'               => $task['created_by_email'],
                    'non_billable_hours'        => $task['log_hours']['non_billable_hours'],
                    'billable_hours'            => $task['log_hours']['billable_hours'],
                    'duration'                  => $task['duration'],
                    'duration_type'             => $task['duration_type'],
                    'customer_hours_approver'   => $customer_hours_approver,
                    'monthly_hour_budget'       => $monthly_hour_budget
                ]
                );

            $groups = $task['GROUP_NAME'];
            if($groups['ASSOCIATED_TEAMS_COUNT'] > 0)
            {
                foreach($groups['ASSOCIATED_TEAMS'] as $key => $team)
                {
                    TasksTeam::where('task_id', $data->id)->delete();
                    TasksTeam::create(
                        [
                            'name' => $team,
                            'task_id' => $data->id,
                            'zpuid' => $key
                        ]
                    );
                }
            }
        }

        $customer_hours_approver_arr = array_unique($customer_hours_approver_arr);


        Transaction::updateOrCreate(
            [
                'project_id'                => $project->id, // Check for the existing record using account_id
            ],
            [
                'internal_hours_approver'   => $internal_hours_approver,
                'customer_hours_approver'   => implode(',', $customer_hours_approver_arr),
                'project_name'              => $project->name,
                'total_billable_hours'      => $total_billable_hours,
                'total_non_billable_hours'  => $total_non_billable_hours,
                'account_name'              => $project->owner_name,
                'account_id'                => $project->owner_id,
                'project_description'       => $project->descriptio,
                'monthly_hours_budget'      => $monthly_hours_budget,
                'reporting_period_start'    => $project->start_date,
                'reporting_period_end'      => $project->end_date,
                'portal_id'                 => $project->portal_id
            ]
        );
    }

    private function getConfig($project)
    {
        return new TasksConfig(
            portalId: $project->portals->portal_id,
            projectId: $project->project_id
        );
    }


    public function formatDate($datestr)
    {
        try{
           return date('Y-m-d', strtotime(str_replace('-', '/', $datestr)));
        }
        catch(\Exception $e)
        {
            return null;
        }
    }

}
