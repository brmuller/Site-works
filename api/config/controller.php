<?php
  //required headers
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");

  spl_autoload_register(function ($class) {
    include 'models/' . $class . '.php';
  });

  //Basic authentication check
  $api_manager=new apiManager();
  $user_id=$api_manager->checkBasicAuth();
  if ($user_id != ''){
    //route to the appropriate $object
    $str_json_file=file_get_contents("api/config/app_objects.json"); //get contents of JSON api objects file
    $app_objects=json_decode($str_json_file,true);
    $objects=$app_objects["objects"];
    if (!empty($object) && array_key_exists($object,$objects)){
      if (!empty($service) && array_key_exists($service,$objects[$object])){
        //check if service is available
        if ($objects[$object][$service]){
          require('api/objects/'.$object.'/'.$service.'.php');
        }else{
          //set response code - 503 SERVICE UNAVAILABLE
          http_response_code(503);
          echo json_encode(
            array("message"=>"Service temporarily unavailable.")
          );
        }
      }else{
        //set response code - 400 BAD REQUEST
        http_response_code(400);
        echo json_encode(
          array("message"=>"Action parameter missing in url.")
        );
      }
    }else{
      //set response code - 400 BAD REQUEST
      http_response_code(400);
      echo json_encode(
        array("message"=>"Object parameter missing in url.")
      );
    }
  }else{
    //set response code - 401 UNAUTHORIZED
    http_response_code(401);
    echo json_encode(
      array("message"=>"Basic Authentication is required to access the resource.")
    );
  }
