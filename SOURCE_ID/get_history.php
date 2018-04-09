<?php
/* get history of changes as JSON array */

// get current source (server) path
$L = preg_split( "/\//", "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

$path="";

for( $i=0; $i<count($L)-1; $i++ ){
  if( $path != "" ) $path.="/";
  $path.=$L[$i];
}

// get all changes ( JSON files array )
$F=array_reverse( glob("changes/*.json") );

foreach( $F as $k=>$f ){
  $F[$k]=$path."/".$f;
}

// return JSON of all changes files
echo json_encode($F);

?>
