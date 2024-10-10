<?php
$user_id = $_settings->userdata('id');
$user_type = $_settings->userdata('user_type');

// Initialize variables with default values
$fullname = $gender = $email = $department = $curriculum = '';

if ($user_type === 'student') {
    $student_user_query = "SELECT s.*, d.name as department, c.name as curriculum, CONCAT(lastname, ', ', firstname, ' ', middlename) as fullname FROM student_list s INNER JOIN department_list d ON s.department_id = d.id INNER JOIN curriculum_list c ON s.curriculum_id = c.id WHERE s.id = '$user_id'";
    $student_user = $conn->query($student_user_query);

    if ($student_user && $student_user->num_rows > 0) {
        $user_data = $student_user->fetch_assoc();

        // Assign values to variables
        $fullname = $user_data['fullname'];
        $gender = $user_data['gender'];
        $student_id = $user_data['student_id'];
        $department = $user_data['department'];
        $curriculum = $user_data['curriculum'];
    }
} elseif ($user_type === 'outsider') {
    $outsider_user_query = "SELECT s.*, CONCAT(lastname, ', ', firstname, ' ', middlename) as fullname FROM outsiders_list s WHERE s.id = '$user_id'";
    $outsider_user = $conn->query($outsider_user_query);

    if ($outsider_user && $outsider_user->num_rows > 0) {
        $user_data = $outsider_user->fetch_assoc();

        // Assign values to variables
        $fullname = $user_data['fullname'];
        $gender = $user_data['gender'];
        $student_id = $user_data['student_id'];
    }
}

foreach ($user_data as $k => $v) {
    $$k = $v;
}
?>
<style>
    .student-img{
		object-fit:scale-down;
		object-position:center center;
        height:200px;
        width:200px;
	}
</style>
<div class="content py-4">
    <div class="card card-outline card-success shadow rounded-0">
        <div class="card-header rounded-0">
            <h5 class="card-title">Your Information:</h5>
            <div class="card-tools">
                <a href="./?page=my_archives" class="btn btn-default bg-success btn-flat"><i class="fa fa-archive" ></i> My Archives</a>
                <a href="./?page=manage_account" class="btn btn-default bg-warning btn-flat" style="background-color: #114e3f;"><i class="fa fa-edit"></i> Update Account</a>
            </div>
        </div>
        <div class="card-body rounded-0">
            <div class="container-fluid">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-4 col-sm-12">
                            <center>
                                <img src="<?= validate_image($avatar) ?>" alt="Student Image" class="img-fluid student-img bg-gradient-dark border">
                            </center>
                        </div>
                        <div class="col-lg-8 col-sm-12">
                            <dl>
                                <dt class="text-navy">Student Name:</dt>
                                <dd class="pl-4"><?= ucwords($fullname) ?></dd>
                                <dt class="text-navy">User Type:</dt>
                                <dd class="pl-4"><?= ucwords($user_type) ?></dd>
                                <dt class="text-navy">Gender:</dt>
                                <dd class="pl-4"><?= ucwords($gender) ?></dd>
                                <dt class="text-navy">Student ID:</dt>
                                <dd class="pl-4"><?= $student_id ?></dd>
                                <?php if ($user_type === 'student') : ?>
                                    <dt class="text-navy">Department:</dt>
                                    <dd class="pl-4"><?= ucwords($department) ?></dd>
                                    <dt class="text-navy">Curriculum:</dt>
                                    <dd class="pl-4"><?= ucwords($curriculum) ?></dd>
                                <?php endif; ?>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>