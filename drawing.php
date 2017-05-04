<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
    header('content-type:text/html;charset=utf-8');

// $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = "127.0.0.1";//$url["host"];
$username = "root"; //$url["user"];
$password = "root";//$url["pass"];
$db = "draw";//substr($url["path"], 1);

$data = ($_POST["data"]);
for ($i=0; $i < strlen($data) ; $i++) { 
	// var_dump(ord($data[$i]));
	var_dump($data[$i]);
}
exit;

$conn = new mysqli($server, $username, $password, $db);
// $conn = new SQLite3('test.db'); //for my minimac which didnt have MYSQL

if ($conn->query(
	"CREATE TABLE IF NOT EXISTS drawings(
	  	id int(11) NOT NULL auto_increment,   
	 	name VARCHAR(16) NOT NULL,
	 	width int(11) NOT NULL,
	 	height int(11) NOT NULL,
	 	score int(11) DEFAULT 0,
	 	data text,
	 	date_created timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  		date_modified timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
	 	PRIMARY KEY (id)
	)"
)==true){
// echo "New record created successfully";
} else {
    echo "Error: " . $conn->error;
}

// echo "Starting";
//Post 
header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	// echo "post";
	$name = $_POST['name'];
	$width = $_POST['width'];
	$height = $_POST['height'];
	$data = $_POST['data'];
	$score=0; 
	if ($width && $height && $data){
		// $result = $conn->query("SELECT * FROM scores order by score desc")
		$sql = "INSERT INTO drawings (name, width, height, data) VALUES ('$name', '$width', '$height','$data')";
		// echo $sql;
		if ($conn->query($sql) === TRUE) {
		    echo "{status:'ok', name: '$name', data: '$data'}";
		} else {
		    echo "{status: 'error', error: '$conn->error'}";
		}
	}
}
//Else return results
else{	
	// echo "get";
	$myArray = array();
	if ($result = $conn->query("SELECT * FROM drawings order by datecreated desc")) {

	    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
	            $myArray[] = $row;
	    }
	    echo json_encode($myArray);
		}
}

?>