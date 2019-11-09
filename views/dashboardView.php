<div class="ui stackable two column grid">
  <div class="four wide column medium-screen-hide"> <!-- Menu -->
    <div class="ui vertical menu">
      <div class="item">
        <div class="header">
          Tâches
          <div class="ui teal left pointing label" style="margin-left: 8em;"><?= $my_tasks ?></div>
        </div>
        <div class="menu">
          <a href="/workflow/dashboard/newtask" class="item"><i class="plus icon"></i>Nouvelle tâche</a>
        </div>
      </div>
      <div class="item">
        <div class="menu">
          <div class="ui dropdown item">
            <span class="menu-header">Equipes</span>
            <i class="dropdown icon"></i>
            <div class="menu transition hidden">
              <a href="/workflow/dashboard/newteam" class="item" id="but-create-team"><i class="edit icon"></i> Créer une équipe</a>
              <a href="/workflow/dashboard/jointeam" class="item" id="but-join-team"><i class="users icon"></i> Rejoindre une équipe</a>
            </div>
          </div>
          <?php if (count($teams)>0){ ?>
            <?php if (count($teams)>MAX_TEAM_ROWS){ ?>
              <?php for ($i = 0; $i < MAX_TEAM_ROWS; $i++){ ?>
                <a href="/workflow/dashboard/updateteam/<?= $teams[$i]['id'] ?>" class="item"><?= htmlspecialchars($teams[$i]['name']) ?></a>
              <?php } ?>
            <?php }else{ ?>
              <?php for ($i = 0; $i < count($teams); $i++){ ?>
                <a href="/workflow/dashboard/updateteam/<?= $teams[$i]['id'] ?>" class="item"><?= htmlspecialchars($teams[$i]['name']) ?></a>
              <?php } ?>
            <?php } ?>
            <?php if (count($teams)>MAX_TEAM_ROWS){ ?>
              <div class="ui dropdown item">
                <span style="font-weight:bold;color:darkgrey;">Plus</span>
                <i class="dropdown icon"></i>
                <div class="menu transition hidden">
                  <?php for ($i = MAX_TEAM_ROWS; $i < count($teams); $i++){ ?>
                    <a href="/workflow/dashboard/updateteam/<?= $teams[$i]['id'] ?>" class="item"><?= htmlspecialchars($teams[$i]['name']) ?></a>
                  <?php } ?>
                </div>
              </div>
            <?php } ?>
          <?php  } ?>
        </div>
      </div>
      <div class="item">
        <div class="menu">
          <div class="ui dropdown item">
            <span class="menu-header">Flows</span>
            <i class="dropdown icon"></i>
            <div class="menu transition hidden">
              <a href="/workflow/dashboard/newflow" class="item" id="but-create-flow"><i class="edit icon"></i> Créer un flow</a>
            </div>
          </div>
          <?php if (count($flows)>0){ ?>
            <?php if (count($flows)>MAX_FLOW_ROWS){ ?>
              <?php for ($i = 0; $i < MAX_FLOW_ROWS; $i++){ ?>
                <a href="/workflow/dashboard/updateflow/<?= $flows[$i]['id'] ?>" class="item"><?= htmlspecialchars($flows[$i]['name']) ?></a>
              <?php } ?>
            <?php }else{ ?>
              <?php for ($i = 0; $i < count($flows); $i++){ ?>
                <a href="/workflow/dashboard/updateflow/<?= $flows[$i]['id'] ?>" class="item"><?= htmlspecialchars($flows[$i]['name']) ?></a>
              <?php } ?>
            <?php } ?>
            <?php if (count($flows)>MAX_FLOW_ROWS){ ?>
              <div class="ui dropdown item">
                <span style="font-weight:bold;color:darkgrey;">Plus</span>
                <i class="dropdown icon"></i>
                <div class="menu transition hidden">
                  <?php for ($i = MAX_FLOW_ROWS; $i < count($flows); $i++){ ?>
                    <a href="/workflow/dashboard/updateflow/<?= $flows[$i]['id'] ?>" class="item"><?= htmlspecialchars($flows[$i]['name']) ?></a>
                  <?php } ?>
                </div>
              </div>
            <?php } ?>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
  <div class="twelve wide column" id="dashboard"> <!-- Dashboard -->
    <div class="row">
      <div id="tasks-header" style="margin-bottom: 10px;">
        <div class="ui stackable two column grid" style="margin: auto;">
          <div class="eight wide column" style="position:relative;padding-bottom:0;">
            <div id="tasks-header-elt" style="position: absolute;bottom: 0;left: 0;">
              <span style="margin-right:10px;">Equipe: </span>
              <span style="font-size:small;">
                <?php if (count($teams)>0){ ?>
                  <select class="ui selection dropdown" id="team-selection">
                    <?php if (!isset($team)){ ?>
                      <option value="" disabled selected>Equipe</option>
                    <?php } ?>
                    <?php for ($i = 0; $i < count($teams); $i++) { ?>
                      <?php if (isset($team) AND $team==$teams[$i]['id']){ ?>
                        <option selected value="<?= $teams[$i]['id'] ?>"><?= htmlspecialchars($teams[$i]['name']) ?></option>
                      <?php }else{ ?>
                        <option value="<?= $teams[$i]['id'] ?>"><?= htmlspecialchars($teams[$i]['name']) ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                <?php }else{ ?>
                  <strong>Vous n'avez pas encore d'équipe</strong>
                <?php } ?>
              </span>
            </div>
          </div>
          <div class="eight wide column" style="position:relative;padding-bottom:0;">
            <div class="ui icon input">
              <input class="prompt" type="text" placeholder="Filtrer les tâches..." id="task-filter">
              <i class="search icon"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php if (isset($tasks_list)){ ?>
    <div class="ui stackable two column grid"  id="team-stats" style="margin-top:10px;">
      <div class="twelve wide column">
        <h3 class="ui dividing header">
          Tâches
          <?php if ($nb_rows>0){ ?>
          <a href="/workflow/dashboard/exporttasks/<?= htmlspecialchars($team) ?>" class="item">
            <i class="file excel outline icon"></i>
          </a>
          <?php } ?>
        </h3>
      </div>
      <div class="four wide column">
        <div class="ui tiny statistics" style="float:right;">
          <div class="statistic" style="margin-bottom:0;">
            <div class="value">
              <?= $tasks['rows_count'] ?>
            </div>
            <div class="label">
              Tâches
            </div>
          </div>
          <div class="statistic" style="margin-bottom:0;">
            <div class="value">
              <img src="/workflow/static/avatar/joe.jpg" class="ui circular inline image" style="height:25px;">
              <?= $members_count ?>
            </div>
            <div class="label">
              Membres
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row" id="task-table-container">
    <?php if ($nb_rows>0){ ?>
      <table class="ui small selectable celled table" id="task-table">
        <thead class="mobile-screen-hide">
          <tr>
            <th>Titre</th>
            <th>Responsable</th>
            <th>Status</th>
            <th>Priorité</th>
          </tr>
        </thead>
        <tbody>
          <?php for ($i=0;$i<$nb_rows;$i++){ ?>
            <tr id="<?= $tasks_list[$i]['id'] ?>">
              <td><?= htmlspecialchars($tasks_list[$i]['title']) ?></td>
              <td><?= htmlspecialchars($tasks_list[$i]['fullname']) ?></td>
              <td><?= htmlspecialchars($tasks_list[$i]['status']) ?></td>
              <td><div class="ui star rating disabled" data-rating="<?= $tasks_list[$i]['priority'] ?>" data-max-rating="3"></div></td>
            </tr>
          <?php } ?>
        </tbody>
        <?php if ($nb_pages>1){ ?>
        <tfoot>
          <tr>
            <th colspan="4">
              <div class="ui right floated pagination menu">
                <a class="icon item">
                  <i class="left chevron icon"></i>
                </a>
              <?php for($i=0;$i<$nb_pages;$i++){ ?>
                <a class="item page <?php if ($i==0){echo 'active';} ?>"><?= ($i+1) ?></a>
              <?php } ?>
                <a class="icon item">
                  <i class="right chevron icon"></i>
                </a>
              </div>
            </th>
          </tr>
        </tfoot>
        <?php } ?>
      </table>
    <?php }else{ ?>
      <div class="ui info message">
        <p>Il n'y a pas de tâches pour cette équipe.</p>
      </div>
    <?php } ?>
    </div>
    <div class="ui stackable two column grid" style="margin-top:10px;">
      <div class="ten wide column">
        <h3 class="ui dividing header">Historique</h3>
        <?php if (isset($events_list)){ ?>
        <div class="ui comments">
          <?php for ($i = 0; $i < count($events_list); $i++){ ?>
          <div class="comment">
            <a class="avatar">
              <img src="/workflow/static/avatar/<?= $events_list[$i]['avatar'] ?>.jpg">
            </a>
            <div class="content">
              <div class="metadata">
                <span class="date"><?= $events_list[$i]['creation_date'] ?></span>
              </div>
              <div class="text">
                <a href="/workflow/members/<?= htmlspecialchars($events_list[$i]['username']) ?>" class="author"><?= htmlspecialchars($events_list[$i]['fullname']) ?></a>
                <?= htmlspecialchars($events_list[$i]['message']) ?>
                <?php if($events_list[$i]['event_type'] != 'team_join'){
                  echo '<a href="/workflow/dashboard/updatetask/'.$events_list[$i]['affected_object_ref'].'">'.$events_list[$i]['affected_object_ref'].'</a>';
                } ?>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
        <?php if($nb_rows>MAX_HISTORY_ROWS){
          echo '<a href="/workflow/history">Plus ></a>';
        } ?>
        <?php } ?>
      </div>
      <div class="six wide column">
        <h3 class="ui dividing header" style="margin-top:0;">Quick Stats</h3>
        <?php if ($nb_rows>0){ ?>
        <canvas id="myChart" width="200" height="200"></canvas>
        <?php } ?>
      </div>
    </div>
    <?php }else{ ?>
      <div class="ui warning message">
        <div class="header">
          Aucune équipe sélectionnée.
        </div>
        Sélectionnez une équipe pour afficher la liste des tâches.
      </div>
    <?php } ?>
  </div>
</div>
