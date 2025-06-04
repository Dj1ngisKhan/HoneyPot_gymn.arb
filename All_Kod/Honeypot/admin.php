<?php
include "partials/admin/header.php";
include "partials/admin/navbar.php";

$user_id = 1;


if(!(isset($_SESSION["logged_in_pot"]))){
    header("Location: /bruh/website/login.php");
}

$conn = new PDO("mysql:host=localhost;dbname=honeypot", "root", "");
$query = "SELECT * FROM users ORDER BY date";

$stmt = $conn->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_OBJ);


// SMS Skickas till mig - jävla skit krångel.....!

// require "/bruh/vendor/autoloader.php";


?>


<main class="container my-5">
    <?php if(isset($_SESSION["username"])): ?>
        <h2 class="mb-4">Välkommen <?php echo $_SESSION['username']  ?> till din Admin Dashboard</h2>
    <?php endif; ?>


    <div class="d-flex justify-content-between align-items-center mb-4">

        <form class="d-flex align-items-center" action="<?php echo '/bruh/honeypot/create-dummy-articles.php'; ?>" method="post">
            <label class="form-label me-2" for="articleCount">Antal användare</label>
            <input id="articleCount" min="1" style="width: 100px" class="form-control me-2" name="article_count" type="number">
            <button id="articleCount" class="btn btn-primary" type="submit">Generera användare</button>
        </form>

        <button id="deleteSelectedBtn" class="btn btn-danger">Radera valda användare</button>

    </div>


    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>ID</th>
                <th>Användarnamn</th>
                <th>Email</th>
                <th>Lösenord</th>
                <th>Datum</th>
                <th>Redigera</th>
                <th>Radera</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($users)): ?>
            <?php foreach ($users as $articleItem): ?>



            <tr>
                <td><input type="checkbox" class="articleCheckbox" value="<?php echo $articleItem->id;?>"></td>
                <td><?php echo $articleItem->id; ?></td>
                <td><?php echo $articleItem->username; ?></td>
                <td><?php echo $articleItem->email ?></td>
                <td><?php echo $articleItem->password; ?></td>
                <td><?php echo $articleItem->date; ?></td>
                <td>
                    <a href="edit-article.php?id=<?php echo $articleItem->id; ?>" class="btn btn-sm btn-primary me-1">Redigera</a>
                </td>

                <td>
                    <form onsubmit="confirmDelete(<?php echo $articleItem->id; ?>)" method="POST" action="<?php echo "/bruh/honeypot/delete_article.php"?>">
                        <input name="id" value="<?php echo $articleItem->id; ?>" type="hidden">
                        <!-- <button class="btn btn-sm btn-danger" onclick="confirmDelete(1)">Delete</button> -->
                        <button class="btn btn-sm btn-danger">Radera</button>
                    </form>
                </td>

            </tr>

            <?php endforeach; ?>
            <?php endif; ?>

            </tbody>
        </table>
    </div>
</main>

<script>


    document.getElementById('selectAll').onclick = function() {
        let checkboxes = document.querySelectorAll('.articleCheckbox');
        for (let checkbox of checkboxes){
            checkbox.checked = this.checked;
        }
    };

    document.getElementById('deleteSelectedBtn').onclick = function(){
        let selectedIds = [];
        let checkboxes = document.querySelectorAll('.articleCheckbox:checked');
        checkboxes.forEach((checkbox) => {
            selectedIds.push(checkbox.value)
        });

       if(selectedIds.length === 0){
           alert("HEY SELECT 1 AT LEAST");
           return;
       }

       if(confirm("Are you sure you want to delete this article")){
           sendDeleteRequest(selectedIds)
       }

    }

    document.querySelectorAll('.delete-single').forEach((button) =>{

        button.onclick = function(){
           let articleId = this.getAttribute('data-id');
            if(confirm("Are you sure you want to delete this article " + articleId + ' ?')){
                sendDeleteRequest([articleId])
            }

       }

   })

    function sendDeleteRequest(articleIds){
        let xhr = new XMLHttpRequest();
        xhr.open('POST', "<?php echo base_url('delete_articles.php') ?>", true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function() {
            if(xhr.readyState === 4 && xhr.status === 200){
                let response = JSON.parse(xhr.responseText);
                if(response.success){
                    alert("WE DID IT and article got deleted");
                    location.reload();
                } else {
                    alert('FAILED TO DELETE: ' + response.message)
                }
            }
        };
        xhr.send(JSON.stringify({ article_ids : articleIds}))
    }

</script>



<?php include "partials/admin/footer.php"; ?>
