<?php
include "db.php";
include "header.php";

if (!isset($_GET['id'])) {
    die("Invalid request. No assignment selected.");
}
$assignment_id = intval($_GET['id']);

// Fetch assignment
$result = $conn->query("SELECT * FROM assignments WHERE id=$assignment_id");
if ($result->num_rows == 0) {
    die("Assignment not found.");
}
$assignment = $result->fetch_assoc();

$success = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = $_POST['student_name'];
    $student_id = $_POST['student_id'];

    // File upload
    $allowed = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        die("❌ Invalid file type. Allowed: " . implode(", ", $allowed));
    }

    // Create uploads directory if not exists
    $upload_dir = "uploads/" . $assignment_id;
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Generate safe file name
    $file_name = time() . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "_", basename($_FILES["file"]["name"]));
    $target_file = $upload_dir . "/" . $file_name;

    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        die("❌ Error uploading file.");
    }

    // Insert submission
    $stmt = $conn->prepare("INSERT INTO submissions (assignment_id, student_name, student_id, file_path) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $assignment_id, $student_name, $student_id, $target_file);
    $stmt->execute();

    $success = "✅ Submission uploaded successfully!";
}
?>

<h1 class="text-3xl font-bold text-gray-800 mb-2">📄 Submit Assignment</h1>
<p class="text-gray-600 mb-6">Assignment: <span class="font-semibold"><?php echo htmlspecialchars($assignment['title']); ?></span></p>

<?php if (!empty($success)): ?>
  <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
    <?php echo $success; ?>
  </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="bg-white p-8 rounded-xl shadow-md space-y-6">
  <div>
    <label class="block text-gray-700 font-semibold mb-2">Student Name</label>
    <input type="text" name="student_name" required
           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
  </div>

  <div>
    <label class="block text-gray-700 font-semibold mb-2">Student ID</label>
    <input type="text" name="student_id" required
           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
  </div>

  <div>
    <label class="block text-gray-700 font-semibold mb-2">Upload File</label>
    <input type="file" name="file" required
           class="w-full text-gray-700 border border-gray-300 px-4 py-2 rounded-lg cursor-pointer bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:outline-none">
    <p class="text-sm text-gray-500 mt-1">Allowed: PDF, DOC, DOCX, PPT, PPTX, JPG, JPEG, PNG</p>
  </div>

  <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-md">
    Submit Assignment
  </button>
</form>

<?php include "footer.php"; ?>
