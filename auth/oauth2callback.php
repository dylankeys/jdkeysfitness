<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../vendor/autoload.php');

session_start();

$client = new Google\Client();
$client->setAuthConfig(__DIR__ . '/client_secret.json');
$client->setRedirectUri('https://' . $_SERVER['HTTP_HOST'] . '/auth/oauth2callback.php');
$client->setScopes(array('https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/userinfo.profile'));

if (!isset($_GET['code'])) {
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} 
else {
    $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();

    $oauth = new Google\Service\Oauth2($client);
    $user_data = $oauth->userinfo->get();
    $_SESSION['oauth2_email'] = $user_data->email;
    
    $redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . '/admin';

    if (!in_array($_SESSION['oauth2_email'], $CFG->admins)) {

        header('Location: ' . $CFG->wwwroot . '/error.html');
    }
    else {
        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    }
}