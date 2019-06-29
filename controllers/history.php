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
    'id' => 'history',
    'name' => 'Historique'
  );

  $history_manager=new historyManager();
  $team_manager=new teamManager();


  //handle GETS on the page
  if (isset($type) && isset($id)){
    //$type=$_GET['type'];
    if ($type=='team'){
      $team_id=$id;
      //check if user is allowed to access the team
      if ($team_manager->accessTeamAuth($team_id,$_SESSION['id'])){
        $_SESSION['team']=$team_id;
      }
    }
  }


  if (isset($_SESSION['team'])){
    $team=$_SESSION['team'];
    $events_list=$history_manager->getHistoryList($team);
  }

  $strname=ucfirst(strtolower($_SESSION['firstname']));
  $avatar=$_SESSION['avatar'];
  $teams=$team_manager->getTeams($_SESSION['id']);

  //define items to include in the page view
  $main_view='historyView.php';

  //call template and display above items in the page
  require('views/template.php');
