 <?php
    require  'init.php';


    if(isPostRequest()){
    $article = new Article();
        if($article->generateDummyData($_POST['article_count'])) {
            redirect('/bruh/honeypot/admin.php');
        } else {

            echo "SOMETHING HAPPENED it FAILED";
        }

    }


 ?>










