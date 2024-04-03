<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ZohoAccount;

class ZohoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $zoho = ZohoAccount::first();
        return view('zoho.index', compact('zoho'));
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'redirect_uri' => 'required|url',
        ]);

        $zoho = ZohoAccount::first();
        if($zoho)
        {
            $zoho->update($request->all());
        }
        else{
            $zoho = ZohoAccount::create($request->all());
        }

        $url = 'https://accounts.zoho.com/oauth/v2/auth?scope=ZohoCRM.modules.ALL ZohoProjects.portals.ALL ZohoProjects.projects.ALL ZohoProjects.tasks.ALL&client_id='.$zoho->client_id.'&response_type=code&access_type='.$zoho->access_type.'&redirect_uri='.$zoho->redirect_uri;

        return redirect()->to($url);
        // 
    }

    public function code(Request $request) 
    {
        $code = $request->query('code');
        $zoho = ZohoAccount::first();
        
        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://accounts.zoho.com/oauth/v2/token', [
            'form_params' => [
                'grant_type' => $zoho->grant_type,
                'client_id' => $zoho->client_id,
                'client_secret' => $zoho->client_secret,
                'redirect_uri' => $zoho->redirect_uri,
                'code' => $code,
            ],
        ]);

        $body = $response->getBody();
        $auth = json_decode($body);

        $zoho->forceFill([
            'code' => $code,
            'access_token' => $auth->access_token,
            'refresh_token' => $auth->refresh_token,
            'expiry-time' => $auth->expires_in
        ])->save();

        return redirect()->route('zoho.index')->with('success', 'Zoho config updated successfully.');
    }

    

    public function connect(ZohoAccount $zoho)
    {
        $client = new \GuzzleHttp\Client();
    }
}
