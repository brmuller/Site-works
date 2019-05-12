<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
    <head>
      <title><?php if(isset($_SESSION['id'])){echo 'Tableau de bord';}else{echo 'Outil générique de gestion de process';} ?></title>
		  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
			<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
			<script type="text/javascript" src="/workflow/js/main.js"></script>
      <script type="text/javascript" src="/workflow/js/stats.js"></script>
      <script type="text/javascript" src="/workflow/js/moment.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.7/semantic.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script><!-- graphs -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css" />
  		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.7/semantic.min.css" />
      <link href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/icon.min.css" rel="stylesheet" type="text/css">
		  <link rel="stylesheet" type="text/css" href="/workflow/css/main.css" />
    </head>
    <body>
      <!-- Modals -->
      <?php if (isset($_SESSION['id'])){
        if (isset($new_team) && $new_team){include("views/modalCreateTeam.php");}
        if (isset($join_team) && $join_team){include("views/modalJoinTeam.php");}
        if (isset($new_flow) && $new_flow){include("views/modalCreateFlow.php");}
        if (isset($new_task) && $new_task){include("views/modalCreateTask.php");}
        if (isset($update_task) && $update_task){include("views/modalUpdateTask.php");}
      }else{
        if (isset($signin)){require("views/modalConnexion.php");}
        if (isset($signup)){require("views/modalRegistration.php");}
      } ?>

      <!--page header -->
      <?php require("views/header.php"); ?>

      <!-- Page main content -->
  		<div <?php if(isset($_SESSION['id'])){echo 'id="dashboard"';} ?> class="main-content">
        <?php if (isset($_SESSION['id'])){
          require("views/dashboardView.php");
        }else{
          echo '<img src="/workflow/static/images/whiteboard-4054377_1920.jpg"/ style="width :100%;">';
        } ?>
  		</div>
    </body>
</html>
