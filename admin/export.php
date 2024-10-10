<?php
require('../config.php');

// Data from POST request
$department_count = $_POST['dept_count'] ?? ' --- ';
$curriculum_count = $_POST['curr_count'] ?? ' --- ';
$verified_students = $_POST['ver_stud'] ?? ' --- ';
$non_verified_students = $_POST['non_ver_stud'] ?? ' --- ';
$verified_archives = $_POST['ver_arch'] ?? ' --- ';
$non_verified_archives = $_POST['non_ver_arch'] ?? ' --- ';

// Prepare the output
$output = '<table cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%;">';

// Header
$output .= '<tr><td colspan="10" style="border:none;"></td></tr>';
$output .= '<tr style="background-color:#228B22; color:#ced4da;">';
$output .= '<th colspan="10" style="text-align:center; font-size:24px; color: white;">WebSMART Report</th>';
$output .= '</tr>';
$output .= '<tr><td colspan="10" style="border:none;"></td></tr>';
$output .= '<tr>';
$output .= '<td colspan="10" style="text-align:center; background-color:#ced4da;">';
$output .= '<strong>Pamantasan ng Cabuyao</strong><br>';
$output .= 'Cabuyao, Philippines 4025<br>';
$output .= '<i>' . date('F d, Y') . '</i>';
$output .= '</td>';
$output .= '</tr>';
$output .= '<tr><td colspan="10" style="border:none;"></td></tr>';

// System Analytics Section
$output .= '<tr style="background-color:#006400; color:#ced4da;">';
$output .= '<td colspan="10" style="text-align:center; font-weight:bold; color: white;">SYSTEM ANALYTICS</td>';
$output .= '</tr>';
$output .= '<tr style="background-color:#ced4da;">';
$output .= '<td colspan="7">Department List</td>';
$output .= '<td colspan="3" align="center">' . $department_count . '</td>';
$output .= '</tr>';
$output .= '<tr style="background-color:#ced4da;">';
$output .= '<td colspan="7">Curriculum List</td>';
$output .= '<td colspan="3" align="center">' . $curriculum_count . '</td>';
$output .= '</tr>';
$output .= '<tr style="background-color:#ced4da;">';
$output .= '<td colspan="7">Verified Students</td>';
$output .= '<td colspan="3" align="center">' . $verified_students . '</td>';
$output .= '</tr>';
$output .= '<tr style="background-color:#ced4da;">';
$output .= '<td colspan="7">Non-Verified Students</td>';
$output .= '<td colspan="3" align="center">' . $non_verified_students . '</td>';
$output .= '</tr>';
$output .= '<tr style="background-color:#ced4da;">';
$output .= '<td colspan="7">Verified Archives</td>';
$output .= '<td colspan="3" align="center">' . $verified_archives . '</td>';
$output .= '</tr>';
$output .= '<tr style="background-color:#ced4da;">';
$output .= '<td colspan="7">Non-Verified Archives</td>';
$output .= '<td colspan="3" align="center">' . $non_verified_archives . '</td>';
$output .= '</tr>';
$output .= '<tr><td colspan="10" style="border:none;"></td></tr>';
$output .= '<tr><td colspan="10" style="border:none;"></td></tr>';

// Historical Data Section
$output .= '<tr style="background-color:#006400; color:#ced4da;">';
$output .= '<td colspan="10" style="text-align:center; font-weight:bold; color: white;">HISTORICAL DATA</td>';
$output .= '</tr>';
$output .= '<tr><td colspan="10" style="border:none;"></td></tr>';

// Fetch years
$years_query = $conn->query("SELECT DISTINCT `year` FROM `archive_list` WHERE status = '1' ORDER BY `year` ASC");
$years = [];
while ($row = $years_query->fetch_assoc()) {
    $years[] = $row['year'];
}

