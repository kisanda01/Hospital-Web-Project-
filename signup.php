<?php
$firstName=$_POST["fname"];
$lastName=$_POST["lname"];
$email=$_POST["email"];
$username=$_POST["username"];
$password=$_POST["password"];

//database connection
$connection = mysqli_connect('localhost','root','','carehospital');
if($connection->connect_error){
    die("connection failed".$connection->connect_error);
}else{
    $sendto=$connection->prepare("insert into patients(fname,lname,email,username,password)values(?,?,?,?,?)");
    $sendto->bind_param("sssss",$firstName,$lastName,$email,$username,$password);
    $sendto->execute();
    echo "Singup Successfull"; 

    $sendto->close();
    $connection->close();
}
?>