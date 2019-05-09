<div id="header"> <!-- Page header -->
  <div class="grid-wrapper">
    <div class="grid-content">
      <div class="header-logo">
        <a href="index.php" class="header-logo"><img src="static/images/youflow-logo.png"></a>
      </div>
      <div class="header-main-content">
        <div>
          <!--<button id="but-deconnect" class="ui blue basic button">Déconnexion</button> -->
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
      </div>
    </div>
  </div>
</div>



<div id="header">
  <div class="grid-wrapper">
    <div class="grid-content">
      <div class="header-logo">
        <a href="index.php" class="header-logo"><img src="static/images/youflow-logo.png"></a>
      </div>
      <div class="header-main-content">
        <div id="inscription-but">
          <button id="but-insc" class="ui primary button">S'inscrire</button>
        </div>
        <div id="connection-but">
          <button id="but-connect" class="ui blue basic button">Connexion</button>
        </div>
      </div>
    </div>
  </div>
</div>
