<?php 

require_once __DIR__ . '/../../Config/Db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_collection = $db->User;

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 🟢 Check if request has multipart data (file + text)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $emergency = $_POST['emergency'] ?? '';
    $role = $_POST['role'] ?? '';
    $designation = $_POST['designation'] ?? '';
    $department = $_POST['department'] ?? '';
    $address = $_POST['address'] ?? '';
    $joinDate = $_POST['joinDate'] ?? '';
    $reportingManager = $_POST['reportingManager'] ?? '';
    $projects = isset($_POST['projects']) ? json_decode($_POST['projects'], true) : [];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 🟡 Generate new empId
    $last = $user_collection->findOne([], ['sort' => ['empId' => -1]]);
    if ($last && isset($last['empId'])) {
        $num = (int)substr($last['empId'], 3);
        $num++;
        $newId = 'EMP' . str_pad($num, 3, '0', STR_PAD_LEFT);
    } else {
        $newId = 'EMP001';
    }

    // 🟢 Handle file uploadc
    $uploadDir = __DIR__ . '/../../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $photoUrl = '';
    if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['profilePhoto']['tmp_name'];
        $fileName = uniqid('emp_') . '_' . basename($_FILES['profilePhoto']['name']);
        $targetPath = $uploadDir . $fileName;
        if (move_uploaded_file($tmpName, $targetPath)) {
            $photoUrl = 'http://localhost:8000/uploads/' . $fileName; // accessible URL
        }
    }

    $userData = [
        'empId' => $newId,
        'name' => $name,
        'email' => $email,
        'password' => $hashedPassword,
        'phone' => $phone,
        'emergencyContact' => $emergency,
        'role' => $role,
        'designation' => $designation,
        'department' => $department,
        'address' => $address,
        'joinDate' => $joinDate,
        'reportingManager' => $reportingManager,
        'projectIds' => $projects,
        'profilePhoto' => $photoUrl
    ];

    $result = $user_collection->insertOne($userData);

    if ($result) {
        echo json_encode(["status" => 'success']);
    } else {
        echo json_encode(["status" => 'failed']);
    }
}
?>
