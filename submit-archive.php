<?php
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * FROM `archive_list` WHERE id = '{$_GET['id']}'");
    if ($qry->num_rows) {
        foreach ($qry->fetch_array() as $k => $v) {
            if (!is_numeric($k))
                $$k = $v;
        }
    }
    if (isset($student_id)) {
        if ($student_id != $_settings->userdata('id')) {
            echo "<script> alert('You don\'t have access to this page'); location.replace('./'); </script>";
        }
    }
}
?>
<style>
    .banner-img {
        object-fit: scale-down;
        object-position: center center;
        height: 30vh;
        width: calc(100%);
    }
</style>
<?php if(!isset($_POST['next']) && $_settings->userdata('type') != 1) {?>
<div class="content py-4 mx-auto col-12 col-sm-12 col-md-4 col-lg-4">
    <div class="card card-outline card-success shadow rounded">
        <div class="card-header rounded">
            <h5 class="card-title">Select submission</h5>
        </div>
        <div class="card-body rounded">
            <div class="container">
                <form action="./?page=submit-archive" method="POST">
                    <div class="row">
                        <select class="form-select mb-3" name="type" id="" required>
                            <option value="" selected disabled>Please select type...</option>
                            <option value="1">Projects / Thesis</option>
                            <option value="2">Forms</option>
                        </select>
                        <button type="submit" name="next" class="btn btn-success">Next</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php }?>

