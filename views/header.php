<div id="header"> <!-- Page header -->
  <div class="grid-wrapper">
    <div class="grid-content">
      <div class="header-logo">
        <a href="index.php" class="header-logo"><img src="static/images/youflow-logo.png"></a>
      </div>
      <?php if (isset($_SESSION['id'])){ ?>
      <div class="ui massive text menu" style="margin-top:5px;margin-left:30px;">
        <a class="<?php if ($current_page=='history'){echo 'active';} ?> item">
          Historique
        </a>
        <a class="<?php if ($current_page=='stats'){echo 'active';} ?> item">
          Stats
        </a>
      </div>
      <?php } ?>
      <div class="header-main-content">
        <?php if (isset($_SESSION['id'])){ ?>
        <div>
          <a href="/workflow/dashboard.php?type=logout" class="ui blue basic button">Déconnexion</a>
        </div>
        <div class="ui header" style="margin-right:20px;line-height:60px;margin-top:0;">
          <span>Bienvenue <?= $strname ?></span>
        </div>
        <div class="ui one doubling cards">
          <div class="card" style="height:45px;margin-top:19px;">
            <div class="image">
              <img style= "height:45px;width:45px;" src="/workflow/static/avatar/<?= $avatar ?>.jpg">
            </div>
          </div>
        </div>
        <?php }else{ ?>
        <div>
          <a href="/workflow/index.php?type=signup" class="ui primary button">S'inscrire</a>
        </div>
        <div>
          <a href="/workflow/index.php?type=signin" class="ui blue basic button">Connexion</a>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
