<?php 

require_once __DIR__ . '/../../Config/Db.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$Leave_collection=$db->Leave;

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");




if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); 
}

$data = json_decode(file_get_contents("php://input"),true);

 $LastLeaveId = $Leave_collection->findOne([],['sort'=>['leaveId' => -1]]);

    if($LastLeaveId && isset($LastLeaveId['leaveId'])){
          $num = (int)substr($LastLeaveId['leaveId'], 5);  
          $num++;  
          $newLeaveId = 'LEAVE' . str_pad($num, 3, '0', STR_PAD_LEFT);  
    }else{
        $newLeaveId='LEAVE001';
    }

if ($data) {

   

    $insertdata=[
        'leaveId'=>$newLeaveId,
        'empId'=>$data['empId'],
        'reason'=>$data['reason'],
        'description'=>$data['description'],
        'leaveDate'=>$data['leaveDate'],
        'status'=>$data['status']
    ];

      $result = $Leave_collection->insertone($insertdata);
   
   
}else {
     echo json_encode('not data found');
}
?>