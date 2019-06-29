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




  //generate username
  public function getUserName($fn,$ln){
    $bdd = $this->connectDB();
    //get team id
    $req=$bdd->prepare(
      'SELECT username
      FROM user
      WHERE firstname= ? AND lastname=?
      ORDER BY username DESC
      LIMIT 1'
    );
    $req->execute(array($fn,$ln));

    $username='';
    if ($req->rowCount()){
      $row = $req->fetch();
      $last_un=$row['username'];
      $last_id=explode(".",$last_un)[2];

      $username = $fn.'.'.$ln.'.'.($last_id+1);
    }else{
      $username = $fn.'.'.$ln.'.1';
    }

    $bdd=null;
    return $username;

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
    $firstname=strtolower(trim($firstname));
    $lastname=strtolower(trim($lastname));
    $email=strtolower(trim($email));
		$fullname=ucfirst($firstname).' '.ucfirst($lastname);
    $username=$this->getUserName($firstname,$lastname);
		$hash_password=sha1($password);

		$insertuser=$bdd->prepare(
      'INSERT INTO user(password,firstname,lastname,fullname,email,avatar,username)
      VALUES(?,?,?,?,?,?,?)'
    );
		$insertuser->execute(array($hash_password,$firstname,$lastname,$fullname,$email,$avatar,$username));

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




  //check if user is recorded in DB
  public function getUserId($email,$password){
    $bdd = $this->connectDB();
    $hash_password=sha1($password);

    $user_id='';
    //$req=$bdd->query("SELECT * FROM user");
    $req=$bdd->prepare('SELECT * FROM user WHERE email= ? AND password= ?');
    $req->execute(array($email,$hash_password));

    if ($req->rowCount()){
      $row=$req->fetch();
      $user_id=$row['id'];
    }

    $bdd=null;
    return $user_id;
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
