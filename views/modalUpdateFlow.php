
<div class="ui dimmer modals page transition visible active" style="display: block !important;" id="modify-flow-dimmer">
  <div class="ui modal transition visible active" id="modal-modify-flow" style="width:700px;height:370px;position:relative;margin-top:-200px;margin-left:-350px;"> <!--Modal modifier equipe -->
    <div class="header">
      <div class="ui grid">
        <div class="nine wide column">
          <div class="row">
            <div class="column">Equipe: <?= htmlspecialchars($flow_data['team']) ?></div>
          </div>
          <div class="row">
            <div class="column" style="font-weight:normal;font-size:small;">Créateur du flow:  <?= htmlspecialchars($flow_data['creator']) ?></div>
          </div>
        </div>
      </div>
    </div>
    <form id="form-modify-flow" action="/workflow/dashboard/", method="POST">
      <div class="ui grid" style="margin:0;">
        <div class="ten wide column">
          <h3 class="ui dividing header">Status</h3>
          <div class="scrolling content" style="height:160px;overflow:auto;">
            <ol class="ui list">
            <?php for ($i = 0; $i < count($flow_data['status']); $i++) { ?>
              <li>
                <div class="ui icon input" style="margin-top:0;">
                  <input type="text" name="status-<?= $flow_data['status'][$i]['id'] ?>" value="<?= $flow_data['status'][$i]['name'] ?>">
                </div>
              </li>
            <?php  } ?>
            </ol>
          </div>
        </div>
        <div class="six wide column">
          <div class="ui icon input" style="margin-top:0;display:none;">
            <input type="text" name="modify-flow-id" value="<?= $flow_id ?>">
          </div>
          <div class="ui icon input" style="margin-top:0;display:none;">
            <input type="text" name="modify-flow-team-id" value="<?= htmlspecialchars($flow_data['team_id']) ?>">
          </div>
          <div>Nom</div>
          <div class="ui icon input" style="margin-top:0;">
            <input type="text" id="modify-flow-name" name="modify-flow-name" placeholder="Nom" value="<?= htmlspecialchars($flow_data['name']) ?>">
          </div>
        </div>
      </div>
    </form>
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
  </div>
</div>
