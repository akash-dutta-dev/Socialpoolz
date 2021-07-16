<?php require ("vendor_google/autoload.php");
//Step 1: Enter you google account credentials

$g_client = new Google_Client();

$g_client->setClientId("67892232898-9bdb4g4tli25lioq8kchukv9p3vbh7da.apps.googleusercontent.com");
$g_client->setClientSecret("b4HklZMKqIoSh6UouMiVmRsB");
$g_client->setRedirectUri("http://localhost/Google/google.php");
$g_client->setScopes(array(
"https://www.googleapis.com/auth/plus.login",
"https://www.googleapis.com/auth/userinfo.email",
"https://www.googleapis.com/auth/userinfo.profile",
"https://www.googleapis.com/auth/plus.me"
));

//$g_client->setScopes("last_name");

//Step 2 : Create the url
$auth_url = $g_client->createAuthUrl();


//Step 3 : Get the authorization  code
$code = isset($_GET['code']) ? $_GET['code'] : NULL;

//Step 4: Get access token
if(isset($code)) {

    try {

        $token = $g_client->fetchAccessTokenWithAuthCode($code);
        $g_client->setAccessToken($token);

    }catch (Exception $e){
        echo $e->getMessage();
    }




    try {
        $pay_load = $g_client->verifyIdToken();


    }catch (Exception $e) {
        echo $e->getMessage();
    }

} else{
    $pay_load = null;
}

if(isset($pay_load)){

echo $pay_load["email"];
 echo $pay_load["name"];
	
echo "<img src='".$pay_load['picture']."'>";
	

}


