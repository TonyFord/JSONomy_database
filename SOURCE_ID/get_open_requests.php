<?php

/* get all requests as JSON array */

// get array of all request JSON in request folder
$F = glob("request/*.json");

// initialize request array
$REQ=Array();

// load every request JSON and push it into request array
foreach( $F as $f ){

  $fp=fopen($f,"r");
  $J=json_decode( fread( $fp,filesize($f) ),true);
  fclose($fp);
  array_push($REQ, $J);

}

// return JSON array of all requests
echo json_encode($REQ);

?>
