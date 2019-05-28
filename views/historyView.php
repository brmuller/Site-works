<div class="ui grid">
  <div class="four wide column">
    <div style="margin-right:10px;">Equipe: </div>
    <div style="font-size:small;">
      <?php if (count($teams)>0){ ?>
        <select class="ui selection dropdown" id="team-selection-history">
          <?php if (!isset($team)){ ?>
            <option value="" disabled selected>Equipe</option>
          <?php } ?>
          <?php for ($i = 0; $i < count($teams); $i++) { ?>
            <?php if (isset($team) AND $team==$teams[$i]['id']){ ?>
              <option selected value="<?= $teams[$i]['id'] ?>"><?= $teams[$i]['name'] ?></option>
            <?php }else{ ?>
              <option value="<?= $teams[$i]['id'] ?>"><?= $teams[$i]['name'] ?></option>
            <?php } ?>
          <?php } ?>
        </select>
      <?php }else{ ?>
        <strong>Vous n'avez pas encore d'équipe</strong>
      <?php } ?>
    </div>
  </div>
  <div class="twelve wide column"> <!-- history -->
    <?php if (isset($events_list)){ ?>
      <?php if (count($events_list)>0){ ?>
      <div class="ui comments">
        <?php for ($i = 0; $i < count($events_list); $i++){ ?>
        <?php
          if ($i==0 or (date('D', strtotime($events_list[$i]['creation_date']))!=date('D', strtotime($events_list[$i-1]['creation_date'])))){
            echo '<h3 class="ui dividing header">'. $events_list[$i]['long_date'] .'</h3>';
          }
        ?>
        <div class="comment">
          <a class="avatar">
            <img src="/workflow/static/avatar/<?= $events_list[$i]['avatar'] ?>.jpg">
          </a>
          <div class="content">
            <div class="metadata">
              <span class="date"><?= date('H:i:s', strtotime($events_list[$i]['creation_date'])) ?></span>
            </div>
            <div class="text">
              <a class="author"><?= $events_list[$i]['fullname'] ?></a>
              <?= $events_list[$i]['message'] ?>
              <?php if($events_list[$i]['event_type'] != 'team_join'){
                echo '<a href="/workflow/dashboard.php?type=updatetask&id='.$events_list[$i]['affected_object_ref'].'">'.$events_list[$i]['affected_object_ref'].'</a>';
              } ?>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
    <?php }else{ ?>
      <div class="ui info message">
        <p>Il n'y a pas d'historique pour cette équipe.</p>
      </div>
    <?php } ?>
    <?php  }else{ ?>
      <div class="ui warning message">
        <div class="header">
          Aucune équipe sélectionnée.
        </div>
        Sélectionnez une équipe pour afficher l'historique.
      </div>
    <?php } ?>
  </div>
</div>
