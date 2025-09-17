<?php
include "db.php";
include "lib/phpqrcode/qrlib.php";
include "header.php";

$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    // Insert assignment
    $stmt = $conn->prepare("INSERT INTO assignments (title, description, due_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $description, $due_date);
    $stmt->execute();
    $assignment_id = $stmt->insert_id;

    // Generate QR code
    if (!is_dir("qrcodes")) {
        mkdir("qrcodes");
    }
    $qr_file = "qrcodes/assignment_" . $assignment_id . ".png";
    $qr_link = "http://localhost/assignment_qr/submit.php?id=" . $assignment_id;

    QRcode::png($qr_link, $qr_file);

    // Update DB
    $conn->query("UPDATE assignments SET qr_code='$qr_file' WHERE id=$assignment_id");

    $success = "✅ Assignment created successfully!";
}
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">➕ Create New Assignment</h1>

<?php if (!empty($success)): ?>
  <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
    <?php echo $success; ?>
  </div>
<?php endif; ?>

<form method="post" class="bg-white p-8 rounded-xl shadow-md space-y-6">
  <div>
    <label class="block text-gray-700 font-semibold mb-2">Title</label>
    <input type="text" name="title" required
           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
  </div>

  <div>
    <label class="block text-gray-700 font-semibold mb-2">Description</label>
    <textarea name="description" rows="4"
              class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
  </div>

  <div>
    <label class="block text-gray-700 font-semibold mb-2">Due Date</label>
    <input type="date" name="due_date" required
           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
  </div>

  <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-md">
    Save Assignment
  </button>
</form>

<?php include "footer.php"; ?>
