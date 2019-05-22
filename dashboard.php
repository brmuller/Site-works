<?php
  //the session starts
  session_start();
  if(!isset($_SESSION['id'])){
  	header('Location: /workflow/');
  	exit;
  }


  spl_autoload_register(function ($class) {
    include 'models/' . $class . '.php';
  });

  $current_page=array(
    'id' => 'dashboard',
    'name' => 'Tableau de bord'
  );

  $history_manager=new historyManager();
  $user_manager=new userManager();
  $task_manager=new taskManager();
  $comment_manager=new commentManager();
  $team_manager=new teamManager();
  $flow_manager= new flowManager();



  //handle POSTS on page
  if (isset($_POST['create-task-team'])){$task_manager->createTask();}
  if (isset($_POST['team-name'])){$team_manager->createTeam();}
  if (isset($_POST['flow-name'])){$flow_manager->createFlow();}
  if (isset($_POST['team-name-join'])){$team_manager->joinTeam();}
  if (isset($_POST['modify-task-title'])){$task_manager->updateTask();}


  //handle GETS on page
  if (isset($_GET['type'])){
    $type=$_GET['type'];

    switch ($type) {

      case "team":
        $_SESSION['team']=$_GET['id'];
        break;

      case "updatetask":
        if (isset($_GET['id'])){
          $task_id=$_GET['id'];
          $task_data=$task_manager->getTaskData($task_id);
          $comments=$comment_manager->getTaskComments($task_id);
          $flow_id=$task_data['flow']['id'];
          $status_list=$flow_manager->getStatusList($flow_id);
          $team_id=$task_data['team']['id'];
          $members_list=$team_manager->getTeamMembers($team_id);
          $task_uploads=$task_manager->getTaskFiles($task_id);
          $update_task=true;
        }
        break;

      case "newtask":
        $new_task=true;
        break;

      case "newflow":
        $new_flow=true;
        break;

      case "jointeam":
        $join_team=true;
        break;

      case "newteam":
        $new_team=true;
        break;

      case "logout":
        $user_manager->deconnect();
        break;

      case "exporttasks":
        $team_id=$_GET['id'];
        $task_manager->exportTasksCSV($team_id);
    }
  }

  if (isset($_SESSION['team'])){
    $team=$_SESSION['team'];
    $tasks=$task_manager->getTasksList($team);
    $members_count=count($team_manager->getTeamMembers($team));
    $tasks_list=$tasks['list'];
    $nb_rows=count($tasks_list);
    $nb_pages=ceil($tasks['rows_count']/MAX_TASK_ROWS);
    $events_list=$history_manager->getHistoryList($team);
  }


  $strname=ucfirst(strtolower($_SESSION['firstname']));
  $avatar=$_SESSION['avatar'];
  $teams=$team_manager->getTeams();
  $flows=$flow_manager->getUserFlows();


  require('views/template.php');
?>
