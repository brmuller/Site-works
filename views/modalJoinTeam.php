
<div class="ui dimmer modals page transition visible active" style="display: block !important;" id="join-team-dimmer">
  <div class="ui modal transition visible active" id="modal-join-team" style="margin-top:-100px;margin-left:-250px;"> <!--Modal rejoindre équipe -->
    <div class="header">Rejoindre une équipe</div>
    <div class="content">
      <form id="form-join-team" action="dashboard.php", method="POST">
        <div class="ui search">
          <div class="ui icon input">
            <input type="text" name="team-name-join" id="team-name-join" placeholder="Trouver une équipe...">
            <i class="search icon"></i>
          </div>
          <div class="results"></div>
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
        Rejoindre
        <i class="checkmark icon"></i>
      </div>
    </div>
  </div><!--fin modal rejoindre équipe -->
</div>
