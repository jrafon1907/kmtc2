<?php
    include('session.php');
    include('conn.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fileInfo = PATHINFO($_FILES["image"]["name"]);

        if (empty($_FILES["image"]["name"])) {
            $location = "";
        } else {
            if ($fileInfo['extension'] == "jpg" OR $fileInfo['extension'] == "png") {
                $newFilename = $fileInfo['filename'] . "_" . time() . "." . $fileInfo['extension'];
                move_uploaded_file($_FILES["image"]["tmp_name"], "../upload/" . $newFilename);
                $location = "upload/" . $newFilename;

                // Insert image path into the database
                $sql = "INSERT INTO images (image_data) VALUES ('$location')";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>window.alert('Image inserted successfully!');</script>";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                $location = "";
                echo "<script>window.alert('Photo not added. Please upload JPG or PNG photo only!');</script>";
            }
        }
    }
?>
    



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Insert Image</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
    }
    
    .container {
            width: 500px;
            padding: 8px;
            background-color: #f5f5f5;
            border-right: none;
            border-radius: 10px;
            box-sizing: border-box;
            text-align: center;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.9);
            margin-left: 450px;
            margin-top: 200px;
    }

    #preview {
      margin-top: 20px;
    }

    #preview img {
      max-width: 300px;
      max-height: 300px;
      border-radius: 8px;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
    }

    #insertImageButton, #sendButton {
      padding: 10px 20px;
      font-size: 16px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin: 5px;
    }

    #insertImageButton:hover, #sendButton:hover {
      background-color: #0056b3;
    }

    #imageInput {
      display: none;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Proof of receiving items</h2>
    <form id="uploadForm" action="receive_order.php" method="post" enctype="multipart/form-data">
        <button id="insertImageButton" type="button">Insert Image</button>
        <input type="file" name="image" id="imageInput" accept="image/*" style="display: none;">
        <div id="preview"></div>
        <button type="submit" id="sendButton">Send</button>
    </form>
  </div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var insertImageButton = document.getElementById('insertImageButton');
        var imageInput = document.getElementById('imageInput');
        var preview = document.getElementById('preview');

        insertImageButton.addEventListener('click', function() {
            imageInput.click();
        });

        imageInput.addEventListener('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    var imageUrl = event.target.result;
                    preview.innerHTML = '<img src="' + imageUrl + '" alt="Preview">';
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>

</body>
</html>
