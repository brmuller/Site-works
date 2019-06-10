<?php

require_once("Manager.php");

class taskManager extends Manager
{

  //attach file to task
  public function addTaskFile($task_id){

    $err="";
    $file="";
    // Testons si le fichier n'est pas trop gros
    if ($_FILES['myfile']['error'] == 0){
      if ($_FILES['myfile']['size'] <= MAX_FILE_SIZE)
      {
        // On peut valider le fichier et le stocker définitivement
        if (!is_dir(REPOSITORY_PATH . '/'. $task_id .'/')) {
            mkdir(REPOSITORY_PATH . '/'. $task_id .'/', 0777, true);
        }
        //move file to 'uploads' repository
        $file=REPOSITORY_PATH . '/'. $task_id .'/'. basename($_FILES['myfile']['name']);
        move_uploaded_file($_FILES['myfile']['tmp_name'], $file);

        //retrieve the task team id
        $bdd = $this->connectDB();
        $req=$bdd->prepare('SELECT team from task WHERE id= ?');
        $req->execute(array($task_id));

        if ($req->rowCount()){
          $row = $req->fetch();
          $team=$row['team'];
        }

        //update history
        $history_manager=new historyManager();
        $history_manager->addEvent($team,'attach_file',$task_id);

      }else{
        $err="fichier trop volumineux";
      }
    }else{
      $err="erreur lors de l'envoi du fichier";
    }


    $file_add=array(
      'error'=>$err,
      'file'=>$file
    );

    return $file_add;
  }



  //export tasks to CSV
  public function exportTasksCSV($team_id){

    $filename = $team_id.'-'.date('d.m.Y').'.csv';

    //retrieve $tasks
    $tasks=$this->getTasksList($team_id);
    $tasks_list=$tasks['list'];

    $list=array();

    // Append results to array
    $headers=false;
    foreach ($tasks_list as &$line) {
      if (!$headers){
        array_push($list, array_keys($line));
        $headers=true;
      }
      array_push($list, array_values($line));
    }


    // Output array into CSV file
    $fp = fopen('php://output', 'w');
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    foreach ($list as $ferow) {
        fputcsv($fp, $ferow,";");
        //fputcsv($file,explode(';',$customerInfo), ";");
    }

    //Close the file pointer.
    fclose($fp);
    die();
  }



