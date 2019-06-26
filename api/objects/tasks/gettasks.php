<?php

//check if team is in url
if (!empty($param1)){
  $team=$param1;
  //check if team exists and get the team id
  $team_manager=new teamManager();
  $team_id=$team_manager->getTeamId($team);
  if ($team_id!=''){
    //check if user is allowed to access the tasks of this team
    if ($team_manager->accessTeamAuth($team_id,$user_id)){
      $task_manager=new taskManager();
      //check if tasks filter was sent in request
      if (isset($_GET['filter']) && !empty($_GET['filter'])){
        $filter=$_GET['filter'];
        $tasks=$task_manager->getTasksList($team_id,$filter);
      }else{
        $tasks=$task_manager->getTasksList($team_id);
      }

      //set response code - 200 OK
      http_response_code(200);

      //show tasks data in json format
      echo json_encode($tasks);

    }else{
      //set response code - 403 FORBIDDEN
      http_response_code(403);
      echo json_encode(
        array("message"=>"Not allowed to access the tasks of this team.")
      );
    }

  }else{
    //set response code - 404 NOT FOUND
    http_response_code(404);
    echo json_encode(
      array("message"=>"Team not found.")
    );
  }

}else{
  //set response code - 400 BAD REQUEST
  http_response_code(400);
  echo json_encode(
    array("message"=>"Team parameter is missing in url.")
  );
}
