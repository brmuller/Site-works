<?php

  function connectDB(){
    try{
      $db = new PDO(DB_SOURCE, DB_USER, DB_PWD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }catch (PDOException $e) {
      die($e->getMessage());
    }

    return $db;
  }




  //connect to dashboard
  function connect($email,$password){
    $bdd = connectDB();
    $hash_password=sha1($password);


    $req=$bdd->prepare('SELECT * FROM user WHERE email= ? AND password= ?');
    $req->execute(array($email,$hash_password));

    if ($req->rowCount()){

      $data=$req->fetch();
      $_SESSION['id']=$data['id'];
      $_SESSION['email']=$data['email'];
      $_SESSION['password']=$data['password'];
      $_SESSION['firstname']=$data['firstname'];
      $_SESSION['lastname']=$data['lastname'];
      $_SESSION['avatar']=$data['avatar'];

      header('Location: /workflow/dashboard.php');
      exit;
    }
    $bdd=null;
  }




  function register($firstname,$lastname,$email,$password,$avatar){
    $bdd = connectDB();

		$fullname=ucfirst($firstname).' '.ucfirst($lastname);
		$email=htmlspecialchars($_POST['email']);
		$hash_password=sha1($password);

		$insertuser=$bdd->prepare('INSERT INTO user(password,firstname,lastname,fullname,email,avatar) VALUES(?,?,?,?,?,?)');
		$insertuser->execute(array($hash_password,$firstname,$lastname,$fullname,$email,$avatar));

		$_SESSION['id']=$bdd->lastInsertId();  //PDO::lastInsertId()
		$_SESSION['email']=$email;
		$_SESSION['password']=$password;
    $_SESSION['firstname']=$firstname;
    $_SESSION['lastname']=$lastname;
		$_SESSION['avatar']=$avatar;

    $bdd=null;
		header('Location: /workflow/dashboard.php');
		exit;
	}




  //logout
  function deconnect(){
    session_destroy();
  	header('Location: /workflow/');
  	exit;
  }




  //check if user is recorded in DB
  function checkUser($email,$password){
    $bdd = connectDB();
    $hash_password=sha1($password);

    $isRecorded=false;
    //$req=$bdd->query("SELECT * FROM user");
    $req=$bdd->prepare('SELECT * FROM user WHERE email= ? AND password= ?');
    $req->execute(array($email,$hash_password));

    if ($req->rowCount()){
      $isRecorded=true;
    }

    $bdd=null;
    return $isRecorded;
  }




  //check if email is already recorded
  function checkEmail($email){
    $bdd = connectDB();

    $isRecorded=false;
    $req=$bdd->prepare('SELECT * FROM user WHERE email= ?');
    $req->execute(array($email));

    if ($req->rowCount()){
      $isRecorded=true;
    }

    $bdd=null;
    return $isRecorded;
  }




  //get list of files attached to task
  function getTaskUploads($task_id){

    $files=array();
    $folder_path='C:/wamp64/www/workflow/uploads/'.$task_id.'/';
    if (is_dir($folder_path)) {
      if($folder = opendir($folder_path)){
        while(false !== ($file = readdir($folder))){
          if($file != '.' && $file != '..' && $file != 'index.php'){
            $files[]=array(
              "folder_path" => $folder_path,
              "filename" => $file
            );
          }
        }
      }
    }

    return $files;
  }




  //insert comment in DB
  function addComment($task_id,$comment,$creator_id){
    //date_default_timezone_set('Europe/Paris');
    $bdd = connectDB();
		//faire les contrôles de saisie en JS
    $creation_date=date("Y-m-d H:i:s");


    //insert record in table comment
		$insertcomment=$bdd->prepare('INSERT INTO comment(comment,task_id,creator,creation_date) VALUES(?,?,?,?)');
		$insertcomment->execute(array($comment,$task_id,$creator_id,$creation_date));

    $bdd=null;
    return true;

	}




  function checkFlowExists($team_id,$flow_name){
    $bdd = connectDB();

    $flow_exists=false;
    //check if flow name already exists for the specified team
    $req=$bdd->prepare('SELECT * FROM team,flow WHERE team.id=flow.team_id AND team.id=? AND flow.name= ?');
    $req->execute(array($team_id,$flow_name));

    if ($req->rowCount()){
      $flow_exists=true;
    }

    $bdd=null;
    return $flow_exists;
  }




  //check if team has a public scope
  function checkPublicTeam($team_name){
    $bdd = connectDB();

    $team_public=false;
    //$req=$bdd->query("SELECT * FROM user");
    $req=$bdd->prepare('SELECT * FROM team WHERE name= ? AND scope=\'public\'');
    $req->execute(array($team_name));

    if ($req->rowCount()){
      $team_public=true;
    }

    $bdd=null;
    return $team_public;
  }




  //check if team name is in DB
  function checkTeamExists($team_name){
    $bdd = connectDB();

    $team_exists=false;
    //$req=$bdd->query("SELECT * FROM user");
    $req=$bdd->prepare('SELECT * FROM team WHERE name= ?');
    $req->execute(array($team_name));

    if ($req->rowCount()){
      $team_exists=true;
    }

    return $team_exists;
    $bdd=null;
  }




  //check if user is memeber of a team
  function checkUserInTeam($team_name,$user_id) {
    $bdd = connectDB();

    $in_team=false;
    //$req=$bdd->query("SELECT * FROM user");
    $req=$bdd->prepare('SELECT * FROM team,user_team WHERE team.id=user_team.id_team AND team.name= ? AND id_user=?');
    $req->execute(array($team_name,$user_id));

    if ($req->rowCount()){
      $in_team=true;
    }

    $bdd=null;
    return $in_team;
  }




  //insert a new flow in DB
  function createFlow(){
    $bdd = connectDB();

    $flow_name=$_POST['flow-name'];
    $team_id=$_POST['flow-team-name'];
    $creator_id=$_SESSION['id'];
    $status_list=explode(";" , $_POST['flow-status-list']);

    //insert record in table flow
    $insertflow=$bdd->prepare('INSERT INTO flow(name,team_id,creator_id) VALUES(?,?,?)');
    $insertflow->execute(array($flow_name,$team_id,$creator_id));
    $flow_id=$bdd->lastInsertId();

    //insert records in table status
    $insertstatus=$bdd->prepare('INSERT INTO status(name,position,id_flow) VALUES(?,?,?)');
    for ($i = 0; $i < count($status_list); $i++){
      $insertstatus->execute(array($status_list[$i],$i,$flow_id));
    }

  }




  //insert a new task in DB
  function createTask(){
    //date_default_timezone_set('Europe/Paris');
    $bdd = connectDB();
		//faire les contrôles de saisie en JS
    $team=htmlspecialchars($_POST['create-task-team']);
    $flow=htmlspecialchars($_POST['create-task-flow']);
    $title=htmlspecialchars($_POST['create-task-title']);
    $priority=htmlspecialchars($_POST['create-task-priority']);
    $description=htmlspecialchars($_POST['create-task-description']);
    if (isset($_POST['create-task-assignee'])){
      $assignee=htmlspecialchars($_POST['create-task-assignee']);
    }else{
      $assignee='';
    }

    //get task status
    $req=$bdd->prepare('SELECT status.name AS status_name FROM status, flow WHERE flow.id=status.id_flow AND status.position=0 AND flow.id= ?');
    $req->execute(array($flow));

    if ($req->rowCount()){
      $row = $req->fetch();
      $status=$row['status_name'];
    }

    $creator=$_SESSION['id'];
    $last_modifier=$_SESSION['id'];
    $creation_date=date("Y-m-d H:i:s");
    $last_modif_date=date("Y-m-d H:i:s");
    $target_date=$_POST['create-task-target'];


    //insert record in table task
		$inserttask=$bdd->prepare('INSERT INTO task(title,description,creator,creation_date,assignee,last_modifier,last_modification_date,target_delivery_date,team,flow,status,priority) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)');
		$inserttask->execute(array($title,$description,$creator,$creation_date,$assignee,$last_modifier,$last_modif_date,$target_date,$team,$flow,$status,$priority));

    $bdd=null;
  }




  //insert a new team in DB
  function createTeam(){
    $bdd = connectDB();
		//faire les contrôles de saisie en JS
    $team_name=htmlspecialchars($_POST['team-name']);
    $team_scope=htmlspecialchars($_POST['team-scope']);
    $creator=$_SESSION['id'];

    //insert record in table team
		$insertteam=$bdd->prepare('INSERT INTO team(name,creator,scope) VALUES(?,?,?)');
		$insertteam->execute(array($team_name,$creator,$team_scope));

    //insert record in table user_team
    $id_team=$bdd->lastInsertId();
    $insertuserteam=$bdd->prepare('INSERT INTO user_team(id_user,id_team) VALUES(?,?)');
		$insertuserteam->execute(array($creator,$id_team));
    $bdd=null;
	}




  //returns the interval between two dates
  function dateDiff($date1, $date2){
    $diff = abs(strtotime($date1) - strtotime($date2)); // abs pour avoir la valeur absolute, ainsi éviter d'avoir une différence négative
    $retour = array();

    $tmp = $diff;
    $retour['second'] = $tmp % 60;

    $tmp = floor( ($tmp - $retour['second']) /60 );
    $retour['minute'] = $tmp % 60;

    $tmp = floor( ($tmp - $retour['minute'])/60 );
    $retour['hour'] = $tmp % 24;

    $tmp = floor( ($tmp - $retour['hour'])  /24 );
    $retour['day'] = $tmp;

    return $retour;
  }




  //removes task from DB
  function deleteTask($task_id){
    $bdd = connectDB();

    //delete task
    $req=$bdd->prepare('DELETE FROM task WHERE id=?');
    $req->execute(array($task_id));

    $bdd=null;

    return true;
	}




  //get list of status attached to flow
  function getStatusList($flow_id){
    $bdd = connectDB();
    $status_list=array();

    //get list of status for the task
    $req=$bdd->prepare('SELECT position, name FROM status WHERE id_flow= ?');
    $req->execute(array($flow_id));
    if ($req->rowCount()){
      while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
        $status_list[$row['position']]=$row['name'];
      }
    }

    $bdd=null;

    return $status_list;

  }




  //get list of comments attached to task
  function getTaskComments($task_id){
    $bdd = connectDB();

    //get team id
    $req=$bdd->prepare('SELECT * FROM comment,user WHERE comment.creator=user.id AND task_id= ? ORDER BY creation_date ASC');
    $req->execute(array($task_id));
    $comments=array();
    if ($req->rowCount()){
      while ($row = $req->fetch(PDO::FETCH_ASSOC)) {

        $comments[]=array(
          "text" => $row['comment'],
          "creation_date" => $row['creation_date'],
          "fullname" => $row['fullname'],
          "avatar" => $row['avatar']
        );
      }
    }
    $bdd=null;

    return $comments;
  }




  //get data attached to task
  function getTaskData($task_id){
    $bdd = connectDB();
    //get team id
    $req=$bdd->prepare('SELECT flow.name AS flow_name, team.name AS team_name, task.* FROM task,team,flow WHERE team.id=task.team AND flow.id=task.flow AND task.id= ?');
    $req->execute(array($task_id));

    $task_data=array();
    if ($req->rowCount()){
      $row = $req->fetch();

      $task_data = array(
        "title" => $row['title'],
        "description" => $row['description'],
        "status" => $row['status'],
        "assignee" => $row['assignee'],
        "team" => array("id" => $row['team'], "name" => $row['team_name']),
        "flow" => array("id" => $row['flow'], "name" => $row['flow_name']),
        "target_date" => $row['target_delivery_date'],
        "last_update" => $row['last_modification_date'],
        "priority" => $row['priority']
      );

    }

    $bdd=null;

    return $task_data;

  }




  //get list of tasks attached to team
  function getTasksList($team_id, $filter="", $page=1){
    $bdd = connectDB();

    $offset_p=($page-1)*8;
    $tasks=array();

    //1) get results Count
    if($filter==""){
      $str_query='SELECT COUNT(*) AS nb_rows FROM task, user WHERE task.assignee=user.id AND team= ? AND is_closed=0';
      $params=array($team_id);
    }else{
      $str_query='SELECT COUNT(*) AS nb_rows FROM task, user WHERE task.assignee=user.id AND
        team= ? AND is_closed=0 AND (title LIKE "%"?"%" OR user.fullname LIKE "%"?"%" OR status LIKE "%"?"%")';
      $params=array($team_id,$filter,$filter,$filter);
    }

    $rows_count=0;

    $req=$bdd->prepare($str_query);
    $req->execute($params);
    if ($req->rowCount()){
      $row = $req->fetch();
      $rows_count=$row['nb_rows'];
    }

    $tasks['rows_count'] = $rows_count;

    //2) get list of tasks for the team
    $tasks_list=array();

    if($filter==""){
      $str_query='SELECT task.id AS id, task.title AS title, user.fullname AS fullname,
        task.status as status, task.priority as priority FROM task, user WHERE task.assignee=user.id AND
        team= ? AND is_closed=0 ORDER BY last_modification_date DESC limit 8 OFFSET '.$offset_p;

    }else{
      $str_query='SELECT task.id AS id, task.title AS title, user.fullname AS fullname,
        task.status as status, task.priority as priority FROM task, user WHERE task.assignee=user.id AND
        team= ? AND is_closed=0 AND (title LIKE "%"?"%" OR user.fullname LIKE "%"?"%" OR status LIKE "%"?"%")
        ORDER BY last_modification_date DESC limit 8 OFFSET '.$offset_p;

    }
    $req=$bdd->prepare($str_query);
    $req->execute($params);

    if ($req->rowCount()){
      while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
        $tasks_list[]=array(
          "id" => $row['id'],
          "title" => $row['title'],
          "fullname" => $row['fullname'],
          "status" => $row['status'],
          "priority" => $row['priority']
        );
      }
    }

    $tasks['list'] = $tasks_list;

    $bdd=null;
    return $tasks;
  }




  //get list of flows in the team
  function getTeamFlows($team_id){

    //insert record in table team
    $bdd = connectDB();
    $req=$bdd->prepare('SELECT id,name FROM flow WHERE team_id= ?');

    $flows=array();
    if ($req->execute(array($team_id))){
      while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
        $flows[]=array(
          "id" => $row['id'],
          "name" => $row['name']
        );
      }
    }
    $bdd=null;

    return $flows;
  }




  //get list of members in team
  function getTeamMembers($team_id){

    //get list of users
    $bdd = connectDB();
    $req=$bdd->prepare('SELECT user.id AS user_id, user.fullname AS user_fullname FROM user,user_team WHERE user.id=user_team.id_user AND user_team.id_team= ?');

    $users=array();
    $req->execute(array($team_id));
    if ($req->rowCount()){
      while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
        $users[]=array(
          "id" => $row['user_id'],
          "fullname" => $row['user_fullname']
        );
      }
    }
    $bdd=null;

    return $users;
  }




  //get list of teams joined by user
  function getTeams(){
    if (!isset($_SESSION['id'])){
      throw new Exception("l'id de l'utilisateur n'est pas défini");
    }
    $id_user=$_SESSION['id'];

    //insert record in table team
    $bdd = connectDB();
    $req=$bdd->prepare('SELECT team.id AS team_id, team.name AS team_name FROM user_team,team WHERE user_team.id_team=team.id AND id_user= ?');

    $teams_list=array();
    if ($req->execute(array($id_user))){
      while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
        $teams_list[]=array(
          "id" => $row['team_id'],
          "name" => $row['team_name']
        );
      }
    }
    $bdd=null;

    return $teams_list;
  }




  //get user info
  function getUserData($user_id){
    $bdd = connectDB();
    //get team id
    $req=$bdd->prepare('SELECT * FROM user WHERE id= ?');
    $req->execute(array($user_id));

    $user_data=array();
    if ($req->rowCount()){
      $row = $req->fetch();

      $user_data = array(
        "pwd" => $row['password'],
        "firstname" => $row['firstname'],
        "lastname" => $row['lastname'],
        "fullname" => $row['fullname'],
        "email" => $row['email'],
        "avatar" => $row['avatar']
      );

    }

    $bdd=null;

    return $user_data;

  }




  //list of flows created by the current user
  function getUserFlows(){
    if (!isset($_SESSION['id'])){
      throw new Exception("l'id de l'utilisateur n'est pas défini");
    }
    $id_user=$_SESSION['id'];

    //insert record in table team
    $bdd = connectDB();
    $req=$bdd->prepare('SELECT * FROM flow WHERE creator_id= ?');

    $flows_list=array();
    if ($req->execute(array($id_user))){
      while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
        $flows_list[]=$row['name'];
      }
    }
    $bdd=null;

    return $flows_list;
  }




  //insert new member in team
  function joinTeam(){

    $bdd = connectDB();

		//faire les contrôles de saisie en JS
    $team_name=htmlspecialchars($_POST['team-name-join']);
    $user_id=$_SESSION['id'];

    //get id of the team
    $req=$bdd->prepare('SELECT id FROM team WHERE name= ?');
    $req->execute(array($team_name));
    $row = $req->fetch();
    $team_id=$row['id'];

    //insert record in table user_team
		$insertteamuser=$bdd->prepare('INSERT INTO user_team(id_user,id_team) VALUES(?,?)');
		$insertteamuser->execute(array($user_id,$team_id));

    $bdd=null;
	}




  //close task
  function closeTask($task_id){
    $bdd=connectDB();

    //update record in table task
		$inserttask=$bdd->prepare('UPDATE task SET is_closed=1 WHERE id=?');
		$inserttask->execute(array($task_id));

    $bdd=null;
    return true;

  }




  //update task data
  function updateTask(){
    date_default_timezone_set('Europe/Paris');
    $bdd = connectDB();
		//faire les contrôles de saisie en JS
    $taskid=htmlspecialchars($_POST['modify-task-id']);
    $status=htmlspecialchars($_POST['modify-task-status']);
    $priority=htmlspecialchars($_POST['modify-task-priority']);
    $title=htmlspecialchars($_POST['modify-task-title']);
    $description=htmlspecialchars($_POST['modify-task-description']);
    if (isset($_POST['modify-task-assignee'])){
      $assignee=htmlspecialchars($_POST['modify-task-assignee']);
    }else{
      $assignee='';
    }
    $last_modifier=$_SESSION['id'];
    $last_modif_date=date("Y-m-d H:i:s");
    $target_date=$_POST['create-task-target'];


    //insert record in table task
		$inserttask=$bdd->prepare('UPDATE task SET title=?, description=?, assignee=?, last_modifier=?, last_modification_date=?, target_delivery_date=?, status=?, priority=? WHERE id=?');
		$inserttask->execute(array($title,$description,$assignee,$last_modifier,$last_modif_date,$target_date,$status,$priority,$taskid));

    $bdd=null;

	}

?>
