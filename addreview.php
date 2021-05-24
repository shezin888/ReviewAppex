<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$pname = $review = $rate = $sname = "";
$pname_err = $review_err = $rating_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate product name
    if(empty(trim($_POST["pname"]))){
        $pname_err = "Please enter a Product Name.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM addreview WHERE pname = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_pname);
            
            // Set parameters
            $param_pname = trim($_POST["pname"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                $pname = trim($_POST["pname"]);
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }


    if(empty(trim($_POST["sname"]))){
        $sname_err = "Please enter a Site Name.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM addreview WHERE sname = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_sname);
            
            // Set parameters
            $param_sname = trim($_POST["sname"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                $sname = trim($_POST["sname"]);
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }


    //validating reviews
    if(empty(trim($_POST["review"]))){
        $review_err = "Please enter a review.";
    }

    else{
        // Prepare a select statement
        $sql = "SELECT id FROM addreview WHERE review = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_review);
            
            // Set parameters
            $param_review = trim($_POST["review"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
            
                $review = trim($_POST["review"]);
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
          
    }
    

    // if(isset($_POST['upload'])){
 
    //     $name = $_FILES['file']['name'];
    //     $target_dir = "image/";
    //     $target_file = $target_dir . basename($_FILES["file"]["name"]);
      
    //     // Select file type
    //     $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      
    //     // Valid file extensions
    //     $extensions_arr = array("jpg","jpeg","png","gif");
      
    //     // Check extension
    //     if( in_array($imageFileType,$extensions_arr) ){
       
    //        // Insert record
    //         $param_image= $name;
    //        // Upload file
    //        move_uploaded_file($_FILES['file']['tmp_name'],$target_dir.$name);
      
    //     }
    // }

    function imageResize($imageResourceId,$width,$height) {
    
    
        $targetWidth =200;
        $targetHeight =200;
    
    
        $targetLayer=imagecreatetruecolor($targetWidth,$targetHeight);
        imagecopyresampled($targetLayer,$imageResourceId,0,0,0,0,$targetWidth,$targetHeight, $width,$height);
    
    
        return $targetLayer;
    }


    if(isset($_POST["upload"])) {
        if(is_array($_FILES)) {
    
    
            $file = $_FILES['file']['tmp_name']; 
            $sourceProperties = getimagesize($file);
            $fileNewName = time();
            $folderPath = "image/";
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $imageType = $sourceProperties[2];
    
    
            switch ($imageType) {
    
    
                case IMAGETYPE_PNG:
                    $imageResourceId = imagecreatefrompng($file); 
                    $targetLayer = imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);
                    imagepng($targetLayer,$folderPath. $fileNewName. "_thump.". $ext);
                    break;
    
    
                case IMAGETYPE_GIF:
                    $imageResourceId = imagecreatefromgif($file); 
                    $targetLayer = imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);
                    imagegif($targetLayer,$folderPath. $fileNewName. "_thump.". $ext);
                    break;
    
    
                case IMAGETYPE_JPEG:
                    $imageResourceId = imagecreatefromjpeg($file); 
                    $targetLayer = imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);
                    imagejpeg($targetLayer,$folderPath. $fileNewName. "_thump.". $ext);

                    break;
    
    
                default:
                    echo "Invalid Image type.";
                    exit;
                    break;
            }
    
    
            move_uploaded_file($file, $folderPath. $fileNewName. ".". $ext);
            $x=$fileNewName. "_thump.". $ext;
            $param_image=$x;
            echo "Image Resize Successfully.";
        }
    }
    
    
 

    
    if(empty(trim($_POST["rate"]))){
        $review_err = "Please give a valid rating.";
    }

    else{
        // Prepare a select statement
        $sql = "SELECT id FROM addreview WHERE rating = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_rating);
            
            // Set parameters
            $param_rating = trim($_POST["rate"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
            
                $rating = trim($_POST["rate"]);
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
          
    }
    
    
    // Check input errors before inserting in database
    if(empty($pname_err) && empty($review_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO addreview (pname, sname, review, filename, rating) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_pname, $param_sname, $param_review, $param_image, $param_rating);
            
            // Set parameters
            $param_pname = $pname;
            $param_sname = $sname;
            $param_review = $review;
            $param_image = $x;
            $param_rating = $rating;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: welcome.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Review</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 50%;  position: absolute; right: 0px; height: 100%; padding: 100px 230px 100px 100px; }
        .btn {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 10px 24px;
            text-align: center;
            width: 150px;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            }

        .bg-img {
        /* The image used */

        background-image: url("image_bg.jpg");
        min-height: 600px;
        left: 30px;
        top: 20px;

        /* Center and scale the image nicely */
        background-position: left;
        background-repeat: no-repeat;
        position: relative;
        background-size: 50% 100%;

        
        }
    </style>
</head>
<body>
    <div class="bg-img">
        <div class="wrapper">
            <h2>Add a Review</h2>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype='multipart/form-data'>
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="pname" class="form-control <?php echo (!empty($pname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $pname; ?>">
                    <span class="invalid-feedback"><?php echo $pname_err; ?></span>
                </div> 
                <div class="form-group">
                    <label>Shopping Site</label>
                    <input type="text" name="sname" class="form-control <?php echo (!empty($sname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $sname; ?>">
                    <span class="invalid-feedback"><?php echo $sname_err; ?></span>
                </div> 
                <div class="form-group">
                    <label>Write your Review</label>
                    <input type="text" name="review" class="form-control <?php echo (!empty($review_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $review; ?>">
                    <span class="invalid-feedback"><?php echo $review_err; ?></span>
                </div>    
                <div class="form-group">
                    <label>Rating</label>
                    <br>
                    <div class="rate">
                        <input type="radio" id="star5" name="rate" value="5" />
                        <label for="star5" title="text">5 stars</label>
                        <input type="radio" id="star4" name="rate" value="4" />
                        <label for="star4" title="text">4 stars</label>
                        <input type="radio" id="star3" name="rate" value="3" />
                        <label for="star3" title="text">3 stars</label>
                        <input type="radio" id="star2" name="rate" value="2" />
                        <label for="star2" title="text">2 stars</label>
                        <input type="radio" id="star1" name="rate" value="1" />
                        <label for="star1" title="text">1 star</label>
                    </div>
                </div>

                <div class="form-group">
                    <br>
                    <br>
                    <label>Upload Image</label>
                        <input type="file" name="file" />
                </div>  

                <div class="form-group">
                    <br>
                    <a href="welcome.php" class="btn btn-primary">Cancel</a>
                    <input type="submit" name="upload" class="btn btn-primary" value="Submit">

                </div>
            </form>
        </div>
    </div>    
</body>
</html>