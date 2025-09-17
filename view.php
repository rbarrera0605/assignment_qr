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

// Fetch submissions
$submissions = $conn->query("SELECT * FROM submissions WHERE assignment_id=$assignment_id ORDER BY submitted_at DESC");
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">📚 Assignment Details</h1>

<div class="bg-white rounded-xl shadow-md p-6 mb-8">
  <h2 class="text-xl font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($assignment['title']); ?></h2>
  <p class="text-gray-600 mb-2"><b>Description:</b> <?php echo htmlspecialchars($assignment['description']); ?></p>
  <p class="text-gray-600 mb-2"><b>Due Date:</b> <?php echo $assignment['due_date']; ?></p>
  <?php if (!empty($assignment['qr_code'])): ?>
    <p class="mt-4"><b>QR Code:</b><br><img src="<?php echo $assignment['qr_code']; ?>" class="w-32 border p-2 rounded"></p>
  <?php endif; ?>
</div>

<h2 class="text-2xl font-bold text-gray-800 mb-4">📥 Student Submissions</h2>

<?php if ($submissions->num_rows > 0): ?>
  <div class="overflow-x-auto">
    <table class="w-full border-collapse bg-white shadow-md rounded-xl overflow-hidden">
      <thead class="bg-blue-600 text-white">
        <tr>
          <th class="px-4 py-3 text-left">ID</th>
          <th class="px-4 py-3 text-left">Student Name</th>
          <th class="px-4 py-3 text-left">Student ID</th>
          <th class="px-4 py-3 text-left">File</th>
          <th class="px-4 py-3 text-left">Submitted At</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $submissions->fetch_assoc()): ?>
          <tr class="border-b hover:bg-gray-50">
            <td class="px-4 py-3"><?php echo $row['id']; ?></td>
            <td class="px-4 py-3"><?php echo htmlspecialchars($row['student_name']); ?></td>
            <td class="px-4 py-3"><?php echo htmlspecialchars($row['student_id']); ?></td>
            <td class="px-4 py-3">
              <a href="<?php echo $row['file_path']; ?>" target="_blank" class="text-blue-600 hover:underline">Download</a>
            </td>
            <td class="px-4 py-3"><?php echo $row['submitted_at']; ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
<?php else: ?>
  <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg">
    ⚠ No submissions yet.
  </div>
<?php endif; ?>

<div class="mt-6">
  <a href="index.php" class="text-gray-600 hover:underline">⬅ Back to Dashboard</a>
</div>

<?php include "footer.php"; ?>