<?php if((isset($_POST['next']) && $_POST['type'] == 1) || $_settings->userdata('type') == 1) { ?>
<div class="content py-4">
    <div class="card card-outline card-success shadow rounded">
        <div class="card-header rounded">
            <h5 class="card-title"><?= isset($id) ? "Update Archive-{$archive_code} Details" : "Submit Project" ?></h5>
        </div>
        <div class="card-body rounded">
            <div class="container-fluid">
                <form action="" id="archive-form" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= isset($id) ? $id : "" ?>">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="title" class="control-label text-navy">Project Title</label>
                                <input type="text" name="title" id="title" autofocus placeholder="Project Title" class="form-control form-control-border" value="<?= isset($title) ? $title : "" ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="research_type" class="control-label text-navy">Research Type</label>
                                <select name="type" id="research_type" class="form-control form-control-border" required>
                                    <option value="" disabled selected>Please Select</option>
                                    <?php 
                                    $research_type = $conn->query("SELECT * FROM `research_type` ORDER BY `type` ASC");
                                    while ($row = $research_type->fetch_assoc()) {
                                        $row['type'] = ucwords($row['type']);
                                        echo "<option value='{$row['id']}'>{$row['type']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="reference_style" class="control-label text-navy">Reference Style</label>
                                <select name="style" id="reference_style" class="form-control form-control-border" required>
                                    <option value="" disabled selected>Please Select</option>
                                    <?php 
                                    $reference_style = $conn->query("SELECT * FROM `reference_style`ORDER BY `style` ASC");
                                    while ($row = $reference_style->fetch_assoc()) {
                                        $row['style'] = ucwords($row['style']);
                                        echo "<option value='{$row['id']}'>{$row['style']}</option>";
                                    } 
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="year" class="control-label text-navy">Archive Year</label>
                                <select name="year" id="year" class="form-control form-control-border" required>
                                    <?php
                                    $currentYear = date("Y");
                                    for ($year = $currentYear; $year >= ($currentYear - 20); $year--) {
                                        echo '<option value="' . $year . '"';
                                        if (isset($archive_year) && $archive_year == $year) {
                                            echo ' selected';
                                        }
                                        echo '>' . $year . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="curriculum_id" class="control-label text-navy">Archive Curriculum</label>
                                <select name="curriculum_id" id="curriculum_id" class="form-control form-control-border" required>
                                    <option value="" disabled selected>Please Select</option>
                                    <?php 
                                    $curriculum = $conn->query("SELECT * FROM `curriculum_list` WHERE status = 1 ORDER BY `name` ASC");
                                    while ($row = $curriculum->fetch_assoc()) {
                                        $row['name'] = ucwords($row['name']);
                                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="members" class="control-label text-navy">Project Members</label>
                                <textarea rows="3" name="members" id="members" placeholder="Members" class="form-control form-control-border summernote-list-only" required><?= isset($members) ? html_entity_decode($members) : "" ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="abstract_pdf" class="control-label text-muted">Project Abstract (PDF File Only)</label>
                                <input type="file" id="abstract_pdf" name="abstract_pdf" class="form-control form-control-border" accept="application/pdf" <?= !isset($id) ? "required" : "" ?>>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="document_pdf" class="control-label text-muted">Project Document (PDF File Only)</label>
                                <input type="file" id="document_pdf" name="document_pdf" class="form-control form-control-border" accept="application/pdf" <?= !isset($id) ? "required" : "" ?>>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group text-center">
                            <button class="btn btn-default bg-success btn-flat">Submit</button>
                                <a href="./?page=submit-archive" class="btn btn-light border btn-flat">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form> 
            </div>
        </div>
    </div>
</div>
<?php }?>

<?php if(isset($_POST['next']) && $_POST['type'] == 2) {?>
<div class="content py-4">
    <div class="card card-outline card-success shadow rounded">
        <div class="card-header rounded">
            <h5 class="card-title"><?= "Submit Forms" ?></h5>
        </div>
        <div class="card-body rounded">
            <div class="container-fluid">
                <form action="" id="form-submit" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= isset($id) ? $id : "" ?>">
                    <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <span class="text-navy"><small>Department</small></span>
                                    <select name="department_id" id="department_id" class="form-control form-control-border select2" required>
                                        <option selected disabled>Choose department...</option>
                                        <?php 
                                        $department = $conn->query("SELECT * FROM `department_list` where status = 1 order by `name` asc");
                                        while($row = $department->fetch_assoc()):
                                        ?>
                                        <option value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="title" class="control-label text-navy">Form Description</label>
                                <input type="text" name="title" id="title" autofocus placeholder="Form Title" class="form-control form-control-border" value="<?= isset($title) ? $title : "" ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="forms_pdf" class="control-label text-muted">Form Document (PDF File Only)</label>
                                <input type="file" id="forms_pdf" name="forms_pdf" class="form-control form-control-border" accept="application/pdf" <?= !isset($id) ? "required" : "" ?>>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group text-center">
                                <button class="btn btn-default bg-success btn-flat">Submit</button>
                                <a href="./?page=submit-archive" class="btn btn-light border btn-flat">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php }?>
<script>
    function displayImg(input, _this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#cimg').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            $('#cimg').attr('src', "<?= validate_image(isset($avatar) ? $avatar : "") ?>");
        }
    }

    $(function(){
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                [ 'style', [ 'style' ] ],
                [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear' ] ],
                [ 'fontname', [ 'fontname' ] ],
                [ 'fontsize', [ 'fontsize' ] ],
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
                [ 'table', [ 'table' ] ],
                [ 'insert', [ 'link', 'picture' ] ],
                [ 'view', [ 'undo', 'redo', 'help' ] ]
            ]
        })
        $('.summernote-list-only').summernote({
            height: 200,
            toolbar: [
                [ 'font', [ 'bold', 'italic', 'clear' ] ],
                [ 'fontname', [ 'fontname' ] ],
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul' ] ],
                [ 'view', [ 'undo', 'redo', 'help' ] ]
            ]
        })

        // Archive Form Submit
        $('#archive-form').submit(function(e){
            e.preventDefault();
            var _this = $(this);
            $(".pop-msg").remove();
            var el = $("<div>");
            el.addClass("alert pop-msg my-2");
            el.hide();
            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_archive",
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error: err => {
                    console.log(err);
                    el.text("An error occurred while saving the data");
                    el.addClass("alert-danger");
                    _this.prepend(el);
                    el.show('slow');
                    end_loader();
                },
                success: function (resp) {
                    if (resp.status == 'success') {
                        location.href = "./?page=view_archive&id=" + resp.id;
                    } else if (!!resp.msg) {
                        el.text(resp.msg);
                        el.addClass("alert-danger");
                        _this.prepend(el);
                        el.show('show');
                    } else {
                        el.text("An error occurred while saving the data");
                        el.addClass("alert-danger");
                        _this.prepend(el);
                        el.show('show');
                    }
                    end_loader();
                    $('html, body').animate({scrollTop: 0}, 'fast');
                }
            });
        });

        $('#form-submit').submit(function(e){
            e.preventDefault();
            var _this = $(this);
            $(".pop-msg").remove();
            var el = $("<div>");
            el.addClass("alert pop-msg my-2");
            el.hide();
            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_form",
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error: err => {
                    console.log(err);
                    el.text("An error occurred while saving the data");
                    el.addClass("alert-danger");
                    _this.prepend(el);
                    el.show('slow');
                    end_loader();
                },
                success: function (resp) {
                    if (resp.status == 'success') {
                        location.href = "./?page=view_form&id=" + resp.id;
                    } else if (!!resp.msg) {
                        el.text(resp.msg);
                        el.addClass("alert-danger");
                        _this.prepend(el);
                        el.show('show');
                    } else {
                        el.text("An error occurred while saving the data");
                        el.addClass("alert-danger");
                        _this.prepend(el);
                        el.show('show');
                    }
                    end_loader();
                    $('html, body').animate({scrollTop: 0}, 'fast');
                }
            });
        });

    })
</script>
