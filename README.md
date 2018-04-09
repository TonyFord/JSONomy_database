# JSONomy datasource
Store and visualize data with very low requirements.

### Use cases
* create/manage databases for maps ( http://umap.openstreetmap.fr/ )
* create/manage data objects for websites

### Requirements
* server / webspace with PHP support

### Dependencies
* jQuery 1.11.2
* Bootstrap4x
* http://umap.openstreetmap.fr/  ( optional )

## Installation

follow the steps below to create a own databases

### Installation - step 1

clone this git repo

    git clone https://github.com/TonyFord/JSONomy_database.git

OR download the zip and extract

    https://github.com/TonyFord/JSONomy_database/archive/master.zip

### Installation - step 2

rename SOURCE_ID folder to your own source ID


### Installation - step 3

create source configuration automatically and run installation script

    install.php

OR

edit the source.json manually

* rename SOURCE_ID
  * **.id**
  * **.properties.id.default**
  * **.properties.id.regex_match**


* replace your entries in
  * **.title** ( source title )
  * **.description** ( source description )
  * **.item_title** ( item title )
  * **.admin** ( source admin contact )
  * **.umap**( umap url optional )


### Installation - step 4

append custom properties

* don't change the properties
  * **.id** ( item id )
  * **.name**  ( item name )
  * **.description** ( item description )
  * **.contact** ( item contact for communication purposes )
  * **.latitude** ( geo data )
  * **.longitude** ( geo data )
  * **.icon** ( icon image url )
  * **.color** ( item color, marker color in map )
  * **.type** ( type of map entry )


* append your custom properties in **.properties** array. You can use the online tool http://jsoneditoronline.org/ for edit.

      { "myproperty":
        {
        "mandatory"   :bool,
        "numeric"     :bool,
        "minlength"   :numeric,
        "maxlength"   :numeric,
        "default"     :string,
        "regex_match" :regex_expression string,
        "hidden"      :bool,
        "readonly"    :bool,
        "unique"      :bool
        }
      }

      mandatory
        true    entry mandatory, else causes default
        false   entry optional, empty entry allowed

      numeric
        true    entry must be numeric and stored as numeric value
        false   entry stored as string

      minlength   if it is numeric then minlength is the lower limit of numeric value
                  if it is not numeric then it means the minimum length of string

      minlength   similar to minlength but for upper limit

      default    if new item will added then the default entry will set

      regex_match   regex expression string to validate the entries

      hidden    
        true    hide field, for example to deactivate fields for edits
        false   field is editable

      readonly  
        true    field can only entered if it will added as new item. Later it is not editable anymore.
        false   field is evertime contenteditable

      unique
        true    every entry must be unique
        false   duplicates are allowed


The complete **source.json** string

    source.json

    {
      "id":"SOURCE_ID",
      "title": "{source title}",
      "description": "{source description}",
      "item_title": "{item title}",
      "admin": "{admin contact}",
      "umap":"{http://umap.openstreetmap.fr/LN/map/...}",
      "properties":{
        "id": {
          "mandatory":true,
          "numeric":false,
          "minlength":4,
          "maxlength":10,
          "default":"SOURCE_ID.XXXX",
          "regex_match":"/^SOURCE_ID\\.[A-Z]([A-Z]|)([A-Z]|)([A-Z]|)$/g",
          "hidden":false,
          "readonly":true,
          "unique":true
        },
        "name":{
          "mandatory":true,
          "numeric":false,
          "minlength":2,
          "maxlength":30,
          "default":"",
          "regex_match":"",
          "hidden":false,
          "readonly":false,
          "unique":true
        },
        "description":{
          "mandatory":false,
          "numeric":false,
          "minlength":0,
          "maxlength":1000,
          "default":"",
          "regex_match":"",
          "hidden":false,
          "readonly":false,
          "unique":false
        },
        "contact":{
          "mandatory":true,
          "numeric":false,
          "minlength":0,
          "maxlength":255,
          "default":"",
          "regex_match":"",
          "hidden":false,
          "readonly":false,
          "unique":true
        },
        "latitude":{
          "mandatory":true,
          "numeric":true,
          "minlength":-90,
          "maxlength":90,
          "default":"",
          "regex_match":"",
          "hidden":false,
          "readonly":false,
          "unique":false
        },
        "longitude":{
          "mandatory":true,
          "numeric":true,
          "minlength":-180,
          "maxlength":180,
          "default":"",
          "regex_match":"",
          "hidden":false,
          "readonly":false,
          "unique":false
        },
        "icon":{
          "mandatory":false,
          "numeric":false,
          "minlength":0,
          "maxlength":1000,
          "default":"",
          "regex_match":"",
          "hidden":false,
          "readonly":false,
          "unique":false
        },
        "color":{
          "mandatory":false,
          "numeric":false,
          "minlength":0,
          "maxlength":30,
          "default":"",
          "regex_match":"",
          "hidden":false,
          "readonly":false,
          "unique":false
        },
        "type":{
          "mandatory":true,
          "numeric":false,
          "minlength":5,
          "maxlength":10,
          "default":"Point",
          "regex_match":"/(^Point$)|(^LineString$)|(^Polygon$)/g",
          "hidden":false,
          "readonly":true,
          "unique":false
        }
      }
    }