// Fetch department data
$departments_query = $conn->query("SELECT d.name AS department, a.year, COUNT(a.id) AS count 
    FROM department_list d
    LEFT JOIN curriculum_list c ON d.id = c.department_id
    LEFT JOIN archive_list a ON c.id = a.curriculum_id AND a.status = '1'
    GROUP BY d.name, a.year
    ORDER BY d.name, a.year
");
$department_data = [];
while ($row = $departments_query->fetch_assoc()) {
    $department = $row['department'];
    $year = $row['year'];
    $count = $row['count'];

    if (!isset($department_data[$department])) {
        $department_data[$department] = array_fill_keys($years, 0);
    }

    $department_data[$department][$year] = $count;
}

// Output department data table
$output .= '<table cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%;">';
$output .= '<tr style="background-color:#ced4da;">';
$output .= '<th colspan="7">Departments</th>';

foreach ($years as $year) {
    $output .= '<th>' . $year . '</th>';
}
$output .= '</tr>';

foreach ($department_data as $department => $counts) {
    $output .= '<tr style="background-color:#ced4da;">';
    $output .= '<td colspan="7">' . $department . '</td>';

    foreach ($years as $year) {
        $output .= '<td align="center">' . ($counts[$year] ?: ' --- ') . '</td>';
    }
    $output .= '</tr>';
}

// Mixed Chart Data Section
$output .= '<tr><td colspan="10" style="border:none;"></td></tr>';
$output .= '<tr style="background-color:#006400; color:#ced4da;">';
$output .= '<td colspan="10" style="text-align:center; font-weight:bold; color: white;">SYSTEM ANALYTICS WITH TRENDS</td>';
$output .= '</tr>';
$output .= '<tr><td colspan="10" style="border:none;"></td></tr>';

// Fetch mixed chart data
$searches_query = $conn->query("SELECT year, SUM(search_count) AS search_count 
    FROM archive_list 
    WHERE status = '1'
    GROUP BY year
");

$views_query = $conn->query("SELECT year, SUM(views_count) AS view_count 
    FROM archive_list 
    WHERE status = '1'
    GROUP BY year
");

// Initialize arrays for search and view data
$search_data = [];
$view_data = [];

// Populate search data
while ($row = $searches_query->fetch_assoc()) {
    $search_data[$row['year']] = $row['search_count'];
}

// Populate view data
while ($row = $views_query->fetch_assoc()) {
    $view_data[$row['year']] = $row['view_count'];
}

// Prepare data for output
$years_for_chart = array_unique(array_merge(array_keys($search_data), array_keys($view_data)));
sort($years_for_chart);

// Output mixed chart data table
$output .= '<table cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%;">';
$output .= '<tr style="background-color:#ced4da;">';
$output .= '<th>Year</th>';
$output .= '<th>Top Searches</th>';
$output .= '<th>Top Views</th>';
$output .= '</tr>';

foreach ($years_for_chart as $year) {
    $search_count = isset($search_data[$year]) ? $search_data[$year] : ' --- ';
    $view_count = isset($view_data[$year]) ? $view_data[$year] : ' --- ';

    $output .= '<tr style="background-color:#ced4da;">';
    $output .= '<td>' . $year . '</td>';
    $output .= '<td align="center">' . $search_count . '</td>';
    $output .= '<td align="center">' . $view_count . '</td>';
    $output .= '</tr>';
}

// Top 10 Most Viewed Section
$output .= '<tr><td colspan="10" style="border:none;"></td></tr>';
$output .= '<tr style="background-color:#006400; color:#ced4da;">';
$output .= '<td colspan="10" style="text-align:center; font-weight:bold; color: white;">TOP 10 MOST VIEWED</td>';
$output .= '</tr>';
$output .= '<tr><td colspan="10" style="border:none;"></td></tr>';

$top_viewed_query = $conn->query("SELECT title, SUM(views_count) AS view_count 
    FROM archive_list 
    WHERE status = '1'
    GROUP BY title
    ORDER BY view_count DESC
    LIMIT 10
");

$output .= '<table cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%;">';
$output .= '<tr style="background-color:#ced4da;">';
$output .= '<th colspan="9">Title</th>';
$output .= '<th>Views</th>';
$output .= '</tr>';

while ($row = $top_viewed_query->fetch_assoc()) {
    $output .= '<tr style="background-color:#ced4da;">';
    $output .= '<td colspan="9">' . $row['title'] . '</td>';
    $output .= '<td align="center">' . $row['view_count'] . '</td>';
    $output .= '</tr>';
}

// Top 10 Most Searched Section
$output .= '<tr><td colspan="10" style="border:none;"></td></tr>';
$output .= '<tr style="background-color:#006400; color:#ced4da;">';
$output .= '<td colspan="10" style="text-align:center; font-weight:bold; color: white;">TOP 10 MOST SEARCHED</td>';
$output .= '</tr>';
$output .= '<tr><td colspan="10" style="border:none;"></td></tr>';

$top_searched_query = $conn->query("SELECT title, SUM(search_count) AS search_count 
    FROM archive_list 
    WHERE status = '1'
    GROUP BY title
    ORDER BY search_count DESC
    LIMIT 10
");

$output .= '<table cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%;">';
$output .= '<tr style="background-color:#ced4da;">';
$output .= '<th colspan="9">Title</th>';
$output .= '<th>Searches</th>';
$output .= '</tr>';

while ($row = $top_searched_query->fetch_assoc()) {
    $output .= '<tr style="background-color:#ced4da;">';
    $output .= '<td colspan="9">' . $row['title'] . '</td>';
    $output .= '<td align="center">' . $row['search_count'] . '</td>';
    $output .= '</tr>';
}

// Topic Trend per Year (Top 5) Section
$output .= '<tr><td colspan="10" style="border:none;"></td></tr>';
$output .= '<tr style="background-color:#006400; color:#ced4da;">';
$output .= '<td colspan="10" style="text-align:center; font-weight:bold; color: white;">TOP 5 TOPIC TRENDS PER YEAR</td>';
$output .= '</tr>';
$output .= '<tr><td colspan="10" style="border:none;"></td></tr>';

$topic_trends_query = $conn->query("SELECT year, title, SUM(views_count) AS view_count 
    FROM archive_list 
    WHERE status = '1'
    GROUP BY year, title
    ORDER BY year, view_count DESC
");

$topic_trends = [];
while ($row = $topic_trends_query->fetch_assoc()) {
    $year = $row['year'];
    if (!isset($topic_trends[$year])) {
        $topic_trends[$year] = [];
    }
    $topic_trends[$year][] = $row;
}

// Output Topic Trends per Year (Top 5)
$output .= '<table cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%;">';

foreach ($topic_trends as $year => $trends) {
    $top_5 = array_slice($trends, 0, 5);
    $output .= '<tr style="background-color:#ced4da;">';
    $output .= '<th>Year</th>';
    $output .= '<th colspan="8">Title</th>';
    $output .= '<th>Views</th>';
    $output .= '</tr>';

    foreach ($top_5 as $trend) {
        $output .= '<tr style="background-color:#ced4da;">';
        $output .= '<td>' . $trend['year'] . '</td>';
        $output .= '<td colspan="8">' . $trend['title'] . '</td>';
        $output .= '<td align="center">' . $trend['view_count'] . '</td>';
        $output .= '</tr>';
    }
}

// Final output
ob_clean();
header('Content-Type:application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=WebSMART_Report.xls');
echo $output;
?>
