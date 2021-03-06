<?php
session_start();
include_once(__DIR__.'/vendor/autoload.php');

$user_id = $_POST['id'];
$img = __DIR__.'/generated/'.$user_id.'.jpg';

$token;

if( !empty($_SESSION['access_token']) ){
  $token = $_SESSION['access_token'];
}

$fb = new \Facebook\Facebook([
  'app_id' => '1910308689210699',
  'app_secret' => '690c7c65992670ca621973c55efb3ee4',
  'default_graph_version' => 'v2.9',
  //'default_access_token' => '{access-token}', // optional
]);

if( !empty($token) ){

  $data = [
  'source' => $fb->fileToUpload($img),
  ];

  try {
    // Returns a `Facebook\FacebookResponse` object
    $response = $fb->post('/me/photos', $data, $token);
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    // echo 'Graph returned an error: ' . $e->getMessage();
    echo "error occurred!";
    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    //echo 'Facebook SDK returned an error: ' . $e->getMessage();
    echo "error occurred!";
    exit;
  }

  $graphNode = $response->getGraphNode();

  if( !empty( $graphNode ) && !empty( $graphNode['id'] ) ){
    header('Location: https://www.facebook.com/photo.php?fbid='.$graphNode['id'] );
  }else{
    header('Location: http://www.virtualrival.mainsusl.com' );
  }

}
unlink( __DIR__.'/generated/'.$user_id.'.jpg' );
?>
