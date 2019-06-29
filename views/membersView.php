<div>
  <div class="ui secondary pointing menu">
    <a href="/workflow/members/<?= $username ?>" class="<?php if(!isset($section) || $section=='edit'){echo 'active';} ?> item">
      Profil
    </a>
    <?php if ($member_page==$username){ ?>
    <a href="/workflow/members/<?= $username ?>/parameters" class="<?php if(isset($section) && $section=='parameters'){echo 'active';} ?> item">
      Paramètres
    </a>
    <?php } ?>
  </div>
  <?php if(!isset($section) || $section=='edit'){ ?>
    <div class="ui segment" id="profil">
      <div class="ui stackable two column grid">
        <div class="six wide column">
          <div class="ui card">
            <div class="image">
              <img src="/workflow/static/avatar/<?= $user_data['avatar'] ?>.jpg">
            </div>
            <div class="content">
              <a class="header"><?= htmlspecialchars($user_data['fullname']) ?></a>
              <div class="meta">
                <span class="date"><?= htmlspecialchars($member_page) ?></span>
              </div>
              <div class="description">
                <?= htmlspecialchars($user_data['firstname']) ?> a rejoint le site en 2018.
              </div>
            </div>
            <div class="extra content">
              <a>
                <i class="user icon"></i>
                <?= $teams_count ?> Equipe(s)
              </a>
            </div>
          </div>
        </div>
        <?php if ($member_page==$username){ ?>
        <div class="ten wide column">
          <h3 class="ui dividing header">A propos de moi</h3>
          <?php if (isset($section) && $section=='edit'){ ?>
            <form id="form-edit-user-data" action="/workflow/members/<?= htmlspecialchars($member_page) ?>", method="POST">
              <h4 class="ui header" style="margin-bottom:3px;">Prénom</h4>
              <div class="ui icon input" style="margin-top:0;">
                <input type="text" name="user-firstname" value="<?= htmlspecialchars($user_data['firstname']) ?>">
              </div>
              <h4 class="ui header" style="margin-bottom:3px;">Nom</h4>
              <div class="ui icon input" style="margin-top:0;">
                <input type="text" name="user-lastname" value="<?= htmlspecialchars($user_data['lastname']) ?>">
              </div>
              <h4 class="ui header" style="margin-bottom:3px;">Sexe</h4>
              <select class="ui selection dropdown" style="font-size:small;" name="user-gender">
                <option <?php if ($user_data['gender']=='male'){ echo 'selected'; }?> value="male">M</option>
                <option <?php if ($user_data['gender']=='female'){ echo 'selected'; }?> value="female">F</option>
              </select>
              <h4 class="ui header" style="margin-bottom:3px;">Date de naissance</h4>
              <div class="ui left icon input" style="width:200px;margin-top:0;">
                <i class="calendar icon"></i>
                <input type="date" name="user-birthdate" value="<?= $user_data['birthdate'] ?>">
              </div>
              <h4 class="ui header" style="margin-bottom:3px;">Pays</h4>
              <select class="ui selection dropdown" style="font-size:small;width:100%;" name="user-country">
                <?php for($i=0;$i<count($countries_list);$i++){ ?>
                  <option <?php if ($user_data['country']==$countries_list[$i]['code']){ echo 'selected'; }?> value="<?= $countries_list[$i]['code'] ?>"><?= $countries_list[$i]['name'] ?></option>
                <?php } ?>
              </select>
            </form>
            <button class="ui button" style="margin-top:20px;">Enregistrer</button>
          <?php }else{ ?>
            <h4 class="ui header" style="margin-bottom:3px;">Prénom:</h4>
            <p><?= htmlspecialchars($user_data['firstname']) ?></p>
            <h4 class="ui header" style="margin-bottom:3px;">Nom:</h4>
            <p><?= htmlspecialchars($user_data['lastname']) ?></p>
            <h4 class="ui header" style="margin-bottom:3px;">Sexe:</h4>
            <p><?= htmlspecialchars($user_data['gender']) ?></p>
            <h4 class="ui header" style="margin-bottom:3px;">Date de naissance:</h4>
            <p><?= htmlspecialchars($user_data['birthdate']) ?></p>
            <h4 class="ui header" style="margin-bottom:3px;">Pays:</h4>
            <p><?= htmlspecialchars($user_data['country']) ?></p>
            <a href="/workflow/members/<?= $username ?>/edit" class="ui button" style="margin-top:20px;"><i class="icon edit"></i>Modifier</a>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
    </div>
  <?php }else{ ?>
    <div class="ui segment" id="parameters">
      <div class="row">
        <h3 class="ui dividing header">Votre email</h3>
        <form id="form-edit-email" action="/workflow/members/<?= htmlspecialchars($member_page) ?>", method="POST">
          <h4 class="ui header" style="margin-bottom:3px;">Adresse e-mail actuelle</h4>
          <div class="ui icon input" style="margin-top:0;">
            <input type="text" name="user-existing-email" value="<?= htmlspecialchars($user_data['email']) ?>">
          </div>
          <h4 class="ui header" style="margin-bottom:3px;">Nouvel e-mail</h4>
          <div class="ui icon input" style="margin-top:0;">
            <input type="text" name="user-new-email" value="">
          </div>
        </form>
        <button class="ui button" style="margin-top:20px;">Enregistrer</button>
      </div>
      <div class="row" style="margin-top:50px;">
        <h3 class="ui dividing header">Changer votre mot de passe</h3>
        <form id="form-edit-pwd" action="/workflow/members/<?= htmlspecialchars($member_page) ?>", method="POST">
          <h4 class="ui header" style="margin-bottom:3px;">Mot de passe actuel</h4>
          <div class="ui icon input" style="margin-top:0;">
            <input type="password" name="user-existing-pwd" value="">
          </div>
          <h4 class="ui header" style="margin-bottom:3px;">Nouveau mot de passe</h4>
          <div class="ui icon input" style="margin-top:0;">
            <input type="password" name="user-new-pwd" value="">
          </div>
          <h4 class="ui header" style="margin-bottom:3px;margin-top:5px;">Confirmer nouveau mot de passe</h4>
          <div class="ui icon input" style="margin-top:0;">
            <input type="password" name="user-new-pwd-confirm" value="">
          </div>
        </form>
        <button class="ui button" style="margin-top:20px;">Changer</button>
      </div>
    </div>
  <?php } ?>
</div>
