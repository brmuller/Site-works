<?php

require_once("Manager.php");

class apiManager extends Manager
{

  //attach file to task
  public function checkBasicAuth(){

    $user_id='';

    if (isset($_SERVER["HTTP_AUTHORIZATION"]) && 0 === stripos($_SERVER["HTTP_AUTHORIZATION"], 'basic ')) {
      $exploded = explode(':', base64_decode(substr($_SERVER["HTTP_AUTHORIZATION"], 6)), 2);
      if (count($exploded)===2) {
          list($un, $pw) = $exploded;

          //check if user is recorded in DB
          $user_manager=new userManager();
          if ($user_manager->checkUser($un,$pw)){
            $user_id=$user_manager->getUserId($un,$pw);
          }
      }
    }

    return $user_id;
  }


}
