<div class="ui dimmer modals page transition visible active" style="display: block !important;" id="registration-dimmer">
  <div class="ui modal transition visible active" id="modal-registration" style="margin-top:-300px;margin-left:-250px;">
    <div class="header">Inscription</div>
    <div class="content">
      <form id="inscription" action="index.php", method="POST">
        <div class="ui input" >
          <input type="text" name="firstname" placeholder="Prénom" id="firstname" >
        </div>
        <div class="ui input" >
          <input type="text" name="lastname" placeholder="Nom" id="lastname" >
        </div>
        <div class="ui input" >
          <input type="text" name="email" placeholder="Email" id="email" >
        </div>
        <div class="ui input" >
          <input type="text" name="email2" placeholder="Confirmez email" id="email2" >
        </div>
        <div class="ui input" >
          <input type="password" name="password" placeholder="Mot de passe" id="password" >
        </div>
        <div class="ui input" >
          <input type="password" name="password2" placeholder="Confirmez mot de passe" id="password2" >
        </div>
        <div class="ui input" style="display:none;" >
          <input type="text" name="avatar" id="avatar" >
        </div>
        <span class="error" id="err-inscription">
          <?php
            if (isset($err_insc)){
              echo $err_insc;
            }
          ?>
        </span>
      </form>
      <div style="margin-top:20px;margin-bottom:10px;">Sélectionnez un avatar:</div>
      <div class="ui six doubling link cards" id="avatar-list">
        <div class="card" id="ade">
          <div class="image">
            <img src="/workflow/static/avatar/ade.jpg">
          </div>
        </div>
        <div class="card" id="chris">
          <div class="image" >
            <img src="/workflow/static/avatar/chris.jpg">
          </div>
        </div>
        <div class="card" id="elliot">
          <div class="image">
            <img src="/workflow/static/avatar/elliot.jpg">
          </div>
        </div>
        <div class="card" id="helen">
          <div class="image">
            <img src="/workflow/static/avatar/helen.jpg">
          </div>
        </div>
        <div class="card" id="jenny">
          <div class="image">
            <img src="/workflow/static/avatar/jenny.jpg">
          </div>
        </div>
        <div class="card" id="joe">
          <div class="image">
            <img src="/workflow/static/avatar/joe.jpg">
          </div>
        </div>
      </div>
    </div>
    <div class="actions">
      <div class="ui black deny button" id="inscription-cancel">
        Annuler
      </div>
      <div class="ui positive right labeled icon button" id="inscription-confirm">
        S'inscrire
        <i class="checkmark icon"></i>
      </div>
    </div>
  </div>
</div>
