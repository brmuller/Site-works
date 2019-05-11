<?php
  //the session starts
  session_start();
  if(!isset($_SESSION['id'])){
  	header('Location: /workflow/');
  	exit;
  }

  function loadClass($class)
  {
    require 'models/' . $class . '.php'; // On inclut la classe correspondante au paramètre passé.
  }

  spl_autoload_register('loadClass');

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


  //handle GETS on page
  if (isset($_GET['type'])){
    $type=$_GET['type'];
    switch ($type) {
      case "updatetask":
        if (isset($_GET['id'])){
          $task_id=$_GET['id'];
          $task_data=$task_manager->getTaskData($task_id);
          $comments=$comment_manager->getTaskComments($task_id);
          $flow_id=$task_data['flow']['id'];
          $status_list=$flow_manager->getStatusList($flow_id);
          $team_id=$task_data['team']['id'];
          $members_list=$team_manager->getTeamMembers($team_id);
          $task_uploads=$task_manager->getTaskFiles($task_id);
          $update_task=true;
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
    }
  }

  if (isset($_SESSION['team'])){
    $team=$_SESSION['team'];
    $tasks=$task_manager->getTasksList($team);
    $members_count=count($team_manager->getTeamMembers($team));
    $tasks_list=$tasks['list'];
    $nb_rows=count($tasks_list);
    $nb_pages=ceil($tasks['rows_count']/8);
  }


  $strname=ucfirst(strtolower($_SESSION['firstname']));
  $avatar=$_SESSION['avatar'];
  $teams=$team_manager->getTeams();
  $flows=$flow_manager->getUserFlows();

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
    <head>
      <title>Workflow</title>
		  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
			<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
			<script type="text/javascript" src="/workflow/js/main.js"></script>
      <script type="text/javascript" src="/workflow/js/moment.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.7/semantic.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script><!-- graphs -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css" />
  		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.7/semantic.min.css" />
      <link href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/icon.min.css" rel="stylesheet" type="text/css">
		  <link rel="stylesheet" type="text/css" href="/workflow/css/main.css" />
    </head>
    <body>
      <?php if (isset($new_team) && $new_team){include("views/modalCreateTeam.php");} ?>
      <?php if (isset($join_team) && $join_team){include("views/modalJoinTeam.php");} ?>
      <?php if (isset($new_flow) && $new_flow){include("views/modalCreateFlow.php");} ?>
      <?php if (isset($new_task) && $new_task){include("views/modalCreateTask.php");} ?>
      <?php if (isset($update_task) && $update_task){include("views/modalUpdateTask.php");} ?>
      <?php require("views/header.php"); ?> <!--page header -->
  		<div id="dashboard" class="main-content"> <!-- Page body -->
        <div class="ui grid">
          <div class="four wide column"> <!-- Menu -->
            <div class="ui vertical menu">
              <div class="item">
                <div class="header">
                  Tâches
                  <div class="ui teal left pointing label" style="margin-left: 8em;">4</div>
                </div>
                <div class="menu">
                  <a href="/workflow/dashboard.php?type=newtask" class="item"><i class="plus icon"></i>Nouvelle tâche</a>
                </div>
              </div>
              <div class="item">
                <div class="header">
                  Equipes
                </div>
                <div class="menu">
                  <?php if (count($teams)>0){ ?>
                      <?php for ($i = 0; $i < count($teams); $i++){ ?>
                        <a class="item"><?= $teams[$i]['name'] ?></a>
                      <?php } ?>
                  <?php  } ?>
                  <div class="ui dropdown item">
                    <span style="font-weight:bold;color:darkgrey;">Plus</span>
                    <i class="dropdown icon"></i>
                    <div class="menu transition hidden">
                      <a href="/workflow/dashboard.php?type=newteam" class="item" id="but-create-team"><i class="edit icon"></i> Créer une équipe</a>
                      <a href="/workflow/dashboard.php?type=jointeam" class="item" id="but-join-team"><i class="users icon"></i> Rejoindre une équipe</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="header">Flows</div>
                <div class="menu">
                  <?php if (count($flows)>0){ ?>
                      <?php for ($i = 0; $i < count($flows); $i++){ ?>
                        <a class="item"><?= $flows[$i] ?></a>
                      <?php } ?>
                  <?php  } ?>
                  <div class="ui dropdown item">
                    <span style="font-weight:bold;color:darkgrey;">Plus</span>
                    <i class="dropdown icon"></i>
                    <div class="menu transition hidden">
                      <a href="/workflow/dashboard.php?type=newflow" class="item" id="but-create-flow"><i class="edit icon"></i> Créer un flow</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="header">Stats</div>
                <div class="menu">
                  <a class="item">E-mail Support</a>
                  <a class="item">FAQs</a>
                </div>
              </div>
            </div>
          </div>
          <div class="twelve wide column"> <!-- Dashboard -->
            <div class="row">
              <div id="tasks-header" style="margin-bottom: 10px;">
                <div class="ui grid" style="margin: auto;">
                  <div class="eight wide column" style="position:relative;padding-bottom:0;">
                    <div style="position: absolute;bottom: 0;left: 0;">
                      <span style="font-size:small;">
                        <?php if (count($teams)>0){ ?>
                          <select class="ui selection dropdown" id="team-selection">
                            <?php if (!isset($team)){ ?>
                              <option value="" disabled selected>Equipe</option>
                            <?php } ?>
                            <?php for ($i = 0; $i < count($teams); $i++) { ?>
                              <?php if (isset($team) AND $team==$teams[$i]['id']){ ?>
                                <option selected value="<?= $teams[$i]['id'] ?>"><?= $teams[$i]['name'] ?></option>
                              <?php }else{ ?>
                                <option value="<?= $teams[$i]['id'] ?>"><?= $teams[$i]['name'] ?></option>
                              <?php } ?>
                            <?php } ?>
                          </select>
                        <?php }else{ ?>
                          <strong>Vous n'avez pas encore d'équipe</strong>
                        <?php } ?>
                      </span>
                    </div>
                  </div>
                  <div class="eight wide column" style="position:relative;padding-bottom:0;">
                    <div class="ui icon input">
                      <input class="prompt" type="text" placeholder="Filtrer les tâches..." id="task-filter">
                      <i class="search icon"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" style="float:right;" id="team-stats">
              <?php if (isset($tasks_list) && $nb_rows>0){ ?>
              <div class="ui tiny statistics">
                <div class="statistic" style="margin-bottom:0;">
                  <div class="value">
                    <?= $tasks['rows_count'] ?>
                  </div>
                  <div class="label">
                    Tâches
                  </div>
                </div>
                <div class="statistic" style="margin-bottom:0;">
                  <div class="value">
                    <img src="static/avatar/joe.jpg" class="ui circular inline image" style="height:25px;">
                    <?= $members_count ?>
                  </div>
                  <div class="label">
                    Membres
                  </div>
                </div>
              </div>
              <?php } ?>
            </div>
            <div class="row" id="task-table-container">
            <?php if (isset($tasks_list)){ ?>
            <?php if ($nb_rows>0){ ?>
              <table class="ui selectable celled table" id="task-table">
                <thead>
                  <tr>
                    <th>Titre</th>
                    <th>Responsable</th>
                    <th>Status</th>
                    <th>Priorité</th>
                  </tr>
                </thead>
                <tbody>
                  <?php for ($i=0;$i<$nb_rows;$i++){ ?>
                    <tr id="<?= $tasks_list[$i]['id'] ?>">
                      <td><?= $tasks_list[$i]['title'] ?></td>
                      <td><?= $tasks_list[$i]['fullname'] ?></td>
                      <td><?= $tasks_list[$i]['status'] ?></td>
                      <td><div class="ui star rating disabled" data-rating="<?= $tasks_list[$i]['priority'] ?>" data-max-rating="3"></div></td>
                    </tr>
                  <?php } ?>
                </tbody>
                <?php if ($nb_pages>1){ ?>
                <tfoot>
                  <tr>
                    <th colspan="4">
                      <div class="ui right floated pagination menu">
                        <a class="icon item">
                          <i class="left chevron icon"></i>
                        </a>
                      <?php for($i=0;$i<$nb_pages;$i++){ ?>
                        <a class="item page <?php if ($i==0){echo 'active';} ?>"><?= ($i+1) ?></a>
                      <?php } ?>
                        <a class="icon item">
                          <i class="right chevron icon"></i>
                        </a>
                      </div>
                    </th>
                  </tr>
                </tfoot>
                <?php } ?>
              </table>
            <?php }else{ ?>
              <span>Il n\'y a pas de tâches pour cette équipe.</span>
            <?php } ?>
            <?php }else{ ?>
              <span>Veuillez sélectionner une équipe.</span>
            <?php } ?>
            </div>
          </div>
        </div>
  		</div>
    </body>
</html>
