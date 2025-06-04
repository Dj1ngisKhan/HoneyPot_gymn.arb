<?php
include "partials/admin/header.php";
include "partials/admin/navbar.php";

$articleId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$article = new Article();
$articleData = $article->getArticleById($articleId);

if(isPostRequest()){

    $username = $_POST['username'];
    $password = $_POST["password"];
    $password_h = password_hash($password, PASSWORD_DEFAULT);
    $author_id = $articleId;
    $created_at = $_POST['date'];
    $mail = $_POST["mail"];

    if($article->update($articleId, $username, $password_h, $author_id, $created_at, $mail)){
        redirect("admin.php");
        exit;
    } else {
        echo "FAILED CREATING ARTICLE";
    }

    



}



?>


<!-- Main Content -->
<main class="container my-5">
    <h2>Updatera användare</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Användarnamn *</label>
            <input value="<?php echo $articleData->username; ?>" name="username" type="text" class="form-control" id="title" placeholder="Enter article title" required>
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Email *</label>
            <input value="<?php echo $articleData->email; ?>" name="mail" type="text" class="form-control" id="title" placeholder="Enter article title" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Datum *</label>
            <input
                    value="<?php echo htmlspecialchars(date('Y-m-d', strtotime($articleData->date))); ?>"
                    name="date"
                    type="date"
                    class="form-control"
                    id="date"
                    required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Lösenord (hashed) *</label>
            <textarea name="password" class="form-control" id="content" rows="1" placeholder="Enter article content" required><?php echo $articleData->password; ?></textarea>
        </div>

        <?php if(!empty($articleData->image)): ?>
        <div class="mb-3">
            <label for="image" class="form-label">Current Featured Image</label><br>
            <img class="img-fluid mb-2" style="width: 100px" src="<?php echo htmlspecialchars($articleData->image) ?>" alt="">
        </div>

        <?php endif; ?>

        <button type="submit" class="btn btn-success">Updatera användare</button>
        <a href="admin.php" class="btn btn-secondary ms-2">Avbryt</a>
    </form>
</main>

<?php include "partials/admin/footer.php"; ?>
