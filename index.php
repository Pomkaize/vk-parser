<?php
ini_set('max_execution_time', 3000);
use VK\Actions\Groups;
use VK\Client\VKApiRequest;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

require_once './vendor/autoload.php';
require_once './src/Core.php';
$client_id = 6777922;
$client_secret = 'XoT250aCKVHxMlBiz4yt';
$redirect_uri = 'http://localhost:8000';
$token = '6ec72fc004db29261705b49c88cb3cdc1628d21ca1fa0d118acae30bf9783de2972b63ca29825e373b722';



if(isset($_GET['login'])) {
    $oauth = new VKOAuth();
    $display = VKOAuthDisplay::PAGE;
    $scope = array(VKOAuthUserScope::WALL, VKOAuthUserScope::GROUPS);
    $state = 'secret_state_code';
    $browser_url = $oauth->getAuthorizeUrl(VKOAuthResponseType::CODE, $client_id, $redirect_uri, $display, $scope, $state);

    echo $browser_url;
} else if(isset($_GET['code'])) {
    $oauth = new VKOAuth();
    $code = $_GET['code'];
    $response = $oauth->getAccessToken($client_id, $client_secret, $redirect_uri, $code);
    $access_token = $response['access_token'];
    echo $access_token;
}

if(isset($_GET['parse'])) {
    $core = new Core();
   /* $core->parse_users();*/
    /*$core->parse_user_groups();*/
    $core->import();
}
