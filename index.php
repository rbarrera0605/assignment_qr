<?php 
include "db.php"; 
include "header.php"; 
?>

<div class="container mx-auto px-4 py-6">
  <h1 class="text-3xl font-bold mb-6">📚 Assignment Dashboard</h1>

  <!-- Success Messages -->
  <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
      🗑 Assignment deleted successfully!
    </div>
  <?php endif; ?>

  <!-- Add Assignment Button -->
  <div class="mb-6">
    <a href="add.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">➕ Add New Assignment</a>
  </div>

  <!-- Assignment Table -->
  <div class="overflow-x-auto bg-white shadow-md rounded-xl">
    <table class="min-w-full table-auto border-collapse">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-4 py-2 border">#</th>
          <th class="px-4 py-2 border">Title</th>
          <th class="px-4 py-2 border">Due Date</th>
          <th class="px-4 py-2 border">QR Code</th>
          <th class="px-4 py-2 border">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $conn->query("SELECT * FROM assignments ORDER BY id DESC");
        if ($result->num_rows > 0) {
            $counter = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr class='hover:bg-gray-50'>";
                echo "<td class='border px-4 py-2'>" . $counter++ . "</td>";
                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['title']) . "</td>";
                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['due_date']) . "</td>";
                echo "<td class='border px-4 py-2 text-center'>";
                if (!empty($row['qr_code']) && file_exists($row['qr_code'])) {
                    echo "<img src='" . $row['qr_code'] . "' alt='QR Code' class='w-16 h-16 mx-auto'>";
                } else {
                    echo "<span class='text-gray-500'>No QR</span>";
                }
                echo "</td>";
                echo "<td class='border px-4 py-2 space-x-2 text-center'>
                        <a href='view.php?id=" . $row['id'] . "' class='text-blue-600 hover:underline'>📄 View</a>
                        <a href='edit.php?id=" . $row['id'] . "' class='text-green-600 hover:underline'>✏ Edit</a>
                        <a href='delete.php?id=" . $row['id'] . "' 
                           onclick=\"return confirm('Are you sure you want to delete this assignment and all submissions?');\" 
                           class='text-red-600 hover:underline'>🗑 Delete</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5' class='text-center p-4 text-gray-500'>No assignments found.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<?php include "footer.php"; ?>
