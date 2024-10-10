<?php
require_once('./config.php');

$searchQuery = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

if ($searchQuery !== '') {
    // Fetch matching results
    $sql = "SELECT * FROM archive_list WHERE title LIKE '%$searchQuery%' AND status = 1 ORDER BY search_count DESC LIMIT 5";
    $result = $conn->query($sql);

    // Collect IDs for later update
    $ids = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<li class="list-group-item suggestion-item text-sm" data-title="' . htmlspecialchars($row['title']) . '">' 
                . htmlspecialchars($row['title']) . '<p class="text-primary">Search popularity: ' . $row['search_count'] . '</p></li>';
            $ids[] = $row['id'];
        }
        $_SESSION['search_query'] = $searchQuery;
        $_SESSION['search_ids'] = $ids;
    } else {
        echo '<li class="list-group-item suggestion-item">No suggestions available</li>';
    }
}
?>
