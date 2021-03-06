<?php
  //the session starts
  //session_start();
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
  if (isset($_POST['modify-team-name'])){$team_manager->updateTeam();}
  if (isset($_POST['modify-flow-name'])){$flow_manager->updateFlow();}


  //handle GETS on page
  if (isset($type)){
    //$type=$_GET['type'];

    switch ($type) {

      case "team":
        if (isset($id)){
          $team_id=$id;
          //check if user is allowed to access the team
          if ($team_manager->accessTeamAuth($team_id)){
            $_SESSION['team']=$id;
          }
        }
        break;

      case "updatetask":
        if (isset($id)){
          $task_id=$id;
          //check if user is allowed to access the task
          if ($task_manager->accessTaskAuth($task_id)){
            $task_data=$task_manager->getTaskData($task_id);
            $comments=$comment_manager->getTaskComments($task_id);
            $flow_id=$task_data['flow']['id'];
            $status_list=$flow_manager->getStatusList($flow_id);
            $team_id=$task_data['team']['id'];
            $members_list=$team_manager->getTeamMembers($team_id);
            $task_uploads=$task_manager->getTaskFiles($task_id);
            $update_task=true;
          }
        }
        break;

      case "updateteam":
        if (isset($id)){
          $team_id=$id;
          //check if user is allowed to access the team
          if ($team_manager->accessTeamAuth($team_id)){
            $team_data=$team_manager->getTeamData($team_id);
            $team_members=$team_manager->getTeamMembers($team_id);
            $update_team=true;
          }
        }
        break;

      case "updateflow":
        if (isset($id)){
          $flow_id=$id;
          //check if user is allowed to access the flow (only access flows that he created)
          if ($flow_manager->accessFlowAuth($flow_id)){
            $flow_data=$flow_manager->getFlowData($flow_id);
            $update_flow=true;
          }
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
        if (isset($id)){
          $team_id=$id;
          //check if user is allowed to access the team
          if ($team_manager->accessTeamAuth($team_id)){
            $task_manager->exportTasksCSV($team_id);
          }
        }
    }
  }

  if (isset($_SESSION['team'])){
    $team=$_SESSION['team'];
    $tasks=$task_manager->getTasksList($team);
    $members_count=count($team_manager->getTeamMembers($team));
    $team_flows=$team_manager->getTeamFlows($team);
    $tasks_list=$tasks['list'];
    $nb_rows=count($tasks_list);
    $nb_pages=ceil($tasks['rows_count']/MAX_TASK_ROWS);
    $events_list=$history_manager->getHistoryList($team, MAX_HISTORY_ROWS);
  }


  $strname=ucfirst(strtolower($_SESSION['firstname']));
  $avatar=$_SESSION['avatar'];
  $teams=$team_manager->getTeams();
  $flows=$flow_manager->getUserFlows();
  $my_tasks=$task_manager->getUserTasksCount();

  //define items to include in the page view
  $main_view='dashboardView.php'; //main content
  if (isset($new_team) && $new_team){$modals[]='modalCreateTeam.php';} //modals
  if (isset($join_team) && $join_team){$modals[]='modalJoinTeam.php';}
  if (isset($new_flow) && $new_flow){$modals[]='modalCreateFlow.php';}
  if (isset($new_task) && $new_task){$modals[]='modalCreateTask.php';}
  if (isset($update_task) && $update_task){$modals[]='modalUpdateTask.php';}
  if (isset($update_team) && $update_team){$modals[]='modalUpdateTeam.php';}
  if (isset($update_flow) && $update_flow){$modals[]='modalUpdateFlow.php';}


  //call template and display above items in the page
  require('views/template.php');
