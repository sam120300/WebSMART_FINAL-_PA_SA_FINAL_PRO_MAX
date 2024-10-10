<?php
require_once('../../config.php');

// Fetch distinct departments and curriculums
$departments_qry = $conn->query("SELECT DISTINCT id, name FROM department_list ORDER BY name ASC");
$curriculums_qry = $conn->query("SELECT DISTINCT id, name FROM curriculum_list ORDER BY name ASC");

// Fetch student details
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];
    $qry = $conn->query("SELECT s.*, d.name as department, c.name as curriculum, CONCAT(lastname, ', ', firstname, ' ', middlename) as fullname, firstname, middlename, lastname FROM student_list s INNER JOIN department_list d ON s.department_id = d.id INNER JOIN curriculum_list c ON s.curriculum_id = c.id WHERE s.id ='{$student_id}'");
    if ($qry->num_rows > 0) {
        $res = $qry->fetch_array();
        foreach ($res as $k => $v) {
            if (!is_numeric($k))
                $$k = $v;
        }
    }
}
?>

<style>
    #uni_modal .modal-footer {
        display: none;
    }
    .student-img {
        object-fit: scale-down;
        object-position: center center;
        border-radius: 50%;
        max-width: 100%;
        height: auto;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .form-control {
        width: 100%;
    }
    select {
        height: auto; /* Adjust height to fit content */
    }
</style>

<div class="container-fluid">
    <form id="update-student-form">
        <div class="row">
            <div class="col-md-4">
                <center>
                    <img src="<?= validate_image($avatar) ?>" alt="Student Image" class="img-fluid student-img bg-gradient-dark border">
                </center>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <label for="firstname" class="text-navy">First Name:</label>
                    <input type="text" id="firstname" name="firstname" class="form-control" value="<?= ucwords($firstname) ?>"<?php if ($_settings->userdata('type') == 3){ echo "disabled"; } ?>>
                </div>

                <div class="form-group">
                    <label for="middlename" class="text-navy">Middle Name:</label>
                    <input type="text" id="middlename" name="middlename" class="form-control" value="<?= ucwords($middlename) ?>" <?php if ($_settings->userdata('type') == 3){ echo "disabled"; } ?>>
                </div>

                <div class="form-group">
                    <label for="lastname" class="text-navy">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" class="form-control" value="<?= ucwords($lastname) ?>" <?php if ($_settings->userdata('type') == 3){ echo "disabled"; } ?>>
                </div>

                <div class="form-group">
                    <label for="gender" class="text-navy">Gender:</label>
                    <select id="gender" name="gender" class="form-control" <?php if ($_settings->userdata('type') == 3){ echo "disabled"; } ?>>
                        <option value="Male" <?= $gender == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= $gender == 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="student_id" class="text-navy">Student ID:</label>
                    <input type="text" id="student_id" name="student_id" class="form-control" value="<?= $student_id ?>" <?php if ($_settings->userdata('type') == 3){ echo "disabled"; } ?>>
                </div>

                <div class="form-group">
                    <label for="password" class="text-navy">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter new password (leave blank to keep current)" <?php if ($_settings->userdata('type') == 3){ echo "disabled"; } ?>>
                </div>

                <div class="form-group">
                    <label for="department" class="text-navy">Department:</label>
                    <select id="department" name="department" class="form-control" <?php if ($_settings->userdata('type') == 3){ echo "disabled"; } ?>>
                        <?php while ($row = $departments_qry->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>" <?= $department_id == $row['id'] ? 'selected' : '' ?>><?= ucwords($row['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="curriculum" class="text-navy">Curriculum:</label>
                    <select id="curriculum" name="curriculum" class="form-control" <?php if ($_settings->userdata('type') == 3){ echo "disabled"; } ?>>
                        <?php while ($row = $curriculums_qry->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>" <?= $curriculum_id == $row['id'] ? 'selected' : '' ?>><?= ucwords($row['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status" class="text-navy">Status:</label>
                    <select id="status" name="status" class="form-control" <?php if ($_settings->userdata('type') == 3){ echo "disabled"; } ?>>
                        <option value="1" <?= $status == 1 ? 'selected' : '' ?>>Verified</option>
                        <option value="0" <?= $status == 0 ? 'selected' : '' ?>>Not Verified</option>
                    </select>
                </div>

                <div class="form-group text-right">
                    <button type="submit" class="btn btn-success" <?php if ($_settings->userdata('type') == 3){ echo "disabled"; } ?>>Save Changes</button>
                    <button class="btn btn-dark" data-dismiss="modal" type="button">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
$(document).ready(function(){
    $('#update-student-form').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: 'students/update_student.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(resp){
                if(resp.status == 'success'){
                    alert_toast("Student data updated successfully.", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000); // Reload after 1 second to ensure the toast appears
                } else {
                    console.error(resp.error);
                    alert_toast("An error occurred: " + resp.error, 'error');
                }
            },
            error: function(xhr, status, error){
                console.error("AJAX Error: " + error);
                alert_toast("An error occurred during the request.", 'error');
            }
        });
    });
});
</script>
