<?php require_once('./config.php'); ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" href="logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRAlx1Th3w+j9H4bDgv1X5d6CTODs3zJAP0pyt10" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            overflow-x: hidden;
        }
        #header {
            height: 100vh;
            width: 100%;
            position: relative;
            margin-top: -50px;
        }
        #header:before {
            content: "";
            position: absolute;
            height: 100%;
            width: 100%;
            background-image: url(<?= validate_image($_settings->info("cover")) ?>);
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }
        #header > div {
            position: absolute;
            height: 100%;
            width: 100%;
            z-index: 2;
        }
        #top-Nav a.nav-link.active {
            color: #001f3f;
            font-weight: 900;
            position: relative;
        }
        #top-Nav a.nav-link.active:before {
            content: "";
            position: absolute;
            border-bottom: 2px solid #001f3f;
            width: 33.33%;
            left: 33.33%;
            bottom: 0;
        }
        .explore:hover {
            transform: scale(1.2);
            box-shadow: 5px 5px 5px #000;
        }
        .navbtn:hover{
           transform: scale(1.1);
        }
        /* Responsive Typography */
        h1 {
            font-size: calc(2rem + 2vw);
        }
        
    </style>
</head>
<body class="layout-top-nav layout-fixed layout-navbar-fixed" style="height: auto;">
    <?php require_once('inc/header.php') ?>
    <div class="wrapper">
        <?php $page = isset($_GET['page']) ? $_GET['page'] : 'home'; ?>
        <?php require_once('inc/topBarNav.php') ?>
        <?php if ($_settings->chk_flashdata('success')): ?>
        <script>
            alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success');
        </script>
        <?php endif; ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper pt-5">
            <?php if ($page == "home" || $page == "about_us"): ?>
            <div id="header" class="shadow mb-4">
                <div class="d-flex justify-content-center h-100 w-100 align-items-center flex-column text-center px-3">
                    <h1 class="text-white fw-bold" style="text-shadow: 5px 5px 5px black; font-size: 64px;"><?php echo $_settings->info('name') ?></h1>
                    <form class="col-12 col-md-8 col-lg-6 mt-3" id="search-form" method="GET" action="./">
                        <input type="hidden" name="page" value="projects">
                        <input style="height: 50px;" type="search" id="search-input" name="q" class="form-control rounded-5" required placeholder="Search..." value="<?= isset($_GET['q']) ? $_GET['q'] : '' ?>">
                        <div id="suggestions-box" style="position: relative;">
                            <ul id="suggestions-list" class="list-group text-start" style="position: absolute; z-index: 1000; width: 100%;"></ul>
                        </div>
                    </form>
                    <script>
    document.getElementById('suggestions-list').style.display = 'none';
    document.getElementById('search-input').addEventListener('input', function () {
        let query = this.value.trim();

        if (query.length > 0) {
            fetch(`search_suggestions.php?q=${encodeURIComponent(query)}`)
                .then(response => response.text())
                .then(data => {
                    const suggestionsList = document.getElementById('suggestions-list');
                    suggestionsList.innerHTML = data.trim() !== '' ? data : '';
                    suggestionsList.style.display = data.trim() !== '' ? 'block' : 'none';

                    // Update search count
                    fetch('update_search_count.php');
                });
        } else {
            document.getElementById('suggestions-list').innerHTML = '';
            document.getElementById('suggestions-list').style.display = 'none';
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target && e.target.matches('.suggestion-item')) {
            // Extract the title from the data-title attribute
            const selectedTitle = e.target.getAttribute('data-title');
            document.getElementById('search-input').value = selectedTitle;

            // Automatically submit the form to trigger the search
            document.getElementById('search-form').submit();
        }
    });

    document.addEventListener('click', function (e) {
        if (!document.getElementById('suggestions-box').contains(e.target)) {
            document.getElementById('suggestions-list').innerHTML = '';
            document.getElementById('suggestions-list').style.display = 'none';
        }
    });
</script>


<div class="row w-50 mt-5 g-2">
    <?php if ($_settings->userdata('id') > 0): ?>
        <a href="?page=projects" class="col-6 col-sm-6 col-md-3 navbtn btn btn-transparent text-light font-weight-bold" data-bs-toggle="tooltip" title="Explore all projects from different fields">
            <img src="uploads/project-management.png" alt="" style="width: 75px;"><br>PROJECTS
        </a>
        <a href="?page=forms" class="col-6 col-sm-6 col-md-3 navbtn btn btn-transparent text-light font-weight-bold" data-bs-toggle="tooltip" title="Browse all available forms">
            <img src="uploads/form.png" alt="" style="width: 75px;"><br>FORMS
        </a>
        <a href="?page=submit-archive" class="col-6 col-sm-6 col-md-3 navbtn btn btn-transparent text-light font-weight-bold" data-bs-toggle="tooltip" title="Submit documents or project archives">
            <img src="uploads/submit.png" alt="" style="width: 75px;"><br>SUBMIT
        </a>
        <a href="?page=about" class="col-6 col-sm-6 col-md-3 navbtn btn btn-transparent text-light font-weight-bold" data-bs-toggle="tooltip" title="Find out more about us and what we do">
            <img src="uploads/question-mark.png" alt="" style="width: 75px;"><br>ABOUT&nbspUS
        </a>
    <?php else: ?>
        <a href="?page=projects" class="col-6 col-sm-6 navbtn btn btn-transparent text-light font-weight-bold" data-bs-toggle="tooltip" title="Explore all projects from different fields">
            <img src="uploads/project-management.png" alt="" style="width: 75px;"><br>PROJECTS
        </a>
        <a href="?page=about" class="col-6 col-sm-6 navbtn btn btn-transparent text-light font-weight-bold" data-bs-toggle="tooltip" title="Find out more about us and what we do">
            <img src="uploads/question-mark.png" alt="" style="width: 75px;"><br>ABOUT&nbspUS
        </a>
    <?php endif; ?>
</div>
<script>
    // Initialize all tooltips on the page
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>


                </div>
            </div>
            <?php endif; ?>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php 
                        if (!file_exists($page.".php") && !is_dir($page)) {
                            include '404.html';
                        } else {
                            if (is_dir($page)) {
                                include $page.'/index.php';
                            } else {
                                include $page.'.php';
                            }
                        }
                    ?>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php require_once('inc/footer.php') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
