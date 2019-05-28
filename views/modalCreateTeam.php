
<div class="ui dimmer modals page transition visible active" style="display: block !important;" id="create-team-dimmer">
  <div class="ui modal transition visible active" id="modal-create-team" style="margin-top:-150px;margin-left:-250px;"> <!--Modal créer équipe -->
    <div class="header">Créer une équipe</div>
    <div class="content">
      <form id="form-create-team" action="/workflow/dashboard/", method="POST">
        <div class="ui input" >
          <input type="text" name="team-name" placeholder="Nom" id="team-name" >
        </div>
        <div class="ui input" >
          <select name="team-scope" id="team-scope" class="ui selection dropdown" style="font-size:small;">';
            <option value='public'>Public</option>
            <option value='private'>Privé</option>
          </select>
          <div style="margin-left: 10px;">
            <i class="info circle icon tooltip">
              <!--<span class="tooltiptext">Public: l'équipe sera visible de tous</br>Privé: vous seul pourrez ajouter des membres à votre équipe</span>-->
            </i>
            <span>Privé: vous seul pouvez ajouter des membres à votre équipe.</span>
          </div>
        </div>
        <span class="error">
        </span>
      </form>
    </div>
    <div class="actions">
      <div class="ui black deny button">
        Annuler
      </div>
      <div class="ui positive right labeled icon button">
        Créer
        <i class="checkmark icon"></i>
      </div>
    </div>
  </div><!-- fin modal créer équipe -->
</div>
