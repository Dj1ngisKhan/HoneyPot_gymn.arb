<?php require_once "init.php";


if(isPostRequest()){

    $id = $_POST['id'];
    $article = new Article();
    if($article->deleteWithImage($id)){
        redirect("/bruh/honeypot/admin.php");
    } else {
        echo "Failed to deleted";
    }






}



