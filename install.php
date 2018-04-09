<?php

// check if default folder SOURCE_ID exists else skip the install script
if( file_exists("SOURCE_ID") == false ){
  echo "Folder SOURCE_ID not found! Please clone the JSONomy project again OR copy an existing source folder and rename it to SOURCE_ID!";
  exit;
}

// check if new SOURCE JSON submitted by post method
if( !empty( $_POST["SOURCE"]) ){

  // decode source JSON to arrayobject
  $SOURCE=json_decode( $_POST["SOURCE"],true );

  // store the JSON to source.json
  $filename = "SOURCE_ID/source.json";

  $fp=fopen( $filename, "w+" );
  fwrite( $fp, json_encode( $SOURCE ));
  fclose($fp);

  // create datacollection JSON array
  $filename = "SOURCE_ID/".$SOURCE["id"].".json";

  $fp=fopen( $filename, "w+" );
  fwrite( $fp, "[]");
  fclose($fp);

  // create datacollection geoJSON array for umap purposes
  $filename = "SOURCE_ID/".$SOURCE["id"].".geo.json";

  $fp=fopen( $filename, "w+" );
  fwrite( $fp, "[]");
  fclose($fp);

  // rename the SOURCE_ID folder to new SourceID
  rename ( "SOURCE_ID", $SOURCE["id"] );

  exit;
}

?>
<html>
<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <form>
      <div class="form-group">
        <label for="id">source ID*</label>
        <input type="text" class="form-control" id="id" placeholder="FCLN">
      </div>
      <div class="form-group">
        <label for="title">source title*</label>
        <input type="text" class="form-control" id="title" placeholder="FairCoop Local Nodes">
      </div>
      <div class="form-group">
        <label for="description">source description</label>
        <input type="text" class="form-control" id="description" placeholder="List of all FairCoop local nodes!">
      </div>
      <div class="form-group">
        <label for="item_title">item title</label>
        <input type="text" class="form-control" id="item_title" placeholder="Local Node">
      </div>
      <div class="form-group">
        <label for="admin">admin contact*</label>
        <input type="text" class="form-control" id="admin" placeholder="@TonyFord">
      </div>
      <div class="form-group">
        <label for="licence">licence* <a href="https://creativecommons.org/share-your-work/public-domain/freeworks/" target="licence">?</a></label>
        <input type="text" class="form-control" id="licence" placeholder="CC0" value ="CC0">
      </div>
      <div class="form-group">
        <label for="umap">umap url</label>
        <input type="text" class="form-control" id="umap" placeholder="http://umap.openstreetmap.fr/de/map/faircoop-directory">
      </div>
      <div class="form-group">
        <label for="umap"></label>
        <button type="button" class="btn btn-primary" onclick="create_source_json()">create source.json</button>
      </div>
    </form>
  </div>
  <h3></h3>

  <script
			  src="https://code.jquery.com/jquery-3.3.1.min.js"
			  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
			  crossorigin="anonymous"></script>

<script>

function json_load( url ){
  var json = null;
  $.ajax({
      'async': false,
      'global': false,
      'cache': false,
      'url': url,
      'dataType': "json",
      'success': function (data) {
          json = data;
      }
  });
  return json;
}

function create_source_json(){

  var SOURCE=json_load("source_template.json");

  // check all form fields are filled
  var flag=true;
  $.each( $("input.form-control"),
    function( i,v ){
      if( v.value == "" && v.id != "umap" && v.id != "description" && v.id != "item_title" ) flag=false;
    }
  );

  if( flag == false ){
    alert( "please fill in all mandatory fields!" );
    return;
  }

  // get inputs from form
  $.each( $("input.form-control"),
    function( i,v ){
      SOURCE[v.id]=v.value;
    }
  );

  // replace default SOURCE_ID with new SourceID
  SOURCE.properties.id.default= SOURCE.properties.id.default.replace(/SOURCE_ID/g,SOURCE.id);
  SOURCE.properties.id.regex_match = SOURCE.properties.id.regex_match.replace(/SOURCE_ID/g,SOURCE.id);

  // post SOURCE JSON to save source.json on server
  $.post( "",
    {
        SOURCE: JSON.stringify(SOURCE)
    },
    function(data, status){
      if( status != "success"){
        alert("Data: " + data + "\nStatus: " + status);
      } else {
        $(".container").toggleClass("d-none",true);
        $("h3").html( "<a href='" + $("#id").val() + "/source.json'>./" + $("#id").val() + "/source.json</a> successfully created!");
      }

    }
  );

}
</script>
</body>

</html>
