<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../Config/Db.php';



if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



// header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: https://aripen-frontend.vercel.app");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); 
}

$collection=$db->User;

$data = json_decode(file_get_contents("php://input"),true);
var_dump($data);
if($data){
   $empId = $data['EmpId'];
   $section = $data['section'];

   $updateData=[];

   switch ($section) {
        case 'meta':
            $updateData=[
              'name' => $data['firstname'] . " " . $data['lastname'],
              'email'=>$data['email'],
              'phone'=>$data['phone'],
              'bio'=>$data['bio'],
            ];
        break;

          case 'info':
             $updateData=[
              'name' => $data['firstname'] . " " . $data['lastname'],
              'email'=>$data['email'],
              'phone'=>$data['phone'],
              'designation'=>$data['bio'],
            ];
        break;

          case 'address':
            $updateData=[
              "address"=>$data['country'] . " " . $data['city'],
              'postalcode'=>$data['postalcode'],
              'empId'=>$data['empid'],
            ];
        break;
    
    default:
          http_response_code(400);
        echo json_encode([
          "status" => "error",
           "message" => "Invalid section"
          ]);
        exit;
        break;
   }

   
$result= $collection->updateOne(
  ['empId'=>$empId],
  ['$set'=>$updateData],
);

if ($result->getModifiedCount()) {
    echo json_encode([
      "status" => "success",
       "message" => "Profile updated"
      ]);
} else {
    echo json_encode([
      "status" => "error",
       "message" => "No changes made"
      ]);
}
}
?>