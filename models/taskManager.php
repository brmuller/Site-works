<?php
class taskManager
{
  //get list of files attached to task
  public function getTaskUploads($task_id){

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
}

?>
