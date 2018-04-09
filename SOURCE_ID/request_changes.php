<?php
/* save request to request folder */

$CHG = json_decode( $_POST["CHG"], true );
$filename=$CHG["id"].".json";

// check and get request if already exists
if( file_exists ( "request/".$filename ) == true ){
  $fp=fopen("request/".$filename,"r");
  $JS= json_decode( fread($fp,20000),true );
  fclose($fp);

  // merge existing changes and new changes
  $CHG = array_merge_recursive ( $JS, $CHG );
}

// remove duplicates
if( is_array( $CHG["id"] ) == true ) $CHG["id"]=$CHG["id"][0];

// remove old entries of change request if exists
foreach( $CHG["properties"] as $property => $content ){
  if( is_array( $content ) == true ) {
    $CHG["properties"][$property]=$content[ count( $content ) - 1];
  }
}

// save request JSON to folder request
$fp=fopen("request/".$filename,"w+");
fwrite( $fp, json_encode($CHG) );
fclose($fp);

?>