  //get list of files attached to task
  public function getTaskFiles($task_id){

    $files=array();
    $folder_path=REPOSITORY_PATH . '/'. $task_id .'/';
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




  //insert a new task in DB
  public function createTask(){
    //date_default_timezone_set('Europe/Paris');
    $bdd = $this->connectDB();
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
    $req=$bdd->prepare('SELECT status.id AS status_id FROM status, flow WHERE flow.id=status.id_flow AND status.position=0 AND flow.id= ?');
    $req->execute(array($flow));

    if ($req->rowCount()){
      $row = $req->fetch();
      $status=$row['status_id'];
    }

    $creator=$_SESSION['id'];
    $last_modifier=$_SESSION['id'];
    $creation_date=date("Y-m-d H:i:s");
    $last_modif_date=date("Y-m-d H:i:s");
    $target_date=$_POST['create-task-target'];


    //insert record in table task
		$inserttask=$bdd->prepare('INSERT INTO task(title,description,creator,creation_date,assignee,last_modifier,last_modification_date,target_delivery_date,team,flow,status,priority) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)');
		$inserttask->execute(array($title,$description,$creator,$creation_date,$assignee,$last_modifier,$last_modif_date,$target_date,$team,$flow,$status,$priority));

    //update history
    $history_manager=new historyManager();
    $history_manager->addEvent($team,'task_creation',$bdd->lastInsertId());

    $bdd=null;
  }




  //removes task from DB
  public function deleteTask($task_id){
    $bdd = $this->connectDB();

    //delete task
    $req=$bdd->prepare('DELETE FROM task WHERE id=?');
    $req->execute(array($task_id));

    $bdd=null;

    return true;
	}



  //check if user is allowed to access a task (user can only access tasks from the teams he belongs to)
  public function accessTaskAuth($task_id){
    $bdd = $this->connectDB();
    $auth=false;

    $user_id=$_SESSION['id'];
    $req=$bdd->prepare('SELECT * from task, user_team WHERE task.team=user_team.id_team AND task.id= ? AND user_team.id_user=?');
    $req->execute(array($task_id, $user_id));
    if ($req->rowCount()){
      $auth=true;
    }

    $bdd=null;
    return $auth;
  }




  //get data attached to task
  public function getTaskData($task_id){
    $bdd = $this->connectDB();
    //get team id
    $req=$bdd->prepare('SELECT flow.name AS flow_name, team.name AS team_name, task.* FROM
      task,team,flow WHERE team.id=task.team AND flow.id=task.flow AND task.id= ?');
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




  //get user tasks count
  public function getUserTasksCount(){
    $bdd = $this->connectDB();
    $user_id=$_SESSION['id'];

    //get tasks count
    $req=$bdd->prepare('SELECT COUNT(*) as nb_tasks FROM task WHERE assignee= ?');
    $req->execute(array($user_id));

    $count=0;
    if ($req->rowCount()){
      $row = $req->fetch();
      $count=$row['nb_tasks'];
    }

    $bdd=null;
    return $count;
  }




  //get list of tasks attached to team
  public function getTasksList($team_id, $filter="", $page=1){
    $bdd = $this->connectDB();

    $offset_p=($page-1)*MAX_TASK_ROWS;
    $tasks=array();

    //1) get results Count
    if($filter==""){
      $str_query='SELECT COUNT(*) AS nb_rows
      FROM task
      WHERE team= ? AND is_closed=0';
      $params=array($team_id);
    }else{
      $str_query='SELECT COUNT(*) AS nb_rows
      FROM task
      LEFT JOIN user
      ON task.assignee=user.id
      WHERE team= ? AND is_closed=0 AND (title LIKE "%"?"%" OR user.fullname LIKE "%"?"%" OR status LIKE "%"?"%")';
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
      $str_query='SELECT task.id AS id, task.title AS title, IFNULL(user.fullname,"") AS fullname,
      status.name as status, task.priority as priority
      FROM task
      LEFT JOIN user
      ON task.assignee=user.id
      LEFT JOIN status
      ON task.status=status.id
      WHERE team= ? AND is_closed=0
      ORDER BY last_modification_date DESC limit '.MAX_TASK_ROWS.' OFFSET '.$offset_p;

    }else{
      $str_query='SELECT task.id AS id, task.title AS title, IFNULL(user.fullname,"") AS fullname,
        status.name as status, task.priority as priority
        FROM task
        LEFT JOIN user
        ON task.assignee=user.id
        LEFT JOIN status
        ON task.status=status.id
        WHERE team= ? AND is_closed=0 AND (title LIKE "%"?"%" OR user.fullname LIKE "%"?"%" OR status LIKE "%"?"%")
        ORDER BY last_modification_date DESC limit '.MAX_TASK_ROWS.' OFFSET '.$offset_p;
    }
    $req=$bdd->prepare($str_query);
    $req->execute($params);

    if ($req->rowCount()){
      while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
        $tasks_list[]=$row;
      }
    }

    $tasks['list'] = $tasks_list;

    $bdd=null;
    return $tasks;
  }




  //close task
  public function closeTask($task_id){
    $bdd=$this->connectDB();

    //update record in table task
		$inserttask=$bdd->prepare('UPDATE task SET is_closed=1 WHERE id=?');
		$inserttask->execute(array($task_id));

    $bdd=null;
    return true;

  }




  //update task data
  public function updateTask(){
    //date_default_timezone_set('Europe/Paris');
    $bdd = $this->connectDB();
		//faire les contrôles de saisie en JS
    $task_id=htmlspecialchars($_POST['modify-task-id']);
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
		$inserttask->execute(array($title,$description,$assignee,$last_modifier,$last_modif_date,$target_date,$status,$priority,$task_id));

    //retrieve the task team id
    $req=$bdd->prepare('SELECT team from task WHERE id= ?');
    $req->execute(array($task_id));

    if ($req->rowCount()){
      $row = $req->fetch();
      $team=$row['team'];
    }

    //update history
    $history_manager=new historyManager();
    $history_manager->addEvent($team,'task_update',$task_id);

    $bdd=null;

	}

}
