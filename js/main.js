$( document ).ready(function() {

  //connexion form display
  //$('#but-connect').click(function () {
  //  $('#modal-connexion').modal({
  //    onApprove : function(){return false;} //prevent modal to close when clicking Validate button
  //  }).modal('show');
  //});

  //modal close
  $('#modal-connexion').find(".deny.button").click(function () {
    $('#connexion-dimmer').modal({
    }).modal('hide');
  });


  //conexion form submit check
  $('#connexion-confirm').click(function () {
    // Get the Login Data value and trim it
    var email = $.trim($('#email-co').val());
    var password = $.trim($('#password-co').val());


    if (email  === '' || password  === '') {
      //check if fields are empty
      $('#err-username').text("Veuillez saisir votre mail et mot de passe.");

    }else {
      //check with AJAX if user if recorded in DB
      var data = {
        "action": "check_user" ,
        "email": email ,
        "password": password
      };

      var func = function(data) {
        if (data.isRecorded){
          document.forms['connexion'].submit();
        }else{
          $('#err-username').text("Email ou mot de passe incorrect.");
        }
      }

      ajaxCall(data,func);
    }
  });

  //subscription form display
  //$('#but-insc').click(function () {
  //  $('#modal-inscription').modal({
  //    onApprove : function(){return false;} //prevent modal to close when clicking Validate button
  //  }).modal('show');
  //});

  $('#modal-registration').find(".deny.button").click(function () {
    $('#registration-dimmer').modal({
    }).modal('hide');
  });

  //subscription form submit
  $('#inscription-confirm').click(function () {
    // Get the subscription Data value and trim it
    var firstname = $.trim($('#firstname').val());
    var lastname = $.trim($('#lastname').val());
    var email = $.trim($('#email').val());
    var email2 = $.trim($('#email2').val());
    var password = $.trim($('#password').val());
    var password2 = $.trim($('#password2').val());
    var avatar = $.trim($('#avatar').val());


    if (firstname  === '' || lastname  === '' || email  === '' || email2  === '' || password  === '' || password2  === '') {
      //check if fields are empty
      $('#err-inscription').text("Veuillez renseigner tous les champs.");
    }else if (email != email2) {
      $('#err-inscription').text("Les email ne correspondent pas.");
    }else if (password != password2) {
      $('#err-inscription').text("Les mots de passe ne correspondent pas.");
    }else if (avatar==='') {
      $('#err-inscription').text("Veuillez sélectionner un avatar.");
    }else {
      //check if user is recorded in the DB
      var data = {
        "action": "check_email" ,
        "email": email
      };

      var func = function(data) {
        if (data.isRecorded){
          $('#err-inscription').text("Vous possédez déjà un compte avec cet email.");
        }else{
          document.forms['inscription'].submit()
        }
      }

      ajaxCall(data,func);
    }
  });


  //avatar selection
  $('#avatar-list .card').click(function () {
    // write new status in input box
    var avatar = $(this).attr('id');
    $('#avatar').val(avatar);

    $('#avatar-list .card.active').removeClass('active');
    $(this).addClass('active');
  });

  //----------------------------------------------- Dashboard page --------------------------------------------------

  //dashboard left menu
  $( ".ui.dropdown.item" ).hover(
    function() {
      $( this ).find( ".menu" ).removeClass("hidden");
      $( this ).find( ".menu" ).addClass("visible");
    }, function() {
      $( this ).find( ".menu" ).removeClass("visible");
      $( this ).find( ".menu" ).addClass("hidden");
    }
  );



////////////////////////////////////////  FLOW   /////////////////////////////////////

  //modal close
  $('#modal-create-flow').find(".deny.button").click(function () {
    $('#create-flow-dimmer').modal({
    }).modal('hide');
  });

  var status_list = new Array();

  //ajouter status dans flow
  $('#but-add-status').click(function () {
    var status_name=$('#status-name').val();
    var status_last=$('#status-last').is(':checked');
    if (status_last){
      var status_last_txt="oui";
    }else{
      var status_last_txt="non";
    }

    var content='<div class="row">';
    content=content+'<div class="column"><a class="ui teal circular label">'+ (status_list.length+1) +'</a></div>';
    content=content+'<div class="column">'+ status_name +'</div>'
    content=content+'<div class="column" style="padding-left:50px;">'+ status_last_txt +'</div>';
    content=content+'</div>';
    $("#status-list").append(content);
    status_list.push(status_name);
    $("#flow-status-list").val(status_list.join(";"));
    return false;
  });

  //add new flow
  $('#modal-create-flow').find(".positive.button").click(function () {
    // Get the Login Data value and trim it
    var flow_name = $.trim($('#flow-name').val());
    var team_id = $.trim($('#flow-team-name').val());

    if (flow_name  === '') {
      //check if field is empty
      $('#modal-create-flow').find(".error").text("Veuillez saisir un nom de flow.");
    }else {
      var data = {
        "action": "checkflowname" ,
        "flow_name": flow_name ,
        "team_id": team_id
      };

      var func = function(data) {
        if (data.flowExists){
          $('#modal-create-flow').find(".error").text("Ce nom de flow existe déjà.");
        }else{
          document.forms['form-create-flow'].submit();
        }
      }

      ajaxCall(data,func);
    }
  });




///////////////////////////  TEAM CREATION  ///////////////////////////////////


  //modal close
  $('#modal-create-team').find(".deny.button").click(function () {
    $('#create-team-dimmer').modal({
    }).modal('hide');
  });

  //team creation form submit check
  $('#modal-create-team').find(".positive.button").click(function () {
    // Get the Login Data value and trim it
    var team_name = $.trim($('#team-name').val());
    var team_scope = $.trim($('#team-scope').val());


    if (team_name  === '') { //check if field is empty
      $('#modal-create-team').find(".error").text("Veuillez saisir un nom d'équipe.");

    }else if (team_scope===''){
      $('#modal-create-team').find(".error").text("Veuillez sélectionner la portée de votre équipe");

    }else {

      var data = {
        "action": "checkteamname" ,
        "team_name": team_name
      };

      //check if the team name already exists
      var func = function(data) { // NOTE: no "new"
        if (data.teamExists){
          $('#modal-create-team').find(".error").text("Ce nom d'équipe existe déjà. Vous pouvez la rejoindre en cliquant sur 'Rejoindre une équipe'.");
        }else{
          document.forms['form-create-team'].submit();
        }
      }

      ajaxCall(data,func);
    }
  });


  function ajaxCall(data, func){

    $.ajax({
      url : '/workflow/ajax.php',
      type : 'POST', // Le type de la requête HTTP, ici devenu POST
      data: { jsondata: JSON.stringify( data ) }, // On fait passer nos variables, exactement comme en GET, au script more_com.php
      dataType : 'json',
      success : function(response, statut){ // success est toujours en place, bien sûr !
        func(response);
      },
      error : function(resultat, statut, erreur){
        alert(resultat);
        alert(statut);
        alert(erreur);
      }
    });

  }


///////////////////////////////  TEAM JOIN  /////////////////////////////////////////


  //modal close
  $('#modal-join-team').find(".deny.button").click(function () {
    $('#join-team-dimmer').modal({
    }).modal('hide');
  });

  //team join form submit check
  $('#modal-join-team').find(".positive.button").click(function () {
    // Get the Login Data value and trim it
    var team_name = $.trim($('#team-name-join').val());

    if (team_name  === '') {//check if field is empty
      $('#modal-join-team').find(".error").text("Veuillez saisir un nom d'équipe.");

    }else {
      //check if the team scope is public
      var data = {
        "action": "check_public_team" ,
        "team_name": team_name
      };

      var func = function(data) {
        if (!data.isPublic){
          $('#modal-join-team').find(".error").text("Il n'existe pas d'équipe avec ce nom ou vous n'êtes pas autorisé à rejoindre cette équipe.");
        }else{
          //check if the user already joined the team
          var data = {
            "action": "check_user_in_team" ,
            "team_name": team_name
          };

          var func2 = function(data){
            if (data.isInTeam){
              $('#modal-join-team').find(".error").text("Vous avez déjà rejoint cette équipe.");
            }else{
              document.forms['form-join-team'].submit();
            }
          }
          ajaxCall(data,func2);
        }
      }
      ajaxCall(data,func);
    }
  });




  ///////////////////////////  TASK CREATION  ///////////////////////////////////
    //modal close
    $('#modal-create-task').find(".deny.button").click(function () {
      $('#create-task-dimmer').modal({
      }).modal('hide');
    });

    //create task team selection change
    $('#create-task-team').on('change', function() {
      var team_id=this.value;
      if (team_id!=''){

        var data = {
          "action": "get_flows_list" ,
          "team_id": team_id
        };

        //get list of flows in team
        $.ajax({
          url : '/workflow/ajax.php',
          type : 'POST', // Le type de la requête HTTP, ici devenu POST
          data: { jsondata: JSON.stringify( data ) }, // On fait passer nos variables, exactement comme en GET, au script more_com.php
          dataType : 'json',
          success : function(data_flows, statut){ // success est toujours en place, bien sûr !
            $('#create-task-flows-list').empty();
            var flows_list=data_flows.flows;
            if (flows_list.length==0){
              $('#create-task-details').removeClass("visible");
              $('#create-task-details').addClass("hidden");
              $('#modal-create-task').find(".error").text("Vous devez d'abord créer un flow pour cette équipe.");
            }else{

              var data = {
                "action": "get_team_members" ,
                "team_id": team_id
              };

              //get list of members in team
              $.ajax({
                url : '/workflow/ajax.php',
                type : 'POST', // Le type de la requête HTTP, ici devenu POST
                data: { jsondata: JSON.stringify( data ) }, // On fait passer nos variables, exactement comme en GET, au script more_com.php
                dataType : 'json',
                success : function(data_users, statut){ // success est toujours en place, bien sûr !
                  var assignees_list=data_users.users;
                  if (assignees_list.length==0){
                    $('#create-task-details').removeClass("visible");
                    $('#create-task-details').addClass("hidden");
                    $('#modal-create-task').find(".error").text("Il n'y a pas d'utilisateurs dans cette équipe.");
                  }else{

                    $('#modal-create-task').find(".error").text("");

                    //flows list
                    var flows_drop=$('<select class="ui selection dropdown" style="font-size:small;" id="create-task-flow" name="create-task-flow"></select>');
                    flows_drop.append($('<option value="" disabled selected>Flow</option>'));
                    for (var i=0;i<flows_list.length;i++){
                      var opt_flow=$('<option value="'+flows_list[i]['id']+'"></option>').text(flows_list[i]['name']);
                      flows_drop.append(opt_flow);
                    }
                    $('#create-task-flows-list').append(flows_drop);
                    selectPlaceholder('#create-task-flow');

                    //assignees list
                    var assignees_drop=$('<select class="ui selection dropdown" style="font-size:small;" id="create-task-assignee" name="create-task-assignee"></select>');
                    assignees_drop.append($('<option value="" disabled selected>Responsable</option>'));
                    assignees_drop.append($('<option value=""></option>'));
                    for (var i=0;i<assignees_list.length;i++){
                      var opt_assignee=$('<option value="'+assignees_list[i]['id']+'"></option>').text(assignees_list[i]['fullname']);
                      assignees_drop.append(opt_assignee);
                    }
                    $('#create-task-assignees-list').empty();
                    $('#create-task-assignees-list').append(assignees_drop);
                    selectPlaceholder('#create-task-assignee');


                    $('#create-task-details').removeClass("hidden");
                    $('#create-task-details').addClass("visible");
                  }
                }
              });
            }
          },
          error : function(resultat, statut, erreur){
            alert(statut);
            alert(erreur);
          }
        });
      }

    });

    //task creation form submit check
    $('#modal-create-task').find(".positive.button").click(function () {
      // Get the Login Data value and trim it
      var team = $.trim($('#create-task-team').val());
      var flow = $.trim($('#create-task-flow').val());
      var title = $.trim($('#create-task-title').val());
      var description = $.trim($('#create-task-description').val());
      var assignee = $.trim($('#create-task-description').val());
      var target = $('#create-task-target').val();


      if (team  === '') {
        //check if field is empty
        $('#modal-create-task').find(".error").text("Veuillez sélectionner une équipe.");
      }else if (flow===''){
          $('#modal-create-task').find(".error").text("Veuillez sélectionner un flow.");
      }else if (title===''){
          $('#modal-create-task').find(".error").text("Veuillez saisir un titre.");
      }else if (target===''){
          $('#modal-create-task').find(".error").text("Veuillez saisir une date d'échéance.");
      }else {
        var team_selected=$('#team-selection').value
        $('#form-create-task').append("<input type='hidden' name='team-selected' value='"+team_selected+"' />");
        document.forms['form-create-task'].submit();
      }
    });




  ///////////////////////////////////////   UPDATE TASK TABLE   //////////////////////////////////////////////

    //update tasks table after team selection change
    $('#team-selection').on('change', function() {
      var team_id=this.value;
      window.location.replace(window.location.pathname +"?type=team&id="+team_id);
    });

    function buildTeamStats(nbTasks,nbMembers){
      var stats=$('<div class="ui tiny statistics"></div>');
      var stat1=$('<div class="statistic" style="margin-bottom:0;"></div>');
      var val=$('<div class="value"></div>').text(nbTasks);
      var label=$('<div class="label"></div>').text('Tâches');

      stat1.append(val,label);

      var stat2=$('<div class="statistic" style="margin-bottom:0;"></div>');
      var val=$('<div class="value"></div>');
      val.append($('<img src="static/avatar/joe.jpg" class="ui circular inline image" style="height:25px;margin-right:5px;" />'));
      val.append(nbMembers);
      var label=$('<div class="label"></div>').text('Membres');

      stat2.append(val,label);

      stats.append(stat1,stat2);

      return stats;
    }


    function buildTaskTable(tasks){

      var nb_pages=Math.ceil(tasks["rows_count"]/8);
      var tasks_list=tasks['list'];

      var table=$('<table class="ui selectable celled table" id="task-table"><thead><tr><th>Titre</th><th>Responsable</th><th>Status</th><th>Priorité</th></tr></thead></table>');
      var tbody=$('<tbody></tbody>');
      for (var i=0; i<tasks_list.length;i++){
        var tr=$('<tr id="'+tasks_list[i]["id"]+ '"></tr>');
        var td_1=$('<td>'+ tasks_list[i]["title"]+ '</td>');
        var td_2=$('<td>'+ tasks_list[i]["fullname"]+ '</td>');
        var td_3=$('<td>'+ tasks_list[i]["status"]+ '</td>');
        var td_4=$('<td><div class="ui star rating disabled" data-rating="' +tasks_list[i]["priority"]+ '" data-max-rating="3"></div></td>');
        tr.append(td_1,td_2,td_3,td_4);
        tbody.append(tr);
      }
      table.append(tbody);

      if (nb_pages>1){
        var tfoot=$('<tfoot></tfoot>');
        var tr=$('<tr></tr>');
        var th=$('<th colspan="4"></th>');
        var div=$('<div class="ui right floated pagination menu"></div>');
        var left_arrow=$('<a class="icon item"><i class="left chevron icon"></i></a>');
        var right_arrow=$('<a class="icon item"><i class="right chevron icon"></i></a>');

        div.append(left_arrow);
        for (var i=0; i<nb_pages; i++){
          if (i==0){
            var navigation=$('<a class="item page active">'+(i+1)+'</a>');
          }else{
            var navigation=$('<a class="item page">'+(i+1)+'</a>');
          }
          div.append(navigation);
        }
        div.append(right_arrow);

        th.append(div);
        tr.append(th);
        tfoot.append(tr);
        table.append(tfoot);
      }


      return table;
    }





  ///////////////////////////////////////   TASK MODIFICATION   //////////////////////////////////////////////

    //modal close
    $('#modal-modify-task').find(".deny.button").click(function () {
      $('#modify-task-dimmer').modal({
      }).modal('hide');
    });


    //open modal when click on task list table
    $(document).on("click", "#task-table tbody tr", function() {
      var taskid=this.id;
      window.location.replace("/workflow/dashboard.php?type=updatetask&id="+taskid);
    });


    //task table pagination change
    $(document).on("click", "#task-table .item.page", function() {
      // Get the Login Data value and trim it
      var page = $(this).text();
      var team_id=$('#team-selection').val();
      var str_filter=$("#task-filter").val();

      $('#task-table').find(".item.page.active").removeClass('active');
      $(this).addClass('active');

      var data = {
        "action": "get_table_page" ,
        "team_id": team_id,
        "filter": str_filter,
        "page": page
      };

      $.ajax({
        url : '/workflow/ajax.php',
        type : 'POST', // Le type de la requête HTTP, ici devenu POST
        data: { jsondata: JSON.stringify( data ) }, // On fait passer nos variables, exactement comme en GET, au script more_com.php
        dataType : 'json',
        success : function(data, statut){ // success est toujours en place, bien sûr !
          $('#task-table').find("tbody").remove();
          var tbody=buildPageTable(data.tasks);
          tbody.insertAfter( "#task-table thead" );
          $(".ui.rating").rating();
        },
        error : function(resultat, statut, erreur){
          alert(statut);
          alert(erreur);
        }
      });
    });

    function buildPageTable(tasks){
      var tasks_list=tasks['list'];

      var tbody=$('<tbody></tbody>');
      //var tbody=$('<tbody></tbody>');
      for (var i=0;i<tasks_list.length;i++) {
        var tr=$('<tr id="'+tasks_list[i]['id']+'"></tr>');
        var td1=$('<td>'+tasks_list[i]['title']+'</td>');
        var td2=$('<td>'+tasks_list[i]['fullname']+'</td>');
        var td3=$('<td>'+tasks_list[i]['status']+'</td>');
        var td4=$('<td><div class="ui star rating disabled" data-rating="'+tasks_list[i]['priority']+'" data-max-rating="3"></div></td>');
        tr.append(td1,td2,td3,td4);
        tbody.append(tr);
      }

      return tbody;
    }


    //attach documents to task
    $('#but-doc-send').click(function () {
      var form = $('#form-attachments')[0];
	    var formData = new FormData(form);
      $.ajax({
        url : '/workflow/ajax.php',
        type : 'POST', // Le type de la requête HTTP, ici devenu POST
        data : formData, // On fait passer nos variables, exactement comme en GET, au script more_com.php
        dataType : 'json',
        contentType: false,
        cache: false,
        processData:false,
        success : function(data, statut){ // success est toujours en place, bien sûr !
          if (data.file['error']==""){
            $('#err-attachment').empty();
            var filename = data.file['file'].split('/').pop();
            var new_file=$('<li><a href="'+ data.file['file'] +'">' + filename + '</a></li>');
            $('#attachments-list').append(new_file);
          }else{
            $('#err-attachment').text(data.file['error']);
          }
        },
        error : function(resultat, statut, erreur){
          alert(resultat);
          alert(statut);
          alert(erreur);
        }
      });
    });


    //change status
    $('#flow-steps .clickable').click(function () {
      // write new status in input box
      var new_status=$(this).prev().text().trim();
      $('#modify-task-status').val(new_status);

      $('#flow-steps .active').next().removeClass('teal');
      $('#flow-steps .active').next().addClass('grey');
      $('#flow-steps .active').next().addClass('clickable');

      $('#flow-steps .active').removeClass('active');

      $(this).removeClass('grey');
      $(this).addClass('teal');
      $(this).removeClass('clickable');
      $(this).prev().addClass('active');
    });


    //task modification form submit check
    $('#modal-modify-task').find(".positive.button").click(function () {
      // Get the Login Data value and trim it
      var title = $.trim($('#modify-task-title').val());
      var target = $('#modify-task-target').val();

      if (title===''){
          $('#modal-modify-task').find(".error").text("Veuillez saisir un titre.");
      }else if (target===''){
          $('#modal-modify-task').find(".error").text("Veuillez saisir une date d'échéance.");
      }else {
        document.forms['form-modify-task'].submit();
      }
    });


    //task delete
    $('#but-task-delete').click(function () {
      var task_id=$('#modify-task-id').val();

      var data = {
        "action": "delete_task",
        "task_id": task_id
      };

      $.ajax({
        url : '/workflow/ajax.php',
        type : 'POST', // Le type de la requête HTTP, ici devenu POST
        data: { jsondata: JSON.stringify( data ) }, // On fait passer nos variables, exactement comme en GET, au script more_com.php
        dataType : 'json',
        success : function(data, statut){ // success est toujours en place, bien sûr !
          if (data.isDeleted){
            window.location.replace("/workflow/dashboard");
          }
        },
        error : function(resultat, statut, erreur){
          alert(statut);
          alert(erreur);
        }
      });

    });


    //task close
    $('#but-task-close').click(function () {
      var task_id=$('#modify-task-id').val();

      var data = {
        "action": "close_task",
        "task_id": task_id
      };

      $.ajax({
        url : '/workflow/ajax.php',
        type : 'POST', // Le type de la requête HTTP, ici devenu POST
        data: { jsondata: JSON.stringify( data ) }, // On fait passer nos variables, exactement comme en GET, au script more_com.php
        dataType : 'json',
        success : function(data, statut){ // success est toujours en place, bien sûr !
          if (data.isClosed){
            window.location.replace("/workflow/dashboard");
          }
        },
        error : function(resultat, statut, erreur){
          alert(statut);
          alert(erreur);
        }
      });

    });


    //add a comment
    $('#but-add-comment').click(function () {
	    var comment = $('#modify-task-comment').val();
      var task_id=$('#modify-task-id').val();

      var data = {
        "action": "add_comment",
        "task_id": task_id,
        "comment": comment
      };

      $.ajax({
        url : '/workflow/ajax.php',
        type : 'POST', // Le type de la requête HTTP, ici devenu POST
        data: { jsondata: JSON.stringify( data ) }, // On fait passer nos variables, exactement comme en GET, au script more_com.php
        dataType : 'json',
        success : function(data, statut){ // success est toujours en place, bien sûr !
          if (data.isAdded){
            var data = {
              "action": "get_user_data"
            };

            $.ajax({
              url : '/workflow/ajax.php',
              type : 'POST', // Le type de la requête HTTP, ici devenu POST
              data: { jsondata: JSON.stringify( data ) }, // On fait passer nos variables, exactement comme en GET, au script more_com.php
              dataType : 'json',
              success : function(data_user, statut){ // success est toujours en place, bien sûr !
                var container=buildComment(comment,data_user.user['fullname'],data_user.user['avatar'])
                $('#comments-list').append(container);
              },
              error : function(resultat, statut, erreur){
                alert(statut);
                alert(erreur);
              }
            });
          }
        },
        error : function(resultat, statut, erreur){
          alert(statut);
          alert(erreur);
        }
      });

      $('#modify-task-comment').val('');
    });

    function buildComment(comment,fullname,avatar){
      var creation_date =moment().format('YYYY-MM-DD hh:mm:ss');
      var container=$('<div class="comment"></div>');
      var avatar =$('<a class="avatar"><img src="/workflow/static/avatar/'+avatar+'.jpg"></a>');
      var content=$('<div class="content"></div>');
      var author=$('<a class="author">'+fullname+'</a>');
      var c_date=$('<div class="metadata"><span class="date">'+creation_date+'</span></div>');
      var text=$('<div class="text"></div>').text(nl2br(comment,false));
      var resp=$('<div class="actions"><a class="reply">Répondre</a></div>');

      content.append(author,c_date,text,resp);
      container.append(avatar,content);

      return container;

    }


  //////////////////////////////////////////////// OTHER //////////////////////////////////

    //adjust modals center
    //$('.ui.modal').each(function(){ //loop through each element with the .dynamic-height class
    //    $(this).css({
    //        'margin-left' : '-'+ ($(this).outerWidth() / 2) + 'px',
    //        'margin-top' : '-'+ ($(this).outerHeight() / 2) + 'px'
    //    });
    //});

    function nl2br (str, is_xhtml) {
      var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
      return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
    }

    //placeholder for dropdown lists
    function selectPlaceholder(selectID){
      var selected = $(selectID + ' option:selected');
      var val = selected.val();
      $(selectID + ' option' ).css('color', '#333');
      selected.css('color', '#C8C8C8');
      if (val == "") {
        $(selectID).css('color', '#C8C8C8');
      };
      $(selectID).change(function(){
        var val = $(selectID + ' option:selected' ).val();
        if (val == "") {
          $(selectID).css('color', '#C8C8C8');
        }else{
          $(selectID).css('color', '#333');
        };
      });
    };

    selectPlaceholder('#create-task-team');
    selectPlaceholder('#team-selection');

    function formatDate(strDate){
      var d=new Date(strDate);
      var day=d.getDate();
      var month=d.getMonth()+1;
      var year=d.getFullYear();
      //var h=d.getHours();
      //var m=d.getMinutes();
      if (day<10){
        day="0"+day;
      }
      if (month<10){
        month="0"+month;
      }
      var newDate=day+"/"+month+"/"+year;
      return newDate;
    };


    //search bar
    $("#task-filter").keyup(function(){
      var team_id=$("#team-selection").val();
      var str_filter=$("#task-filter").val();

        var data = {
          "action": "get_tasks_list" ,
          "team_id": team_id,
          "filter": str_filter
        };

        $.ajax({
          url : '/workflow/ajax.php',
          type : 'POST', // Le type de la requête HTTP, ici devenu POST
          data: { jsondata: JSON.stringify( data ) }, // On fait passer nos variables, exactement comme en GET, au script more_com.php
          dataType : 'json',
          success : function(data, statut){ // success est toujours en place, bien sûr !
            var table=buildTaskTable(data.tasks);
            $('#task-table-container').empty();
            $('#task-table-container').append(table);
            $(".ui.rating").rating();
          },
          error : function(resultat, statut, erreur){
            alert(statut);
            alert(erreur);
          }
        });

    });

    //$(document).find(".ui.rating").rating();
    //initialize rating
    $(".ui.rating").rating();
    //$('.ui.rating.disabled').rating('setting', 'interactive', false);


    $('#create-rating-container .ui.rating.active')
      .rating('setting', 'onRate', function(value) {
        $("#create-task-priority").val(value);
    });

    $('#modify-rating-container .ui.rating.active')
      .rating('setting', 'onRate', function(value) {
        $("#modify-task-priority").val(value);
    });


});
