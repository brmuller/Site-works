
<div class="ui dimmer modals page transition visible active" style="display: block !important;" id="create-flow-dimmer">
  <div class="ui modal transition visible active" id="modal-create-flow" style="height:600px;position:relative;margin-top:-300px;margin-left:-250px;"> <!--Modal créer Flow -->
    <div class="header">Créer un flow</div>
    <div class="scrolling content" style="height:480px;overflow:auto;">
      <form id="form-create-flow" action="/workflow/dashboard/", method="POST">
        <div class="ui input" >
          <input type="text" name="flow-name" placeholder="Nom flow" id="flow-name" >
        </div>
        <div class="ui input" >
          <input type="text" name="flow-status-list" id="flow-status-list" style="display:none;">
        </div>
        <div class="ui input">
          <?php if (count($teams)>0){ ?>
              <select name="flow-team-name" id="flow-team-name" class="ui selection dropdown" style="font-size:small;">
              <?php for ($i = 0; $i < count($teams); $i++) { ?>
                <option value="<?= $teams[$i]['id'] ?>"><?= htmlspecialchars($teams[$i]['name']) ?></option>
              <?php } ?>
              </select>
            <?php }else{ ?>
              <strong>Vous n'avez pas encore d'équipe</strong>
          <?php } ?>
        </div>
        <div class="ui horizontal divider">liste status</div>
        <div class="ui three column grid">
          <div class="row">
            <div class="column"></div>
            <div class="column" style="font-weight: bolder;">Status</div>
            <div class="column" style="font-weight: bolder;padding-left:50px;">Fin ?</div>
          </div>
        </div>
        <div class="ui three column grid" id="status-list">
        </div>
        <div class="ui three column grid">
          <div class="row"><!--add status section -->
            <div class="column"><button class="ui button" id="but-add-status">Ajouter</button></div>
            <div class="column">
              <div class="ui input" >
                <input type="text" name="status-name-1" placeholder="Nom status" id='status-name'>
              </div>
            </div>
            <div class="column" style="padding-top:7px;padding-left:50px;">
              <div class="ui checkbox">
                <input type="checkbox" name="status-last-1" id="status-last">
                 <label></label>
              </div>
            </div>
          </div>
        </div>
        <span class="error">
        </span>
      </form>
    </div>
    <div class="actions" style="position:absolute;bottom:0;width:100%;left:0;">
      <div class="ui black deny button">
        Annuler
      </div>
      <div class="ui positive right labeled icon button">
        Créer
        <i class="checkmark icon"></i>
      </div>
    </div>
  </div> <!-- fin modal créer Flow -->
</div>
