<?php
define('clientId','dd79969587be46d1b22c61ff3e037719');

require_once __DIR__.'/../vendor/autoload.php';
$app = new Silex\Application();

function connectToInstagram($url)
{
	$ch= curl_init();
	curl_setopt_array($ch, array(
		CURLOPT_URL 			=> $url,
		CURLOPT_RETURNTRANSFER 	=> 1,
		CURLOPT_SSL_VERIFYPEER	=> false,
		CURLOPT_SSL_VERIFYHOST	=> 2
		));
	$results= curl_exec($ch);
	curl_close($ch);
	return $results;
}

function getShortCode($id,$clientId)
{
    $url= 'https://api.instagram.com/v1/media/'.$id.'?client_id='.$clientId;
    $instagramInfo= connectToInstagram($url);
    $results = json_decode($instagramInfo, true);
    return $results;
}


$app->get('/media/{id}', function ($id) use ($app) 
{
	$location= getShortCode($app->escape($id), clientId);

	if ($location['meta']['code'] == 400) 
	{
        return $app->json($location['meta']);
   	} 
    return $app->json($location['data']['location']);
});

$app->run();
