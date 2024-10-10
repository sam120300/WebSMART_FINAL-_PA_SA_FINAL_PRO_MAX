<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Custom Styles -->
    <style>
        .info-box {
            transition: all 0.3s ease-in-out;
            height: 100%;
        }
        .info-box:hover {
            background-color: #f8f9fa;
            transform: translateY(-5px);
        }
        .info-icon {
            flex-shrink: 0;
        }
        .info-box-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        /* Ensure chart containers take full width and adjust height */
        #chart1, #chart2 {
            width: 100% !important;
            height: 100% !important;
        }
        /* Optional: Adjust heights for different screen sizes */
        @media (max-width: 768px) {
            #chart1, #chart2 {
                height: 300px !important;
            }
        }
        @media (max-width: 480px) {
            #chart1, #chart2 {
                height: 250px !important;
            }
        }
    </style>
</head>
<body>
    <center>
        <h1>Welcome to <?php echo $_settings->info('name') ?></h1>
    </center>
    <?php 
    $department_count = $conn->query("SELECT * FROM `department_list` WHERE status = 1")->num_rows;
    $curriculum_count = $conn->query("SELECT * FROM `curriculum_list` WHERE status = 1")->num_rows;
    $verified_students = $conn->query("SELECT * FROM `student_list` WHERE status = 1")->num_rows;
    $not_verified_students = $conn->query("SELECT * FROM `student_list` WHERE status = 0")->num_rows;
    $verified_archives = $conn->query("SELECT * FROM `archive_list` WHERE status = 1")->num_rows;
    $not_verified_archives = $conn->query("SELECT * FROM `archive_list` WHERE status = 0")->num_rows;
    ?>
<?php
// Fetch Top 5 Searches
$top_searches = $conn->query("SELECT title, search_count FROM archive_list WHERE status = 1 ORDER BY search_count DESC LIMIT 5");
$top_searches_data = [];
while ($row = $top_searches->fetch_assoc()) {
    $top_searches_data[] = ['title' => $row['title'], 'search_count' => $row['search_count']];
}

// Fetch Top 10 Most Viewed
$top_viewed = $conn->query("SELECT title, views_count FROM archive_list WHERE status = 1 ORDER BY views_count DESC LIMIT 10");
$top_viewed_data = [];
while ($row = $top_viewed->fetch_assoc()) {
    $top_viewed_data[] = ['title' => $row['title'], 'views_count' => $row['views_count']];
}

