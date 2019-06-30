<?php
  //the session starts
  //session_start();
  if(!isset($_SESSION['id'])){
  	header('Location: /workflow/');
  	exit;
  }


  $current_page=array(
    'id' => 'not',
    'name' => 'Cette page n\'existe pas'
  );

  $strname=ucfirst($_SESSION['firstname']).' '.ucfirst($_SESSION['lastname']);
  $avatar=$_SESSION['avatar'];
  $username=$_SESSION['username'];

  //define items to include in the page view
  $main_view='pagenotfoundView.php';

  //call template and display above items in the page
  require('views/template.php');
