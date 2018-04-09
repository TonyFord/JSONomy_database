<?php
/* approve request and save changes into (single-object) JSON in data folder */

// get source admin(s)
require_once("accesscontrol.php");

// include Google Authenticator functionallity to check if admins code is valid
require_once("GoogleAuthenticator.php");

// code => admins code for auth | id => item id of request
if( empty($_POST["code"]) || empty($_POST["id"]) ){
  exit;
}

// check if external image file exists
function checkExternalFile($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $retCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $retCode;
}

// import external image file into folder icons
function import_external_image($url){

  global $id;

  // get current source ( server ) path
  $L = preg_split( "/\//", "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

  $path="";

  for( $i=0; $i<count($L)-1; $i++ ){
    if( $path != "" ) $path.="/";
    $path.=$L[$i];
  }


  if( $url == "" ){
    // if url of image is empty then remove file only and return
    unlink("icons/".$id.".*"); return "";
  } else {
    // if file not exists then return without any action
    if( checkExternalFile($url) == false ) return "";
  }

  // get file extension from url
  $T=preg_split("/\./",$url);
  $file_extension = $T[ count($T)-1 ];

  // copy external image file into folder icons
  $ch = curl_init( $url );
  $fp = fopen("icons/".$id.".".$file_extension , "wb");
  curl_setopt($ch, CURLOPT_FILE, $fp);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
  curl_exec($ch);
  curl_close($ch);
  fclose($fp);

  // return the new path of imported image
  return $path."/icons/".$id.".".$file_extension;
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

    // check item id of request JSON with file id
    if( $J["id"] == $id ){

      // check and open (single-object) JSON in data folder
      $filename = "data/".$id.".json";

      if( file_exists( $filename ) == true ){
        // get existing JSON
        $fp = fopen( $filename, "r" );
        $JS = json_decode( fread($fp,20000),true );
        fclose($fp);
      } else {
        // no JSON exists -> add new
        $JS = Array();
      }

      // update existing JSON with changes and import external icon image
      foreach( $J["properties"] as $property => $content ){

        if( $property == "icon" ){
          $content = import_external_image($content);
        }
        $JS[$property]=$content;

      }

      // replace old version with new one
      $fp = fopen( "data/".$id.".json", "w+" );
      fwrite($fp, json_encode($JS));
      fclose($fp);

      // create filename for changes log
      $dt=date("Y-m-d__H_i__",time());

      // append info data into changes JSON
      $J["action"]="approved";
      $J["by"]=$auth["id"];
      $J["timestamp"]=time();
      $J["servertime"]=date("Y-m-d H:i",time());

      // store changes JSON into folder changes
      $fp=fopen("changes/".$dt.$id."_approved.json","w+");
      fwrite($fp, json_encode($J));
      fclose($fp);

      // remove request file
      unlink( "request/".$id.".json" );

      // call creation of datacollections ( DATABASE_ID.json and DATABASE_ID.geo.json )
      include("datacollection_create.php");

    } else {

      // item id and reuest file id does not fit -> return 00
      echo "00";
    }

    // request successfully approved -> return 1
    echo "1";
    exit;
  }

}

// if admins code is invalid return 0
echo "0";

?>
