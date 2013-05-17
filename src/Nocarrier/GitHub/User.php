<?php
namespace Nocarrier\GitHub;

class User
{
    protected $token = null;

    protected $http = null;

    public function __construct(\Guzzle\Service\ClientInterface $http)
    {
        $this->http = $http;
    }

    // todo: handle login failure and credentials expiry
    public function login($user = null, $password = null)
    {
        if (!file_exists('.github') && !is_null($user) && !is_null($password)) {
            $request = $this->http->post(
                '/authorizations',
                null,
                json_encode(
                    array(
                        'scopes' => array('repo'),
                        'note' => 'Incubator Helper'
                    )
                )
            );

            $response = $request->setAuth($user, $password)->send();
            $auth = json_decode($response->getBody());
            file_put_contents('.github', $response->getBody());
        } else {
            var_dump($auth);
            $auth = json_decode(file_get_contents('.github'));
        }

        $this->token = $auth->token;
    }

    //todo: ensure user is logged in and token is set
    public function auth($request)
    {
        $request->setHeader('Authorization', "token {$this->token}");
        return $request;
    }
}
