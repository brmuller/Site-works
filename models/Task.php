<?php

require_once("Manager.php");

class Task extends Manager
{
  private $_id;
  private $_title;
  private $_description;
  private $_creator;
  private $_creation_date;
  private $_assignee="";
  private $_last_modifier;
  private $_last_modification_date;
  private $_target_delivery_date;
  private $_team;
  private $_flow;
  private $_status;
  private $_priority;
  private $_is_closed=0;


  public function __construct($data) // Constructeur demandant 2 paramètres
  {
    //initialise the standard attributes with the hydrate function
    $this->hydrate($data);
  }


  public function hydrate(array $data)
  {
    foreach ($data as $key => $value)
    {
      // On récupère le nom du setter correspondant à l'attribut.
      $method = 'set'.ucfirst($key);

      // Si le setter correspondant existe.
      if (method_exists($this, $method))
      {
        // On appelle le setter.
        $this->$method($value);
      }
    }
  }


  public function id() { return $this->_id; }
  public function title() { return $this->_title; }
  public function description() { return $this->_description; }
  public function creator() { return $this->_creator; }
  public function creation_date() { return $this->_creation_date; }
  public function assignee() { return $this->_assignee; }
  public function last_modifier() { return $this->_last_modifier; }
  public function last_modification_date() { return $this->_last_modification_date; }
  public function target_delivery_date() { return $this->_target_delivery_date; }
  public function team() { return $this->_team; }
  public function flow() { return $this->_flow; }
  public function status() { return $this->_status; }
  public function priority() { return $this->_priority; }
  public function is_closed() { return $this->_is_closed; }


  public function setId($id){
    $this->_id=$id;
  }

  public function setTitle($title){
    $this->_title=$title;
  }

  public function setDescription($description){
    $this->_description=$description;
  }

  public function setCreator($creator){
    $this->_creator=$creator;
  }

  public function setCreation_date($creation_date){
    if ($creation_date==""){
      $this->_creation_date=date("Y-m-d H:i:s");
    }else{
      $this->_creation_date=$creation_date;
    }
  }

  public function setAssignee($assignee){
    $this->_assignee=$assignee;
  }

  public function setLast_modifier($last_modifier){
    $this->_last_modifier=$last_modifier;
  }

  public function setTeam($team){
    $this->_team=$team;
  }

  public function setFlow($flow){
    $this->_flow=$flow;
  }

  public function setLast_modification_date($last_modification_date){
    if ($last_modification_date==""){
      $this->_last_modification_date=date("Y-m-d H:i:s");
    }else{
      $this->_last_modification_date=$last_modification_date;
    }
  }

  public function setTarget_delivery_date($target_delivery_date){
    $this->_target_delivery_date=$target_delivery_date;
  }

  public function setStatus($status){
    if (!isset($status) || $status==""){
      //initialise the status with the first status of the related flow
      $flow_manager=new flowManager();
      $this->_status=$flow_manager->getFirstStatus($this->_flow);
    }else{
      $this->_status=$status;
    }
  }

  public function setPriority($priority){
    $priority=(int) $priority;
    if ($priority<0 || $priority>3){
      trigger_error("Priority doit être compris entre 0 et 3.", E_USER_WARNING);
      return;
    }
    $this->_priority=$priority;
  }

  public function setIs_closed($is_closed){
    $is_closed=(int) $is_closed;
    if ($is_closed!==0 && $is_closed!==1){
      trigger_error("Is_closed doit être 0 ou 1.", E_USER_WARNING);
      return;
    }
    $this->_is_closed=$is_closed;
  }

}
