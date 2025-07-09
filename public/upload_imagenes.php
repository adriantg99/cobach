<!DOCTYPE html>
<html lang="en">
<body>
    <form action="upload_imagenes.php" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="image"/>
        <input type="submit" name="submit" value="UPLOAD"/>
    </form>
</body>
</html>
<?php
if(isset($_POST["submit"])){
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false){
        $image = $_FILES['image']['tmp_name'];
        $imgContent = addslashes(file_get_contents($image));

        /*
         * Insert image data into database
         */

        //DB details
        $dbHost     = '127.0.0.1';
        $dbUsername = 'root';
        $dbPassword = '';
        $dbName     = 'sce-cobach-doctos_alumnos';

        //Create connection and select DB
        $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

        // Check connection
        if($db->connect_error){
            die("Connection failed: " . $db->connect_error);
        }

        $dataTime = date("Y-m-d H:i:s");

        //Insert image content into database
        $insert = $db->query("INSERT into imagenes (id,imagen) VALUES (1,'$imgContent')");
        if($insert){
            echo "File uploaded successfully.";
        }else{
            echo "File upload failed, please try again.";
        }
    }else{
        echo "Please select an image file to upload.";
    }
}
?>


<?php
if(!empty($_GET['id'])){
    //DB details
    $dbHost     = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '*****';
    $dbName     = 'programacionnet';

    //Create connection and select DB
    $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

    //Check connection
    if($db->connect_error){
       die("Connection failed: " . $db->connect_error);
    }

    //Get image data from database
    $result = $db->query("SELECT image FROM images WHERE id = {$_GET['id']}");

    if($result->num_rows > 0){
        $imgData = $result->fetch_assoc();

        //Render image
        header("Content-type: image/jpg");
        echo $imgData['image'];
    }else{
        echo 'Image not found...';
    }
}
?>
