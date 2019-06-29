<div id="header"> <!-- Page header -->
  <div class="grid-wrapper">
    <div class="row">
      <div class="grid-content">
        <div class="header-logo">
          <a href="/workflow/" class="header-logo"><img src="/workflow/static/images/youflow-logo.png"></a>
        </div>
        <?php if (isset($_SESSION['id'])){ ?>
        <div class="ui massive text menu medium-screen-hide" style="margin-top:5px;margin-left:30px;">
          <a class="<?php if ($current_page['id']=='history'){echo 'active';} ?> item" href="/workflow/history">
            Historique
          </a>
          <a class="<?php if ($current_page['id']=='stats'){echo 'active';} ?> item" href="/workflow/stats">
            Statistiques
          </a>
        </div>
        <?php } ?>
        <div class="header-main-content">
          <?php if (isset($_SESSION['id'])){ ?>
          <div class="medium-screen-show" style="display:none;">
            <i class="big bars icon"></i>
          </div>
          <div class="ui one doubling clickable cards mobile-screen-show" id="member-sub-header">
            <div class="card" style="height:45px;margin-top:19px;width:45px;">
              <div class="image">
                <img style= "height:45px;width:45px;" src="/workflow/static/avatar/<?= $avatar ?>.jpg">
              </div>
            </div>
          </div>
          <div class="ui right dropdown item mobile-screen-hide">
            <div class="ui one doubling cards">
              <div class="card" style="height:45px;margin-top:19px;">
                <div class="image">
                  <img style= "height:45px;width:45px;" src="/workflow/static/avatar/<?= $avatar ?>.jpg">
                </div>
              </div>
            </div>
            <div class="menu transition hidden" style="right:0;left:auto;">
              <div class="item"><i class="user icon"></i><?= htmlspecialchars($strname) ?></div>
              <div class="item"><i class="cog icon"></i>Mes paramètres</div>
              <div class="item">
                <a href="/workflow/dashboard/logout" class="ui blue basic button">Déconnexion</a>
              </div>
            </div>
          </div>
          <?php }else{ ?>
          <div>
            <a href="/workflow/signup" class="ui primary button">S'inscrire</a>
          </div>
          <div>
            <a href="/workflow/signin" class="ui blue basic button">Connexion</a>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <div class="row" id="sub-header" style="display:none;">
      <div class="ui middle aligned selection list">
        <div class="item">
          <i class="user icon"></i>
          <div class="content">
            <div class="header"><?= htmlspecialchars($strname) ?></div>
          </div>
        </div>
        <div class="item">
          <i class="cog icon"></i>
          <div class="content">
            <div class="header">Mes paramètres</div>
          </div>
        </div>
        <div class="item">
          <a href="/workflow/dashboard/logout">
            <i class="power off icon" style="float:left;"></i>
            <div class="content">
              <div class="header">Déconnexion</div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
