### Folder info

in this folder all request JSON files will stored

#### get all requests as JSON array

    ../get_open_requests.php


#### get request by id as JSON

    Method: GET
    Param: id ={item id}

    ../get_open_requests.php?id={item id}


#### request changes as JSON

    Methot: POST
    Param: CFG ( in JSON-format )
      { "id":"{item_id}",
        "properties":{
          "{field1}":"{new value}",
          "{field2}":"{new value}"
        }
      }

    ../request_changes.php


#### request approve as JSON

    Methot: POST
    Params: id  ={item_id}
            code={auth code}

    ../request_approve.php


#### request cancel as JSON

    Methot: POST
    Params: id  ={item_id}
            code={auth code}

    ../request_cancel.php
