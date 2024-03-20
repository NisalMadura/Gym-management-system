<?php
error_reporting(0);
ob_start();
session_start();
session_start();


if (!isset($_SESSION['AdminId']) || !isset($_SESSION['FirstName'])) {

    header("Location: admin_login.php");
    exit(); 
}
?>

<!DOCTYPE html>
<html>
<head>
  <title></title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
  

  <!-- Add Webcam.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

  <style>
    
    .select2-container--default .select2-selection--single {
      width: 572px !important; 
      height: 38px; 
      border: 3px solid #ccc; 
    }
    body {
      background-color: #21919B;
      margin: 0;
      padding: 0;
    }


    body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f4f4f4;
}

.container {
  display: flex;
  justify-content: space-around;
  align-items: center;
  height: 70vh;
}

.upload-section,
.preview-section {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.upload-form {
  text-align: center;
}

.upload-label {
  display: block;
  font-size: 18px;
  margin-bottom: 10px;
}

.input-file {
  display: block;
  margin: 0 auto 20px;
  padding: 10px;
  border: 2px solid #ddd;
  border-radius: 5px;
}

.upload-btn {
  background-color: #28A745;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.upload-btn:hover {
  background-color: #218838;
}

h2 {
  text-align: center;
  margin-bottom: 20px;
  font-size: 18px;
}

.uploaded-images {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  grid-gap: 10px;
 
}

.uploaded-images img {
  max-width: 100%;
  height: auto;
  border-radius: 5px;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
}
.container1 {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 150vh;
}

.card1 {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}



  </style>

</head><?php include'header.php';?>

<body style="background-image: url('images/pic5.jpg'); background-size: cover; background-position: center;">
<br><br>
<label for="user_id" style="padding-left: 25px; font-weight: bold; font-size: 18px; color: white;">Select User ID:</label>

<div id="memberSelectBoxes" class="select-box" style="padding-left: 15px;"></div>


<div class="container">
  <div class="upload-section">
    <div class="upload-form">
      <label for="imageUpload" class="upload-label">Upload photo</label>
      <input type="file" id="imageUpload" accept=".png, .jpg, .jpeg" class="input-file">
      <button type="button" class="btn btn-primary upload-btn" onclick="uploadAndSave()">Upload</button>
    </div>
  </div>

  <div class="preview-section">
    <h2>Uploaded Images</h2>
    <div class="uploaded-images" id="uploadedImages">
      <!-- Images will be displayed here -->
    </div>
  </div>
</div>

<div class="container1">
  <div class="card1">
    <div class="row">
      <div class="col-lg-12" align="center">
        <br><br><br><br>
        <label>Capture live photo</label>
        <div id="my_camera" class="pre_capture_frame"></div>
        <input type="hidden" name="captured_image_data" id="captured_image_data">
        <br>
        <input type="button" class="btn btn-info btn-round btn-file" value="Take Snapshot" onClick="take_snapshot()" style="background-color: darkgray;">
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12" align="center">
        <br><br><br><br>
        <label>Result</label>
        <div id="results">
          <img style="width: 350px;" class="after_capture_frame" src="image_placeholder.jpg" />
        </div>
        <br>
        <button type="button" class="btn btn-success" onclick="saveSnap()">Save Picture</button>
      </div>
    </div>
  </div>
</div>


<script language="JavaScript">
  // Webcam configuration and attachment
  Webcam.set({
    width: 350,
    height: 287,
    image_format: 'jpeg',
    jpeg_quality: 90
  });     
  Webcam.attach('#my_camera');
  
  function take_snapshot() {
    Webcam.snap(function(data_uri) {
      document.getElementById('results').innerHTML = '<img class="after_capture_frame" src="'+data_uri+'"/>';
      $("#captured_image_data").val(data_uri);
    });     
  }
  function uploadAndSave() {
    var selectedUserId = $('.js-example-basic-single').val(); 
    
    SaveImages(selectedUserId); 
    uploadPhoto();
}

function SaveImages(selectedUserId) {
    var fileInput = document.getElementById('imageUpload');
    var file = fileInput.files[0];
  
    var formData = new FormData();
    formData.append('image', file);
    formData.append('user_id', selectedUserId); 

    $.ajax({
    type: "POST",
    url: "capture_image_uploads.php",
    data: formData,
    contentType: false,
    processData: false,
    enctype: 'multipart/form-data',
    success: function(response) {
        var data = JSON.parse(response);
        if (data[0] === "Image uploaded successfully.") {
            Swal.fire('Success', 'Image uploaded successfully.', 'success');
            setTimeout(function() {
                location.reload(); 
            }, 3000); 
        } else if (data[0] === "Failed to upload image.") {
            Swal.fire('Error', 'Failed to upload image.', 'error');
        } else if (data[0] === "Invalid file format. Only JPG, JPEG, and PNG are allowed.") {
            Swal.fire('Error', 'Invalid file format. Only JPG, JPEG, and PNG are allowed.', 'error');
        } else if (data[0] === "No image data received or user ID not provided.") {
            Swal.fire('Error', 'No image data received or user ID not provided.', 'error');
        } else {
            Swal.fire('Error', 'An unknown error occurred.', 'error');
        }
    },
    error: function() {
        Swal.fire('Error', 'Error occurred while uploading the image.', 'error');
    }
});


}

function saveSnap() {
    var base64data = $("#captured_image_data").val();
    var user_id = $(".js-example-basic-single").val(); 

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "capture_image_upload.php",
        data: { image: base64data, user_id: user_id }, 
        success: function(data) {
            if (data.status === "success") {
                Swal.fire('Success', data.message, 'success');
                
                setTimeout(function() {
                    location.reload();
                }, 3000); 
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Error occurred while uploading the image.', 'error');
        }
    });
}







  $(document).ready(function() {
    createDropdown();
  });

  function createDropdown() {
    var selectUserDropdown = $("<select></select>");
    selectUserDropdown.attr("name", "member_user_id[]");
    selectUserDropdown.addClass("js-example-basic-single form-control"); 
    selectUserDropdown.attr("title", "Choose a user");
    selectUserDropdown.css("width", "40%");

    $("#memberSelectBoxes").append(selectUserDropdown);

    selectUserDropdown.select2({
      placeholder: 'Select User ID with Name',
      minimumInputLength: 2,
      ajax: {
        url: 'fetchUsernames.php',
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            username: params.term 
          };
        },
        processResults: function(data) {
          var results = data.map(function(user) {
            return { id: user.id, text: user.id + ' - ' + user.username };
          });

          return {
            results: results
          };
        },
        cache: true
      }
    });
  }

  function uploadPhoto() {
    const fileInput = document.getElementById('imageUpload');
    const files = fileInput.files;
    
    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      const reader = new FileReader();
      
      reader.onload = function(e) {
        const imageSrc = e.target.result;
        displayImage(imageSrc);
      };
      
      reader.readAsDataURL(file);
    }
  }


  function displayImage(imageSrc) {
    const imageElement = document.createElement('img');
    imageElement.src = imageSrc;
    imageElement.style.width = '150px'; 

    const tableRow = document.createElement('tr');
    const tableData = document.createElement('td');
    tableData.appendChild(imageElement);
    tableRow.appendChild(tableData);

    const uploadedImagesTable = document.getElementById('uploadedImages');
    uploadedImagesTable.appendChild(tableRow);
  }




</script>


</body>
</html>
