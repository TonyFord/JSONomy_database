<?php
/* cancel request and notice the request in changes folder */

// get source admin(s)
require_once("accesscontrol.php");

// include Google Authenticator functionallity to check if admins code is valid
require_once("GoogleAuthenticator.php");

// code => admins code for auth | id => item id of request
if( empty($_POST["code"]) || empty($_POST["id"]) ){
  exit;
}

$code = $_POST["code"];
$id = $_POST["id"];

// create instance of Google Authenticator class
$ga = new PHPGangsta_GoogleAuthenticator();

// check the admins code with all entries in accesscontrol.php
foreach ( $AUTH as $auth ){

  if( $ga->verifyCode( $auth["key"], $code, $time_offset ) ){

    // admins code is valid -> open request JSON
    $fp = fopen( "request/".$id.".json", "r" );
    $J = json_decode( fread($fp,20000),true );
    fclose($fp);

    // create filename for changes log
    $dt=date("Y-m-d__H_i__",time());

    // append info data into changes JSON
    $J["action"]="canceled";
    $J["by"]=$auth["id"];
    $J["timestamp"]=time();
    $J["servertime"]=date("Y-m-d H:i",time());

    // store changes JSON into folder changes
    $fp=fopen("changes/".$dt.$id."_canceled.json","w+");
    fwrite($fp, json_encode($J));
    fclose($fp);

    // remove request file
    unlink( "request/".$id.".json" );

    // request successfully canceled -> return 1
    echo "1";
    exit;
  }

}

// if admins code is invalid return 0
echo "0";

?>
