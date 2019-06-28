<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
    <head>
      <title><?= $current_page['name']; ?></title>
		  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1">
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
      <?php if (isset($modals)){
        for ($i = 0; $i < count($modals); $i++){
          include("views/".$modals[$i]);
        }
      }
      ?>


      <!-- Header -->
      <?php require("views/header.php"); ?>


      <!-- Main content -->
  		<div class="main-content <?php if(isset($_SESSION['id'])){echo 'site-container';} ?>">
        <?php if (isset($main_view)){
          require('views/'.$main_view);
        }else{
          echo '<img src="/workflow/static/images/whiteboard-4054377_1920.jpg"/ style="width :100%;">';
        } ?>
  		</div>

    </body>
</html>
