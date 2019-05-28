
<div class="ui dimmer modals page transition visible active" style="display: block !important;" id="create-task-dimmer">
  <div class="ui modal transition visible active" id="modal-create-task" style="width:600px;height:600px;position:relative;margin-top:-300px;margin-left:-300px;"> <!--Modal créer tâche -->
    <div class="header">Nouvelle tâche</div>
    <div class="scrolling content" style="height:480px;overflow:auto;">
      <form id="form-create-task" action="/workflow/dashboard/", method="POST">
        <?php if (count($teams)>0){ ?>
            <div class="ui icon input">
              <select class="ui selection dropdown" style="font-size:small;" id="create-task-team" name="create-task-team">
                <option value="" disabled selected>Equipe</option>
                <?php for ($i = 0; $i < count($teams); $i++){ ?>
                  <option value="<?= $teams[$i]['id'] ?>"><?= $teams[$i]['name'] ?></option>
                <?php } ?>
              </select>
            </div>
        <?php }else{ ?>
            <strong>Vous n'avez pas encore d'équipe</strong>
        <?php } ?>
        <div class="ui icon input" id="create-task-flows-list">
        </div>
        <div class="transition hidden" id="create-task-details" style="margin-top:20px;">
          <div class="ui icon input">
            <input type="text" name="create-task-title" id="create-task-title" placeholder="Titre">
          </div>
          <div id="create-rating-container" style="margin-top:20px;text-align:left;">
            <span>Priorité :  </span><div class="ui star rating active" data-rating="0" data-max-rating="3"></div>
            <div class="ui icon input" style="margin-top:0;display:none;">
              <input type="text" name="create-task-priority" id="create-task-priority" value="0">
            </div>
          </div>
          <div class="field" style="margin-top:20px;">
            <span>Description</span>
            <textarea style="height:100px;width:100%;" id="create-task-description" name="create-task-description"></textarea>
          </div>
          <div class="ui icon input" id="create-task-assignees-list">
          </div>
          <div style="margin-top:20px;">Date d'échéance</div>
          <div class="ui left icon input" style="margin-top:0;width:200px;">
            <i class="calendar icon"></i>
            <input type="date" name="create-task-target" id="create-task-target">
          </div>
        </div>
      </form>
    </div>
    <div class="actions" style="position:absolute;bottom:0;width:100%;left:0;">
      <span class="error">
      </span>
      <div class="ui black deny button">
        Annuler
      </div>
      <div class="ui positive right labeled icon button">
        Créer
        <i class="checkmark icon"></i>
      </div>
    </div>
  </div><!--fin modal créer tâche -->
</div>
