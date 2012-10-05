<?php
namespace Nocarrier\GitHub;

class Repo
{
    protected $http = null;
    protected $org = null;
    protected $repo = null;

    public function __construct(\Guzzle\Service\ClientInterface $http, $org, $repo)
    {
        $this->http = $http;
        $this->org = $org;
        $this->repo = $repo;
    }

    protected function authenticatedJsonRequest($user, $url)
    {
        $request = $this->http->get($url);
        try {
            $response = $user->auth($request)->send();
        } catch (\Guzzle\Http\Exception\ClientErrorResponseException $e) {
            return false;
        }

        return json_decode($response->getBody());
    }

    public function get(User $user)
    {
        return $this->authenticatedJsonRequest($user, "/repos/{$this->org}/{$this->repo}");
    }

    public function branches(User $user)
    {
        return $this->authenticatedJsonRequest($user, "/repos/{$this->org}/{$this->repo}/branches");
    }
}
