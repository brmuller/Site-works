<?php
  //the session starts
  session_start();


  spl_autoload_register(function ($class) {
    include '../models/' . $class . '.php';
  });

  $user_manager=new userManager();
  $task_manager=new taskManager();
  $comment_manager=new commentManager();
  $team_manager=new teamManager();
  $flow_manager= new flowManager();



  if (isset($_POST['jsondata'])){

    $jsondata = utf8_encode($_POST['jsondata']); // Don't forget the encoding
    $data = json_decode($jsondata);
    $action=$data->action;
    if (isset($_SESSION['id'])){$user_id=$_SESSION['id'];}

    switch ($action) {
      case "check_user":
          $response=$user_manager->checkUser($data->email,$data->password);
          $response_json=array('isRecorded'=>$response);
          break;
      case "check_email":
          $response=$user_manager->checkEmail($data->email);
          $response_json=array('isRecorded'=>$response);
          break;
      case "checkflowname":
          $response=$flow_manager->checkFlowExists($data->team_id,$data->flow_name);
          $response_json=array('flowExists'=>$response);
          break;
      case "checkteamname":
          $response=$team_manager->checkTeamExists($data->team_name);
          $response_json=array('teamExists'=>$response);
          break;
      case "check_public_team":
          $response=$team_manager->checkPublicTeam($data->team_name);
          $response_json=array('isPublic'=>$response);
          break;
      case "check_user_in_team":
          $response=$team_manager->checkUserInTeam($data->team_name,$user_id);
          $response_json=array('isInTeam'=>$response);
          break;
      case "get_flows_list":
          $response=$flow_manager->getTeamFlows($data->team_id);
          $response_json=array('flows'=>$response);
          break;
      case "get_team_members":
          $response=$team_manager->getTeamMembers($data->team_id);
          $response_json=array('users'=>$response);
          break;
      case "get_tasks_list":
          $response=$task_manager->getTasksList($data->team_id,$data->filter);
          $response_json=array('tasks'=>$response);
          break;
      case "get_table_page":
          $response=$task_manager->getTasksList($data->team_id,$data->filter,$data->page);
          $response_json=array('tasks'=>$response);
          break;
      case "delete_task":
          $task=$task_manager->getTask($data->task_id);
          $response=$task_manager->deleteTask($task);
          $response_json=array('isDeleted'=>$response);
          break;
      case "close_task":
          $task=$task_manager->getTask($data->task_id);
          $response=$task_manager->closeTask($task);
          $response_json=array('isClosed'=>$response);
          break;
      case "add_comment":
          $response=$comment_manager->addComment($data->task_id,$data->comment,$user_id);
          $response_json=array('isAdded'=>$response);
          break;
      case "get_user_data":
          $response=$user_manager->getUserData($user_id);
          $response_json=array('user'=>$response);
          break;
      case "set_current_team":
          $_SESSION['team']=$data->team_id;
          $response_json=array('set'=>true);
          break;
    }

  }

  //attach file to task
  if (isset($_FILES['myfile']) AND isset($_POST['taskid']))
  {
    $task_id=$_POST['taskid'];
    $response=$task_manager->addTaskFile($task_id);
    $response_json=array('file'=>$response);
  }

  if (isset($response_json)){
    echo json_encode($response_json);
  }
