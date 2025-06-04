<?php

use function PHPSTORM_META\map;

class Article {

    private $conn;
    private $table = 'users';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getExcerpt($content, $length = 100)
    {
        if(strlen($content) > $length){
            return substr($content, 0, $length) . "...";
        }
        return $content;
    }


    public function get_all()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    public function getArticleById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $article  = $stmt->fetch(PDO::FETCH_OBJ);

        if($article){
            return $article;
         } else {
            return false;
        }

    }

    public function deleteWithImage($id)
    {

        $article =  $this->getArticleById($id);


        if($article){

            // Kollar ownership
            if($article->user_id == $_SESSION['user_id']) {

                if(!empty($article->image) && file_exists($article->image)){
                    if(!unlink($article->image)){
                        return false;
                    }
                }
                $query = "DELETE FROM " . $this->table . " WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                return $stmt->execute();

            } else {
                redirect("admin.php");
            }

                }




        return false;
    }



    public function getArticleWithOwnerByID($id)
    {
        $query = "SELECT
                    articles.id,
                    articles.title,
                    articles.content,
                    articles.image,
                    articles.created_at,
                    users.username AS author,
                    users.email AS author_email
                  FROM " . $this->table . "
                  JOIN users ON articles.user_id = users.id
                  WHERE articles.id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $article  = $stmt->fetch(PDO::FETCH_OBJ);

        if($article){
            return $article;
        } else {
            return false;
        }

    }


    public function getArticlesByUser($userId)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);

    }




    public function formatCreatedAt($date)
    {
        return date('F j, Y', strtotime($date));
    }


    public function create($title, $content, $author_id, $created_at, $image)
    {
        $query = "INSERT INTO " . $this->table . " (title, content, user_id, created_at, image)
                  VALUES (:title, :content, :user_id, :created_at, :image)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':user_id', $author_id);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->bindParam(':image', $image);
        return $stmt->execute();

    }

    public function uploadImage($file)
    {
        $targetDir = 'uploads/';

        if(!is_dir($targetDir)){
            mkdir($targetDir, 0755, true);
        }

        if(isset($file) && $file['error'] === 0){

            $targetFile = $targetDir . basename($file['name']);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if(in_array( $imageFileType, $allowedTypes)) {
                $uniqueFileName = uniqid() . "_" . time() . "." . $imageFileType;
                $targetFile = $targetFile . "_" . $uniqueFileName;
                if(move_uploaded_file($file['tmp_name'], $targetFile)){
                    return $targetFile;
                } else {
                    return "There was an error uploading the file";
                }

            } else {
                return "Only JPG, JPEG, PNG, and GIF files are allowed";
            }

        }

        return '';

    }


    public function update($id, $username, $password, $author_id, $created_at, $mail, $imagePath = null)
    {

            $query = "UPDATE " . $this->table . " 
                      SET username = :title, 
                      password = :content, 
                      email = :mail,
                      date = :created_at";

            if($imagePath){
                $query .= ", image = :image";
            }

                $query .= " WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $username);
            $stmt->bindParam(':content', $password);
            $stmt->bindParam(":mail", $mail);
            $stmt->bindParam(':created_at', $created_at);

            if($imagePath){
                $stmt->bindParam(':image', $imagePath, PDO::PARAM_STR);
            }
            return $stmt->execute();
    }


    public function generateDummyData($num = 1)
    {

        $query  = "INSERT INTO " . $this->table . " (email, username, password) 
                   VALUES (:email, :username, :password)";

        $stmt = $this->conn->prepare($query);

        $dummy_mails = [
            'hallok@gmail.com', 'gmail@gmail.com', 'yescom@gmail.com', 'subaru@gmail.com',
            'gretasmail@gmail.com', 'mellb123@gmail.com', 'skrapmail@outlook.com', 'linasmail@yahoo.com',
            'maggansskor@outlook.com', 'sensusanne@outlook.com', 'anderslind83@gmail.com', 'jakgil2001@outlook.com',
            'animelover69@outlook.com', '1999sigge@outlook.com', 'outlook@outlook.com', 'callmemaybe@gmail.com',
            '213gustavo213@gmail.com', 'smarttools.se@yahoo.com', 'gfalland11@gmail.com', 'test@gmail.com', 'messmerimp@eldenring.com'
        ];

        $usernames = ['sizsizsez', 'sixsenth', 'melania', 'loklish', 'mrhippo02', 'Xre:zeroX', 'maggan1976', 'andersnyb11', 
        'axelsson_arts', 'messmer_impaler', 'firegiant1881', 'husville', 'santamonica', 'jakgil', 'sensuanne', 'animeanimeanime',
        'gustavofring', '123123123', 'toolenjoyer', 'håkanbråkan', 'hakström2001', 'my_rand_åker', 'jönköpings_jakob'];

        $passwords = ["kjdv23", "ljh324k", "sdjkh", "dsifu32", "lsidjfsSnesfdsdf", "123455676778234". "059140", "hallok01293",
        "maggan1234", "mellb123", "senzeisuss", "123abc123", "password123", "admin", "loginpasswoRd", "lkasdfkjsdfh", "hillish1230SHF",
        "mellan78", "banan69", "glassbåten100"];

        for($i = 0; $i < $num; $i++){
            $mail = $dummy_mails[array_rand($dummy_mails)];
            $stmt->bindParam(':email', $mail);
            $username = $usernames[array_rand($usernames)];
            $stmt->bindParam(':username', $username);
            $password = $passwords[array_rand($passwords)];
            $hpassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hpassword);

            $stmt->execute();
        }

        return true;


    }

    public function reorderAndResetAutoIncrement(){

        try {
            // DB Transaction
            $this->conn->beginTransaction();

            $query  = "SELECT id FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $articles = $stmt->fetchAll(PDO::FETCH_OBJ);

            // Update each Articles ID's sequentially

            $newId = 1;
            foreach($articles as $article){

                $updateQuery = "UPDATE " . $this->table . " SET id = :newId WHERE id = :old_id";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(':newId', $newId, PDO::PARAM_INT);
                $updateStmt->bindParam(':old_id', $article->id, PDO::PARAM_INT);
                $updateStmt->execute();
                $newId++;
            }


            // Reset Auto_INCREMENT

            $nextAutoIncrementId = $newId;
            $resetQuery = "ALTER TABLE " . $this->table . " AUTO_INCREMENT = :next_auto_increment ";
            $resetStmt = $this->conn->prepare($resetQuery);
            $resetStmt->bindParam(':next_auto_increment', $nextAutoIncrementId, PDO::PARAM_INT);
            $resetStmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $exception){
            $this->conn->rollBack();
            throw $exception;
        }


    }



    public function deleteMultiple($articleIds)
    {
        $placeholders = implode(',', array_fill(0, count($articleIds), '?'));
        $query = "DELETE FROM " . $this->table . " WHERE id IN ($placeholders)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($articleIds);
    }




}
