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
    'id' => 'members',
    'name' => 'Membres'
  );


  //handle POSTS on page
  if (isset($_POST['create-task-team'])){$task_manager->createTask();}
  if (isset($_POST['team-name'])){$team_manager->createTeam();}
  if (isset($_POST['flow-name'])){$flow_manager->createFlow();}
  if (isset($_POST['team-name-join'])){$team_manager->joinTeam();}
  if (isset($_POST['modify-task-title'])){$task_manager->updateTask();}
  if (isset($_POST['modify-team-name'])){$team_manager->updateTeam();}
  if (isset($_POST['modify-flow-name'])){$flow_manager->updateFlow();}

  $username=$_SESSION['username'];

  //handle GETS on page
  if (isset($type)){
    //check if member exists
    $member_page=$type;
    $user_manager=new userManager();
    $user_id=$user_manager->getUserIdFromUserName($member_page);
    if ($user_id!=''){
      $user_data=$user_manager->getUserData($user_id);
      $team_manager=new teamManager();
      $teams_count=count($team_manager->getTeams($user_id));
      if (isset($url[2])){
        if ($url[2]=='edit'){
          if ($member_page==$username){
            //get list of countries for dropdown
            $str_json_file=file_get_contents("static/json/countries.json"); //get contents of JSON api objects file
            $countries_list=json_decode($str_json_file,true);
            $section='edit';
          }else{
            require('controllers/notfound.php');
            exit;
          }
        }elseif($url[2]=='parameters'){
          $section='parameters';
        }else{
          require('controllers/notfound.php');
          exit;
        }
      }
    }else{
      require('controllers/notfound.php');
      exit;
    }
  }else{
    require('controllers/notfound.php');
    exit;
  }


  $strname=ucfirst($_SESSION['firstname']).' '.ucfirst($_SESSION['lastname']);
  $avatar=$_SESSION['avatar'];


  //define items to include in the page view
  $main_view='MembersView.php'; //main content


  //call template and display above items in the page
  require('views/template.php');
