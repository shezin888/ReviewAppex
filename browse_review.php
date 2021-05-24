
<?Php
echo "<!doctype html>
<html lang='en'>
  <head>
    <!-- Required meta tags -->

    <title>Review Basket</title>";
require "templates/head.php";
echo "<style>
.my_table > tbody > tr > td {
vertical-align: middle;
}
.my_table {width:100%;}
</style>
</head><body>";

require "templates/top.php";


require "config.php";
////////////
echo "<div class='row'>
<div class='col-md-11 offset-md-1'>";

if($stmt = $link->query("SELECT * FROM addreview")){

    #echo "No of records : ".$stmt->num_rows."<br>";
    
    echo "<table class='table my_table'>
    <tr class='info'> <th> Image </th><th>Name</th><th>Site</th><th>Review</th><th>Rating<br>Outof5</th></tr>";
    while ($row = $stmt->fetch_assoc()) {
    echo "<tr><td><img src=image/$row[filename] class='rounded-circle' alt='$row[pname]'></td>
    <td>$row[pname]</td><td>$row[sname]</td><td>$row[review]</td><td>$row[rating] </td></tr>";
    }
    echo "</table>";
    }else{
    echo $link->error;
    }
echo "</div></div>";

require "templates/bottom.php";	
?>

<script>
$(document).ready(function() {
/////////// form submission//

////
	
})
</script>
</body>
</html>