<?php

/* get request JSON by item id */

$id = $_GET["id"];
if( empty($id) == true ) exit;

// check if request exists
$filename="request/".$id.".json";

if( file_exists($filename) == true ){

  // request found -> get JSON
  $fp=fopen( $filename,"r" );
  $J =fread( $fp, 20000 );
  fclose($fp);

  // return request JSON
  echo json_encode( json_decode( $J ) );

} else {
  // no request found -> return empty JSON
  echo "{}";
}

?>
