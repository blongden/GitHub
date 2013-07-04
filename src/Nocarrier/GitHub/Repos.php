<?php
namespace Nocarrier\GitHub;

class Repos
{
    protected $http = null;

    public function __construct(\Guzzle\Service\ClientInterface $http, $org)
    {
        $this->http = $http;
        $this->org = $org;
    }

    public function get(User $user)
    {
        $data = array();

        $url = "/orgs/{$this->org}/repos";
        do {
            $request = $this->http->get($url);
            try {
                $response = $user->auth($request)->send();
            } catch (\Guzzle\Http\Exception\ClientErrorResponseException $e) {
                return false;
            }

            $data = array_merge_recursive($data, json_decode($response->getBody()));
            $link = $response->getHeader('Link')->getLink('next');
            if (isset($link['url'])) {
                $url = $link['url'];
            } else {
                $url = null;
            }
        } while(!is_null($url));
        return $data;
    }
}