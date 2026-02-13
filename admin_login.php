<?php
//create database connection
$connection = mysqli_connect('localhost','root','','carehospital');

//user name and password
$username=$_POST["username"];
$password=$_POST["password"];

//run a query to obtain username and password from singup tables
$query ="SELECT * FROM admin WHERE username=? and password=?";
$sendto=$connection->prepare($query);
$sendto->bind_param("ss",$username,$password);
$sendto->execute();
$result =$sendto->get_result();

// Check if username and password are correct
if ($result->num_rows == 1) {
    echo "<script>
        alert('Login Successful');
        window.location.href = 'admin_dash.html';
    </script>";
} else {
    echo "<script>
        alert('Invalid Username or Password');
        window.history.back();
    </script>";
}

$sendto->close();
$connection->close();

?>

