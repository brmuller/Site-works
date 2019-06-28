
<div class="ui dimmer modals page transition visible active" style="display: block !important;" id="modify-task-dimmer">
  <div class="ui modal transition visible active" id="modal-modify-task" style="width:700px;height:600px;position:relative;margin-top:-300px;margin-left:-350px;"> <!--Modal modifier tâche -->
    <div class="header">
      <div class="ui grid">
        <div class="nine wide column">
          <div class="row">
            <div class="column" id="task-ref">Référence tâche: <?= $task_id ?></div>
          </div>
          <div class="row">
            <div class="column" id="task-update-date" style="font-weight:normal;font-size:small;">Dernière modif le  <?= $task_data['last_update'] ?></div>
          </div>
        </div>
        <div class="six wide column" style="text-align:right; font-weight:normal;font-size:small;">
          <div class="row">
            <div class="column">
              <span style="font-weight:bold;">Equipe:</span>
              <span id="task-team"> <?= htmlspecialchars($task_data['team']['name']) ?></span>
            </div>
          </div>
          <div class="row">
            <div class="column">
              <span style="font-weight:bold;">Flow:</span>
              <span id="task-flow"><?= htmlspecialchars($task_data['flow']['name']) ?></span>
            </div>
          </div>
        </div>
        <div class="one wide column">
          <div class="ui dropdown item">
            <i class="bars icon"></i>
            <div class="menu transition">
              <div class="item" id="but-task-delete">Supprimer</div>
              <div class="item">Dupliquer</div>
              <div class="item" id="but-task-close">Clôturer</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="ui stackable two column grid" style="margin:0;">
      <div class="four wide column" id="flow-steps" style="text-align:center;">
        <?php for ($i = 0; $i < count($status_list); $i++) { ?>
            <div class="row step">
              <?php if ($status_list[$i]['id']==$task_data['status']){ ?>
                <div class="step title active"> <?= htmlspecialchars($status_list[$i]['name']) ?></div>
                <div class="transition hidden"><?= htmlspecialchars($status_list[$i]['id']) ?></div>
                <span class="ui teal circular label"><?= ($i+1) ?></span>
              <?php }else{ ?>
                <div class="step title"> <?= htmlspecialchars($status_list[$i]['name']) ?></div>
                <div class="transition hidden"><?= htmlspecialchars($status_list[$i]['id']) ?></div>
                <span class="ui grey circular label clickable"> <?= ($i+1) ?></span>
              <?php } ?>
            </div>
            <?php if ($i != count($status_list)-1){ ?>
              <div class="row arrow"><i class="large arrow down icon"></i></div>
            <?php } ?>
        <?php  } ?>
      </div>
      <div class="twelve wide column">
        <div class="scrolling content" style="height:420px;overflow:auto;padding-right:10px;">
          <form id="form-modify-task" action="/workflow/dashboard/", method="POST">
            <div class="ui icon input" style="margin-top:0;display:none;">
              <input type="text" name="modify-task-id" id="modify-task-id" value="<?= $task_id ?>">
            </div>
            <div class="ui icon input" style="margin-top:0;display:none;">
              <input type="text" name="modify-task-status" id="modify-task-status" value="<?= htmlspecialchars($task_data['status']) ?>">
            </div>
            <div id="modify-rating-container" style="margin-bottom:20px;text-align:right;">
              <span>Priorité :  </span><div class="ui star rating active" data-rating="<?= $task_data['priority'] ?>" data-max-rating="3"></div>
              <div class="ui icon input" style="margin-top:0;display:none;">
                <input type="text" name="modify-task-priority" id="modify-task-priority" value="<?= $task_data['priority'] ?>">
              </div>
            </div>
            <div>Titre</div>
            <div class="ui icon input" style="margin-top:0;">
              <input type="text" name="modify-task-title" id="modify-task-title" placeholder="Titre" value="<?= htmlspecialchars($task_data['title']) ?>">
            </div>
            <div class="field ui reply form" style="margin-top:20px;">
              <span>Description</span>
              <textarea style="height:100px;width:100%;" id="modify-task-description" name="modify-task-description"><?= htmlspecialchars($task_data['description']) ?></textarea>
            </div>
            <div style="margin-top:20px;">Responsable</div>
            <div class="ui icon input" id="modify-task-assignees-list" style="margin-top:0;">
              <select class="ui selection dropdown" style="font-size:small;" id="modify-task-assignee" name="modify-task-assignee">
                <option value=""></option>
                <?php for ($i=0;$i<count($members_list);$i++){ ?>
                  <option <?php if ($members_list[$i]['id']==$task_data['assignee']){ echo 'selected'; }?> value="<?= $members_list[$i]['id'] ?>"><?= htmlspecialchars($members_list[$i]['fullname']) ?></option>
                <?php  } ?>
              </select>
            </div>
            <div style="margin-top:20px;">Date déchéance</div>
            <div class="ui left icon input" style="width:200px;margin-top:0;">
              <i class="calendar icon"></i>
              <input type="date" name="create-task-target" id="modify-task-target" value="<?= $task_data['target_date'] ?>">
            </div>
          </form>
          <div id="attachments" style="margin-top:20px;">
            <h3 class="ui dividing header">Pièces jointes</h3>
            <div class="ui icon input">
              <ul id="attachments-list">
                <?php if (count($task_uploads)>0){
                  for ($i=0;$i<count($task_uploads);$i++){ ?>
                  <li><a href="<?= $task_uploads[$i]['folder_path'] . $task_uploads[$i]['filename'] ?>"><?= $task_uploads[$i]['filename'] ?></a></li>
                <?php }} ?>
              </ul>
            </div>
            <form method="POST" enctype="multipart/form-data" id="form-attachments">
              <div class="ui icon input">
                <input type="file" name="myfile">
              </div>
              <input type="text" name="taskid" style="display: none;" value="<?= $task_id ?>">
            </form>
            <button class="small ui button" id="but-doc-send" style="margin-top:10px;">
              <i class="upload icon"></i>Ajouter
            </button>
            <span class="error" id="err-attachment"></span>
            <span class="success" id="succ-attachment"></span>
          </div>
          <div class="ui comments">
            <h3 class="ui dividing header">Commentaires</h3>
            <div id="comments-list">
              <?php for ($i = 0; $i < count($comments); $i++) { ?>
                  <div class="comment">
                    <a class="avatar"><img src="/workflow/static/avatar/<?= $comments[$i]['avatar'] ?>.jpg"></a>
                    <div class="content">
                      <a class="author"><?= htmlspecialchars($comments[$i]['fullname']) ?></a>
                      <div class="metadata"><span class="date"><?= $comments[$i]['creation_date'] ?></span></div>
                      <div class="text"><?= nl2br(htmlspecialchars($comments[$i]['text'])) ?></div>
                      <div class="actions"><a class="reply">Répondre</a></div>
                    </div>
                  </div>
              <?php  } ?>
            </div>
            <form class="ui reply form">
              <div class="field">
                <textarea style="height:100px;" id="modify-task-comment"></textarea>
              </div>
              <div class="ui blue labeled submit icon button" id="but-add-comment">
                <i class="icon edit"></i> Commenter
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="actions" style="position:absolute;bottom:0;width:100%;left:0;">
      <span class="error">
      </span>
      <div class="ui black deny button" id="modify-task-quit">
        Annuler
      </div>
      <div class="ui positive right labeled icon button">
        Enregistrer
        <i class="checkmark icon"></i>
      </div>
    </div>
  </div><!--fin modal modifier tâche -->
</div>
