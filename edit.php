<?php
include "db.php";
include "header.php";

if (!isset($_GET['id'])) {
    die("Invalid request.");
}
$id = intval($_GET['id']);

// Fetch existing assignment
$result = $conn->query("SELECT * FROM assignments WHERE id=$id");
if ($result->num_rows == 0) {
    die("Assignment not found.");
}
$assignment = $result->fetch_assoc();

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    $stmt = $conn->prepare("UPDATE assignments SET title=?, description=?, due_date=? WHERE id=?");
    $stmt->bind_param("sssi", $title, $description, $due_date, $id);
    $stmt->execute();

    echo "<div class='bg-green-100 text-green-800 p-4 rounded mb-4'>✅ Assignment updated successfully!</div>";
    $assignment['title'] = $title;
    $assignment['description'] = $description;
    $assignment['due_date'] = $due_date;
}
?>

<h1 class="text-2xl font-bold mb-4">✏ Edit Assignment</h1>
<form method="post" class="space-y-4 bg-white shadow-md rounded-xl p-6">
  <div>
    <label class="block font-medium">Title</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($assignment['title']); ?>" 
           class="w-full border px-3 py-2 rounded" required>
  </div>
  <div>
    <label class="block font-medium">Description</label>
    <textarea name="description" class="w-full border px-3 py-2 rounded"><?php echo htmlspecialchars($assignment['description']); ?></textarea>
  </div>
  <div>
    <label class="block font-medium">Due Date</label>
    <input type="date" name="due_date" value="<?php echo $assignment['due_date']; ?>" 
           class="w-full border px-3 py-2 rounded" required>
  </div>
  <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">💾 Save Changes</button>
  <a href="index.php" class="ml-3 text-gray-600 hover:underline">Cancel</a>
</form>

<?php include "footer.php"; ?>
