<div class="ui dimmer modals page transition visible active" style="display: block !important;" id="connexion-dimmer">
  <div class="ui modal transition visible active" id="modal-connexion" style="margin-top:-150px;margin-left:-250px;">
    <div class="header">Connexion</div>
    <div class="content">
      <form id="connexion" action="index.php", method="POST">
        <div class="ui input" >
          <input type="text" name="email_co" placeholder="Email" id="email-co" >
        </div>
        <div class="ui input">
          <input type="password" name="password_co" placeholder="Mot de passe" id="password-co">
        </div>
        <span class="error" id="err-username">
          <?php
            if (isset($err_mess)){
              echo $err_mess;
            }
          ?>
        </span>
      </form>
    </div>
    <div class="actions">
      <div class="ui black deny button" id="connexion-cancel">
        Annuler
      </div>
      <div class="ui positive right labeled icon button" id="connexion-confirm">
        Se connecter
        <i class="checkmark icon"></i>
      </div>
    </div>
  </div>
</div>
