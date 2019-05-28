<?php

require_once("Manager.php");

class userManager extends Manager
{

  //get user info
  public function getUserData($user_id){
    $bdd = $this->connectDB();
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




  //connect to dashboard
  public function connect($email,$password){
    $bdd = $this->connectDB();
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

      header('Location: /workflow/dashboard/');
      exit;
    }
    $bdd=null;
  }




  //logout
  public function deconnect(){
    session_destroy();
  	header('Location: /workflow/');
  	exit;
  }




  public function register($firstname,$lastname,$email,$password,$avatar){
    $bdd = $this->connectDB();

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
		header('Location: /workflow/dashboard/');
		exit;
	}




  //check if user is recorded in DB
  public function checkUser($email,$password){
    $bdd = $this->connectDB();
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
  public function checkEmail($email){
    $bdd = $this->connectDB();

    $isRecorded=false;
    $req=$bdd->prepare('SELECT * FROM user WHERE email= ?');
    $req->execute(array($email));

    if ($req->rowCount()){
      $isRecorded=true;
    }

    $bdd=null;
    return $isRecorded;
  }

}
