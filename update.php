<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();


$data = json_decode(file_get_contents("php://input"));

if(isset($data->id)){
    
    $msg['message'] = '';
    $id = $data->id;
    
    $get_users = "SELECT * FROM `users` WHERE id=:id";
    $get_stmt = $conn->prepare($get_users);
    $get_stmt->bindValue(':id', $id,PDO::PARAM_INT);
    $get_stmt->execute();
    
 
    if($get_stmt->rowCount() > 0){

        $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
        
     
        $name = isset($data->name) ? $data->name : $row['name'];
        $age = isset($data->age) ? $data->age : $row['age'];

        $update_query = "UPDATE `users` SET name = :name, age = :age 
        WHERE id = :id";
        
        $update_stmt = $conn->prepare($update_query);
        
      
        $update_stmt->bindValue(':name', htmlspecialchars(strip_tags($name)),PDO::PARAM_STR);
        $update_stmt->bindValue(':age', htmlspecialchars(strip_tags($age)),PDO::PARAM_STR);
      
        $update_stmt->bindValue(':id', $id,PDO::PARAM_INT);
        
        
        if($update_stmt->execute()){
            $msg['message'] = 'Data updated successfully';
        }else{
            $msg['message'] = 'data not updated';
        }   
        
    }
    else{
        $msg['message'] = 'Invlid ID';
    }  
    
    echo  json_encode($msg);
    
}
?>