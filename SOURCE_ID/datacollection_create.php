<?php
/* create datacollection from all single-object JSON files */

// get source config
$filename="source.json";
if( ! file_exists($filename) ){
  echo "ERROR: source.json not found!!";
  exit;
}

$fp=fopen( $filename ,"r");
$json=fread($fp, filesize($filename));
fclose($fp);

$CFG=json_decode($json,true);

// get all (single-object) JSON files of data folder
$F = glob("data/*.json");

// initialize datacollection JSON array
$JSN=Array();

// initialize datacollection JSON array for (optional) umap.openstreetmap.fr usage
$JSNgeo=Array();

// push all single-object JSON into the datacollection JSON array
foreach( $F as $f ){

  $fp=fopen($f,"r");
  $J=json_decode( fread( $fp,filesize($f) ),true);
  fclose($fp);

  array_push($JSN, $J);

  // create the geoJSON fields from data object and push it
  $G=[ "type"=>$J["type"],"coordinates"=>[$J["longitude"],$J["latitude"]] ];
  $J["_storage_options"]=[ "iconUrl" => $J["icon"], "color"=>$J["color"] ];
  array_push($JSNgeo, [ "type"=>"Feature", "properties"=>$J ,"geometry" => $G ]);

}

// save the JSON data collection to file ( DATABASE_ID.json ) on server
$fp=fopen($CFG["id"].".json","w+");
fwrite( $fp, json_encode($JSN));
fclose($fp);

// save the geoSON data collection to file ( DATABASE_ID.geo.json ) on server
$fp=fopen($CFG["id"].".geo.json","w+");
fwrite( $fp, json_encode($JSNgeo));
fclose($fp);

?>
