<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use App\Models\ZohoAccount;
use App\Config\Zoho\TransactionConfig;
use App\Config\Zoho\TasksConfig;

class ZohoService
{
    protected $client;
    protected $credentials;

    public function __construct()
    {
        $this->client = new Client();
        $this->loadCredentials();
    }

    protected function loadCredentials()
    {
        $this->credentials = ZohoAccount::firstOrFail();
    }

    
    public function getRecords($module)
    {
        try {
            $response = $this->client->request('GET', "https://www.zohoapis.com/crm/v2/$module", [
                'headers' => [
                    'Authorization' => "Bearer {$this->credentials->access_token}",
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 401) {
                // Token might be expired, try to refresh it
                $this->refreshToken();
                
                // Retry the request once after refreshing the token
                return $this->getRecords($module);
            }

            // If the error is not due to an expired token, rethrow the exception
            throw $e;
        }
    }

    public function getAllProjects($portalId)
    {
        $url = "https://projectsapi.zoho.com/restapi/portal/{$portalId}/projects/";

        try {
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'Authorization' =>  "Bearer {$this->credentials->access_token}",
                ],
            ]);

            $body = $response->getBody();
            $data = json_decode($body, true);

            return $data['projects']; // The projects data

        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 401) {
                $this->refreshToken();
                
                return $this->getRecords($module);
            }

            
            throw $e;
        }
    }

    public function getTasksForProject(TasksConfig $config)
    {
        $url = "https://projectsapi.zoho.com/restapi/portal/{$config->portalId}/projects/{$config->projectId}/tasks/";

        try {
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'Authorization' => "Bearer {$this->credentials->access_token}",
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $data['tasks']; 

        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 401) {
                $this->refreshToken();
                
                return $this->getTasksForProject($config);
            }

            
            throw $e;
        }
    }

    public function getPortalListId() 
    {
        
         try {
            $response = $this->client->request('GET', 'https://projectsapi.zoho.com/restapi/portals/', [
                'headers' => [
                    'Authorization' => "Bearer {$this->credentials->access_token}",
                ],
            ]);

            return json_decode($response->getBody(), true);

        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 401) {
                $this->refreshToken();
                
                return $this->getPortalListId();
            }

            throw $e;
        }
    }

    public function createProject(array $projectData)
    {
        $url = 'https://projectsapi.zoho.com/restapi/portal/{portalID}/projects/';
        // Replace {portalID} with your actual portal ID.

        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->credentials->access_token,
                    'Content-Type' => 'application/json',
                ],
                'json' => $projectData,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            // Handle exception
            return null;
        }
    }

    public function fetchDataTransaction(TransactionConfig $config)
    {
        // Build the Zoho Creator API endpoint with the provided parameters
        $endpoint = "https://creator.zoho.com/api/v2/{$config->ownerName}/{$config->appName}/{$config->reportName}";

        try {
            $response = $this->client->request('GET', $endpoint, [
                'headers' => [
                    'Authorization' => "Bearer {$this->credentials->access_token}",
                ],
            ]);

            // Assuming the records are in the 'data' array
            $records = json_decode($response->getBody()->getContents(), true);
            return $records['data'];
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 401) {
                // Token might be expired, try to refresh it
                $this->refreshToken();
                
                // Retry the request once after refreshing the token
                return $this->fetchData($ownerName, $appName, $reportName);
            }

            // If the error is not due to an expired token, rethrow the exception
            throw $e;
        }
    }

    

    public function refreshToken()
    {
        $response = $this->client->post('https://accounts.zoho.com/oauth/v2/token', [
            'form_params' => [
                'refresh_token' => $this->credentials->refresh_token,
                'client_id' => $this->credentials->client_id,
                'client_secret' => $this->credentials->client_secret,
                'redirect_uri' => $this->credentials->redirect_uri,
                'grant_type' => 'refresh_token',
            ],
        ]);

        $body = json_decode($response->getBody(), true);
        $newAccessToken = $body['access_token'];

        // Update the access token in the database
        $this->credentials->forceFill([
            'access_token' => $newAccessToken,
        ]);

        // Optionally, reload credentials if needed
        $this->loadCredentials();
    }
}
