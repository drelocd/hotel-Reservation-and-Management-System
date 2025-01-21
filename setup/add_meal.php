<?php
require_once __DIR__ . '/db.php'; // Ensure this includes your database connection

if (!$conn) {
    die(json_encode(["success" => false, "message" => "Database connection failed."]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim(htmlspecialchars($_POST['name']));
    $description = trim(htmlspecialchars($_POST['description']));
    $price = trim($_POST['price']);
    $type = trim($_POST['type']);
    $image = $_FILES['image'];

    if (empty($name) || empty($description) || empty($price) || empty($type) || empty($image['name'])) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    if ($image['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(["success" => false, "message" => "Error uploading image."]);
        exit;
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    $fileType = mime_content_type($image['tmp_name']);
    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(["success" => false, "message" => "Only JPG and PNG images are allowed."]);
        exit;
    }

    list($width, $height) = getimagesize($image['tmp_name']);
    if ($width < 100 || $height < 100) {
        echo json_encode(["success" => false, "message" => "Image must be at least 300x100 pixels."]);
        exit;
    }

    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $imageExt = pathinfo($image["name"], PATHINFO_EXTENSION);
    $uniqueFileName = time() . "_" . uniqid() . "." . $imageExt;
    $imagePath = "uploads/" . $uniqueFileName; // Relative path to be stored in the DB

    if (!move_uploaded_file($image["tmp_name"], $uploadDir . $uniqueFileName)) {
        echo json_encode(["success" => false, "message" => "Failed to save image."]);
        exit;
    }

    // ✅ Insert correct image URL into database
    $sql = "INSERT INTO meals1 (name, description, price, type, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die(json_encode(["success" => false, "message" => "Prepare statement failed: " . $conn->error]));
    }

    $stmt->bind_param("ssdss", $name, $description, $price, $type, $uniqueFileName);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Meal added successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error adding meal: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
