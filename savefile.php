<?php
// Testons si le fichier a bien été envoyé et s'il n'y a pas d'erreur
$err="";
$file="";

if (isset($_FILES['myfile']) AND isset($_POST['taskid']) AND $_FILES['myfile']['error'] == 0)
{
  $taskid=$_POST['taskid'];
  // Testons si le fichier n'est pas trop gros
  if ($_FILES['myfile']['size'] <= 1000000)
  {
    // On peut valider le fichier et le stocker définitivement
    if (!is_dir('C:/wamp64/www/workflow/uploads/'.$taskid.'/')) {
        mkdir('C:/wamp64/www/workflow/uploads/'.$taskid.'/', 0777, true);
    }
    $file='C:/wamp64/www/workflow/uploads/'.$taskid.'/'. basename($_FILES['myfile']['name']);
    move_uploaded_file($_FILES['myfile']['tmp_name'], $file);

  }else{
    $err="fichier trop volumineux";
  }
}else{
  $err="erreur lors de l'envoi du fichier";
}

$response_json=array(
  'error'=>$err,
  'file'=>$file
);
echo json_encode($response_json);

?>
