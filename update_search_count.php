<?php
require_once('./config.php');
session_start();

if (isset($_SESSION['search_query'])) {
    $searchQuery = $conn->real_escape_string($_SESSION['search_query']);
    
    // Increment search_count only if session search_query is set
    if ($searchQuery !== '') {
        // Ensure the query increments the count only once per search
        $updateSql = "UPDATE archive_list 
                      SET search_count = search_count + 1 
                      WHERE title LIKE '%$searchQuery%' AND status = 1";
        $conn->query($updateSql);
    }

    // Unset session variable after update
    unset($_SESSION['search_query']);
}
?>
