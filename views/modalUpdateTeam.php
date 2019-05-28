
<div class="ui dimmer modals page transition visible active" style="display: block !important;" id="modify-team-dimmer">
  <div class="ui modal transition visible active" id="modal-modify-team" style="width:700px;height:370px;position:relative;margin-top:-200px;margin-left:-350px;"> <!--Modal modifier equipe -->
    <div class="header">
      <div class="ui grid">
        <div class="nine wide column">
          <div class="row">
            <div class="column">Equipe: <?= htmlspecialchars($team_data['name']) ?></div>
          </div>
          <div class="row">
            <div class="column" style="font-weight:normal;font-size:small;">Créateur:  <?= htmlspecialchars($team_data['fullname']) ?></div>
          </div>
        </div>
      </div>
    </div>
    <div class="ui grid" style="margin:0;">
      <div class="seven wide column">
        <div class="ui comments">
          <h3 class="ui dividing header">Membres</h3>
          <div class="scrolling content" style="height:160px;overflow:auto;">
          <?php for ($i = 0; $i < count($team_members); $i++) { ?>
              <div class="comment">
                <a class="avatar"><img src="/workflow/static/avatar/<?= $team_members[$i]['avatar'] ?>.jpg"></a>
                <div class="content">
                  <a class="author"><?= htmlspecialchars($team_members[$i]['fullname']) ?></a>
                  <div class="text">Depuis le <?= $team_members[$i]['join_date'] ?></div>
                </div>
              </div>
          <?php  } ?>
          </div>
        </div>
      </div>
      <div class="nine wide column">
        <form id="form-modify-team" action="/workflow/dashboard/", method="POST">
          <div class="ui icon input" style="margin-top:0;display:none;">
            <input type="text" name="modify-team-id" id="modify-team-id" value="<?= $team_id ?>">
          </div>
          <div>Nom</div>
          <div class="ui icon input" style="margin-top:0;">
            <input type="text" name="modify-team-name" id="modify-team-name" placeholder="Nom" value="<?= htmlspecialchars($team_data['name']) ?>">
          </div>
          <div style="margin-top:20px;">Scope</div>
          <div class="ui icon input" style="margin-top:0;">
            <select class="ui selection dropdown" style="font-size:small;" id="modify-team-scope" name="modify-team-scope">
              <option <?php if ($team_data['scope']=='public'){ echo 'selected'; }?> value='public'>Public</option>
              <option <?php if ($team_data['scope']=='private'){ echo 'selected'; }?> value='private'>Privé</option>
            </select>
          </div>
        </form>
      </div>
    </div>
    <div class="actions" style="position:absolute;bottom:0;width:100%;left:0;">
      <span class="error">
      </span>
      <div class="ui black deny button">
        Annuler
      </div>
      <div class="ui positive right labeled icon button">
        Enregistrer
        <i class="checkmark icon"></i>
      </div>
    </div>
  </div><!--fin modal modifier tâche -->
</div>
