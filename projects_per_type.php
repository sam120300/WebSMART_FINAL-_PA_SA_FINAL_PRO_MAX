<style>
    #document_field, #abstract_field {
        min-height: 80vh;
    }
    .filters {
        margin-top: -50px;
    }
    .droppy{
        overflow: scroll;
        height: 40vh;
    }
</style>
<?php 
if(isset($_GET['id'])){

    $qry = $conn->query("SELECT * FROM research_type WHERE id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            if(!is_numeric($k)){
                $research_type[$k] = $v;
            }
        }
    }else{
        echo "<script> alert('Unkown Department ID'); location.replace('./') </script>";
    }

}else{
    echo "<script> alert('Department ID is required'); location.replace('./') </script>";
}

?>
<div class="content py-2">
<div class="row filters mt-3">
        <div class="col-md-8 col-sm-12">
            <ul class="nav">
                    <li class="nav-item"><a href="?page=projects" class="nav-link text-dark font-weight-bold">All Projects</a></li>
                <li class="nav-item dropdown">
                    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle <?= isset($page) && $page == 'projects_per_department' ? 'active' : '' ?>" style="color: black; font-weight: 500;">Department</a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow droppy">
                        <?php 
                        $departments = $conn->query("SELECT * FROM department_list WHERE status = 1 ORDER BY `name` ASC");
                        while($row = $departments->fetch_assoc()):
                        ?>
                        <li><a href="./?page=projects_per_department&id=<?= $row['id'] ?>" class="dropdown-item"><?= ucwords($row['name']) ?></a></li>
                        <?php if($departments->num_rows > 1): ?>
                        <li class="dropdown-divider"></li>
                        <?php endif; ?>
                        <?php endwhile; ?>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle <?= isset($page) && $page == 'projects_per_curriculum' ? 'active' : '' ?>" style="color: black; font-weight: 500;">Courses</a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow droppy">
                        <?php 
                        $curriculums = $conn->query("SELECT * FROM curriculum_list WHERE status = 1 ORDER BY `name` ASC");
                        while($row = $curriculums->fetch_assoc()):
                        ?>
                        <li><a href="./?page=projects_per_curriculum&id=<?= $row['id'] ?>" class="dropdown-item"><?= ucwords($row['name']) ?></a></li>
                        <?php if($curriculums->num_rows > 1): ?>
                        <li class="dropdown-divider"></li>
                        <?php endif; ?>
                        <?php endwhile; ?>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a id="dropdownSubMenuYear" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle <?= isset($page) && $page == 'projects_per_year' ? 'active' : '' ?>" style="color: black; font-weight: 500;">Year</a>
                    <ul aria-labelledby="dropdownSubMenuYear" class="dropdown-menu border-0 shadow droppy">
                        <?php
                        $currentYear = date("Y");
                        for ($year = $currentYear; $year >= ($currentYear - 10); $year--) {
                            echo '<li><a href="./?page=projects_per_year&year=' . $year . '" class="dropdown-item">' . $year . '</a></li>';
                        }
                        ?>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle <?= isset($page) && $page == 'projects_per_type' ? 'active' : '' ?>" style="color: black; font-weight: 500;">Research Type</a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow droppy">
                        <?php 
                        $curriculums = $conn->query("SELECT * FROM research_type ORDER BY `type` ASC");
                        while($row = $curriculums->fetch_assoc()):
                        ?>
                        <li><a href="./?page=projects_per_type&id=<?= $row['id'] ?>" class="dropdown-item"><?= ucwords($row['type']) ?></a></li>
                        <?php if($curriculums->num_rows > 1): ?>
                        <li class="dropdown-divider"></li>
                        <?php endif; ?>
                        <?php endwhile; ?>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle <?= isset($page) && $page == 'projects_per_style' ? 'active' : '' ?>" style="color: black; font-weight: 500;">Reference Style</a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow droppy">
                        <?php 
                        $curriculums = $conn->query("SELECT * FROM reference_style ORDER BY `style` ASC");
                        while($row = $curriculums->fetch_assoc()):
                        ?>
                        <li><a href="./?page=projects_per_style&id=<?= $row['id'] ?>" class="dropdown-item"><?= ucwords($row['style']) ?></a></li>
                        <?php if($curriculums->num_rows > 1): ?>
                        <li class="dropdown-divider"></li>
                        <?php endif; ?>
                        <?php endwhile; ?>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="col-md-4 col-sm-12">
            <form class="w-100" id="search-form" method="GET" action="./">
                <input type="hidden" name="page" value="projects">
                <input style="height: 50px;" type="search" id="search-input" name="q" class="form-control rounded-5" required placeholder="Search..." value="<?= isset($_GET['q']) ? $_GET['q'] : '' ?>">
                <div id="suggestions-box" style="position: relative;">
                    <ul id="suggestions-list" class="list-group" style="position: absolute; z-index: 1000; width: 100%;">
                    </ul>
                </div>
            </form>
        </div>

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

    </div>
    <div class="col-12 mt-3">
        <div class="card card-outline card-success shadow rounded-0">
            <div class="card-body rounded-0">
                <h2>Archive List of <?= isset($research_type['id']) ? $research_type['type'] : "" ?> </h2>
                <hr class="bg-navy">
                <?php 
                $id = isset($_GET['id']) ? $_GET['id'] : '';
                $limit = 10;
                $page = isset($_GET['p'])? $_GET['p'] : 1; 
                $offset = 10 * ($page - 1);
                $paginate = " limit {$limit} offset {$offset}";
                $wheredid = " where type = '{$id}' ";
                $count_all = $conn->query("SELECT * FROM archive_list {$wheredid} AND status = 1")->num_rows;    
                $pages = ceil($count_all/$limit);
                $archives = $conn->query("
                    SELECT a.*, a.id as archive_id, c.department_id, d.name AS department_name, res.type, res.id
                    FROM archive_list a
                    JOIN curriculum_list c ON a.curriculum_id = c.id
                    JOIN department_list d ON c.department_id = d.id
                    JOIN research_type res ON a.type = res.id
                    WHERE a.status = 1 AND a.type = {$id}
                    ORDER BY a.views_count DESC, UNIX_TIMESTAMP(a.date_created) DESC
                    {$paginate}
                ");

                ?>
                <div class="list-group">
                    <?php 
                    while($row = $archives->fetch_assoc()):
                        $row['abstract'] = strip_tags(html_entity_decode($row['abstract']));
                        $dept_id = $row['department_id'];
                    ?>
                    <a href="./?page=view_archive&id=<?= $row['archive_id'] ?>" class="text-decoration-none text-dark list-group-item list-group-item-action">
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-12 text-center">
                            <img style="width: 100px;" src="<?php
                                
                                if ($dept_id == 11){
                                    echo 'assets/depts/11.png';
                                } else if ($dept_id == 12){
                                    echo 'assets/depts/12.png';
                                } else if ($dept_id == 13){
                                    echo 'assets/depts/13.png';
                                } else if ($dept_id == 14){
                                    echo 'assets/depts/14.png';
                                } else if ($dept_id == 15){
                                    echo 'assets/depts/15.png';
                                } else if ($dept_id == 16){
                                    echo 'assets/depts/16.png';
                                } else if ($dept_id == 17){
                                    echo 'assets/depts/17.png';
                                } else if ($dept_id == 18) {
                                    echo 'assets/depts/18.jpg';
                                }
                                
                                ?>" class="banner-img img-fluid bg-gradient-light" alt="Banner Image">
                            </div>
                            <div class="col-lg-8 col-md-7 col-sm-12">
                                <h3 class="text-navy"><b><?php echo $row['title'] ?></b></h3>
                                <small class="text-muted">By <b class="text-info"><?= !empty($row['submitted_by']) ? $row['submitted_by'] : "N/A" ?></b></small>
                                <small class="text-muted">| Views: <b class="text-info"><?= $row['views_count'] ?></b></small>    
                            </div>
                        </div>
                    </a>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="card-footer clearfix rounded-0">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-6"><span class="text-muted">Display Items: <?= $archives->num_rows ?></span></div>
                        <div class="col-md-6">
                            <ul class="pagination pagination-sm m-0 float-right">
                                <li class="page-item"><a class="page-link" href="./?page=projects_per_type&id=<?= $id ?>&p=<?= $page - 1 ?>" <?= $page == 1 ? 'disabled' : '' ?>>«</a></li>
                                <?php for($i = 1; $i<= $pages; $i++): ?>
                                <li class="page-item"><a class="page-link <?= $page == $i ? 'active' : '' ?>" href="./?page=projects_per_type&id=<?= $id ?>&p=<?= $i ?>"><?= $i ?></a></li>
                                <?php endfor; ?>
                                <li class="page-item"><a class="page-link" href="./?page=projects_per_type&id=<?= $id ?>&p=<?= $page + 1 ?>" <?= $page == $pages || $pages <= 1 ? 'disabled' : '' ?>>»</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
