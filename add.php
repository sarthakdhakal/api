<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require 'database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();


$data = json_decode(file_get_contents("php://input"));


$msg['message'] = '';


if(isset($data->name) && isset($data->age) ){
   
    if(!empty($data->name) && !empty($data->age)){
        
        $insert_query = "INSERT INTO `users`(name,age) VALUES(:name,:age)";
        
        $insert_stmt = $conn->prepare($insert_query);
        
        $insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($data->name)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':age', htmlspecialchars(strip_tags($data->age)),PDO::PARAM_STR);
     
        
        if($insert_stmt->execute()){
            $msg['message'] = 'Data Inserted Successfully';
        }else{
            $msg['message'] = 'Data not Inserted';
        } 
        
    }else{
        $msg['message'] = 'Oops! empty field detected. Please fill all the fields';
    }
}
else{
    $msg['message'] = 'Please fill all the fields | name,age';
}

echo  json_encode($msg);
?>