// Fetch Top 5 Most Viewed and Searched per Year
$top_yearly = $conn->query("SELECT year, title, SUM(views_count) as total_views, SUM(search_count) as total_searches FROM archive_list WHERE status = 1 GROUP BY year, title ORDER BY total_views DESC, total_searches DESC LIMIT 5");
$top_yearly_data = [];
while ($row = $top_yearly->fetch_assoc()) {
    $top_yearly_data[] = [
        'year' => $row['year'],
        'title' => $row['title'],
        'total_views' => $row['total_views'],
        'total_searches' => $row['total_searches']
    ];
}
?>

    <hr class="border-info">
    <div class="container-fluid">
        <div class="row" >
            <!-- Left Side: 2x3 Grid of Info Boxes -->
            <div class="col-12 col-lg-6">
                <div class="row">
                    <!-- First row -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="info-box bg-light shadow-md p-3 rounded">
                            <div class="d-flex align-items-center">
                                <div class="info-icon mr-3">
                                    <i class="fas fa-building fa-2x text-primary"></i>
                                </div>
                                <div class="info-box-content">
                                    <span class="info-box-text font-weight-bold">Departments</span>
                                    <span class="info-box-number h3"><?php echo $department_count; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="info-box bg-light shadow-md p-3 rounded">
                            <div class="d-flex align-items-center">
                                <div class="info-icon mr-3">
                                    <i class="fas fa-book fa-2x text-success"></i>
                                </div>
                                <div class="info-box-content">
                                    <span class="info-box-text font-weight-bold">Curriculums</span>
                                    <span class="info-box-number h3"><?php echo $curriculum_count; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second row -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="info-box bg-light shadow-md p-3 rounded">
                            <div class="d-flex align-items-center">
                                <div class="info-icon mr-3">
                                    <i class="fas fa-user-check fa-2x text-info"></i>
                                </div>
                                <div class="info-box-content">
                                    <span class="info-box-text font-weight-bold">Verified Students</span>
                                    <span class="info-box-number h3"><?php echo $verified_students; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="info-box bg-light shadow-md p-3 rounded">
                            <div class="d-flex align-items-center">
                                <div class="info-icon mr-3">
                                    <i class="fas fa-user-times fa-2x text-warning"></i>
                                </div>
                                <div class="info-box-content">
                                    <span class="info-box-text font-weight-bold">Not Verified Students</span>
                                    <span class="info-box-number h3"><?php echo $not_verified_students; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Third row -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="info-box bg-light shadow-md p-3 rounded">
                            <div class="d-flex align-items-center">
                                <div class="info-icon mr-3">
                                    <i class="fas fa-archive fa-2x text-secondary"></i>
                                </div>
                                <div class="info-box-content">
                                    <span class="info-box-text font-weight-bold">Verified Archives</span>
                                    <span class="info-box-number h3"><?php echo $verified_archives; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="info-box bg-light shadow-md p-3 rounded">
                            <div class="d-flex align-items-center">
                                <div class="info-icon mr-3">
                                    <i class="fas fa-archive fa-2x text-danger"></i>
                                </div>
                                <div class="info-box-content">
                                    <span class="info-box-text font-weight-bold">Not Verified Archives</span>
                                    <span class="info-box-number h3"><?php echo $not_verified_archives; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side: Graph Boxes -->
            <div class="col-12 col-lg-6">
                <div class="info-box bg-light shadow mb-4 p-3 rounded" style="height: 400px;">
                    <!-- Bar Chart -->
                    <div id="chart1" style="height: 100%; width: 100%;"></div>
                </div>
            </div>

            <div class="col-12 col-lg-12 col-md-12" style="height: auto;">
                <div class="info-box bg-light shadow mb-4 p-3 rounded" style="height: 400px;">
                    <div id="mixedChart" style="height: 100%; width: 100%;"></div>
                </div>
            </div>
        </div>

        <!-- Generate Report Button -->
        <div class="row mt-4" >
            <div class="col"></div>
            <div class="col-12 col-md-6 text-center">
                <form action="export.php" method="POST">
                    <input type="hidden" name="dept_count" value="<?php echo $department_count; ?>">
                    <input type="hidden" name="curr_count" value="<?php echo $curriculum_count; ?>">
                    <input type="hidden" name="ver_stud" value="<?php echo $verified_students; ?>">
                    <input type="hidden" name="non_ver_stud" value="<?php echo $not_verified_students; ?>">
                    <input type="hidden" name="ver_arch" value="<?php echo $verified_archives; ?>">
                    <input type="hidden" name="non_ver_arch" value="<?php echo $not_verified_archives; ?>">
                    <button type="submit" class="btn btn-success" name="submit">Generate Report</button>
                </form>
            </div>
            <div class="col"></div>
        </div>
    </div>

    <?php 
    $archive_data = [];
    $departments = [];
    
    $result = $conn->query("SELECT `year`, COUNT(*) as `count` FROM `archive_list` WHERE status = '1' GROUP BY `year` ");
    while ($row = $result->fetch_assoc()) {
        $archive_data[] = [
            'year' => $row['year'],
            'count' => $row['count']
        ];
    }
    ?>

    <script>
    var archiveData = <?php echo json_encode($archive_data); ?>;

    var years = archiveData.map(function(item) { return item.year; });
    var counts = archiveData.map(function(item) { return item.count; });

    var barOptions = {
        chart: {
            type: 'bar',
            height: '100%',
            width: '100%',
            background: '#fff',
            foreColor: '#636c81',
            toolbar: {
                show: true,
                offsetX: 0,
                offsetY: 0,
            },
            responsive: [{
                breakpoint: 768,
                options: {
                    chart: {
                        height: 300
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }, {
                breakpoint: 480,
                options: {
                    chart: {
                        height: 250
                    },
                    legend: {
                        position: 'bottom'
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '50%',
                        }
                    },
                    xaxis: {
                        labels: {
                            rotate: -45
                        }
                    }
                }
            }]
        },
        title: {
            text: 'Archives per Year',
            align: 'center',
            style: {
                fontSize: '24px',
                fontWeight: 'bold',
                color: '#333'
            }
        },
        series: [{
            name: 'Archive Count',
            data: counts 
        }],
        xaxis: {
            categories: years,
            labels: {
                rotate: -45,
                style: {
                    fontSize: '12px'
                }
            }
        }, 
        colors: ['#2b8a3e', '#123223'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '70%',
            }
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            position: 'right',
            offsetY: 40
        }
    };

    var barChart = new ApexCharts(document.querySelector("#chart1"), barOptions);
    barChart.render();

    var topSearches = <?php echo json_encode($top_searches_data); ?>;
    var topViewed = <?php echo json_encode($top_viewed_data); ?>;
    var topYearly = <?php echo json_encode($top_yearly_data); ?>;

    var searchTitles = topSearches.map(item => item.title);
    var searchCounts = topSearches.map(item => item.search_count);

    var viewedTitles = topViewed.map(item => item.title);
    var viewCounts = topViewed.map(item => item.views_count);

    var yearlyTitles = topYearly.map(item => item.title + ' (' + item.year + ')');
    var yearlyViews = topYearly.map(item => item.total_views);
    var yearlySearches = topYearly.map(item => item.total_searches);

    var options = {
        chart: {
            type: 'line',
            height: '100%',
            width: '100%',
            background: '#fff',
            foreColor: '#636c81',
            toolbar: {
                show: true,
                offsetX: 0,
                offsetY: 0,
            },
            responsive: [{
                breakpoint: 1024,
                options: {
                    chart: {
                        height: 400
                    },
                    legend: {
                        position: 'top'
                    },
                    xaxis: {
                        labels: {
                            rotate: -45
                        }
                    }
                }
            }, {
                breakpoint: 768,
                options: {
                    chart: {
                        height: 300
                    },
                    legend: {
                        position: 'bottom'
                    },
                    xaxis: {
                        labels: {
                            rotate: -30
                        }
                    }
                }
            }, {
                breakpoint: 480,
                options: {
                    chart: {
                        height: 250
                    },
                    legend: {
                        position: 'bottom'
                    },
                    xaxis: {
                        labels: {
                            rotate: -30
                        }
                    }
                }
            }]
        },
        series: [
            {
                name: 'Top 5 Searches',
                type: 'bar',
                data: searchCounts
            },
            {
                name: 'Top 10 Most Viewed',
                type: 'bar',
                data: viewCounts
            },
            {
                name: 'Top 5 Most Viewed and Searched per Year',
                type: 'line',
                data: yearlyViews
            },
            {
                name: 'Top 5 Most Searched per Year',
                type: 'line',
                data: yearlySearches
            }
        ],
        xaxis: {
            categories: [...yearlyTitles, ...searchTitles, ...viewedTitles],
            labels: {
                style: {
                    fontSize: '8px'
                }
            }
        },
        yaxis: [
            {
                title: {
                    text: 'Counts'
                }
            },
            {
                opposite: true,
                title: {
                    text: 'Views / Searches'
                }
            }
        ],
        legend: {
            position: 'top',
            horizontalAlign: 'center'
        },
        colors: ['#FF4560', '#00E396', '#008FFB', '#FF9800'],
        dataLabels: {
            enabled: true
        },
        title: {
            text: 'Most Searched, Highest Views, and Yearly Data',
            align: 'center'
        }
    };

    var chart = new ApexCharts(document.querySelector("#mixedChart"), options);
    chart.render();
</script>

    <!-- Bootstrap JS and dependencies (Optional, if not already included) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
