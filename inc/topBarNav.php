<?php
$loggedIn = ($_settings->userdata('id') > 0);
$zIndex = $loggedIn ? 1399 : 1499;
$user_type = ($_settings->userdata('user_type'));
?>
<style>
  .user-img{
        position: absolute;
        height: 27px;
        width: 27px;
        object-fit: cover;
        left: -7%;
        top: -12%;
  }
  .btn-rounded{
        border-radius: 50px;
  }
  .search-bar-wrapper {
    position: relative;
    z-index: 2; /* Ensure the search bar is above the container but behind other navbar links */
  }
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<!-- Navbar -->
<style>
  #login-nav{
    position:fixed !important;
    top: 0 !important;
    z-index: <?php echo $zIndex; ?>;
    padding: 1em 1.5em !important;
  }
  #top-Nav{
    z-index: 1400;
  }
  .text-sm .layout-navbar-fixed .wrapper .main-header ~ .content-wrapper, .layout-navbar-fixed .wrapper .main-header.text-sm ~ .content-wrapper {
    margin-top: calc(3.6) !important;
    padding-top: calc(5em) !important;
  }
  #loginDropdown{
    position: absolute !important;
    z-index: 1600 !important;
  }
  #top-Nav .navbar-toggler {
  margin-left: auto;
}

</style>
<nav class="w-100 position-fixed" id="login-nav" style="background-color: #114e3f">
  <div class="d-flex justify-content-between w-100 m-auto">
    <div class="mt-auto mb-auto">
      <span class="mr-2 text-white"><i class="fa fa-globe mr-3"></i><?= $_settings->info('contact') ?></span>
    </div>

    <div>
      <?php if ($_settings->userdata('id') > 0): ?>
        <!-- User is logged in -->
         <div class="mt-auto mb-auto">
            <span class="" style="position: relative;">
              <a href="<?= base_url ?>"><i class="text-light fa-solid fa-house fa-xl"></i></a>  
            </span>
            <?php if($_settings->userdata('type') == 2){?>
              <span class="mx-2" style="position: relative;">
                <a href="<?= base_url.'admin/?page=system_info' ?>"><i class="text-light fa-solid fa-gear fa-xl"></i></a>  
              </span>
            <?php }?>
            <span class="mx-2" style="position: relative;">
              <a href="
              <?php if ($_settings->userdata('type') == 2 || $_settings->userdata('type') == null){
                echo "/WebSMART/admin"; 
                } else {

                  echo "/WebSMART"; 
                } 
                ?>
              
              "><img src="<?= validate_image($_settings->userdata('avatar')) ?>" alt="User Avatar" id="student-img-avatar"></a>  </span>
            
            <span class="mx-1" style="position: relative; color:  white;">
                <a class="dropdown-toggle text-light" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                  <span class="mx-2 d-none d-sm-inline" style="color: white; position: relative;">
                    Hello, <?= $_settings->userdata('firstname') ." ".$_settings->userdata('lastname')?>
                </span>
              </a>

                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <?php if(($_settings->userdata('type') != 2) && ($_settings->userdata('type') != null)){?>
                <li><a class="dropdown-item text-center text-primary" href="?page=profile">My Account <i class="fa-solid fa-user"></i></a></li>
                <?php }?>
                <li><a class="dropdown-item text-danger text-center" href="<?= base_url.'classes/Login.php?f=student_logout' ?>">Logout <i class="fa-solid fa-right-from-bracket"></i></a></li>
              </ul>
            </span>
         </div>

      <?php else: ?> 
        <!-- User is not logged in -->
        <div class="d-flex align-items-center" style="position: relative; right: 70px;">
            <span class="mx-2" style="position: relative;">
              <a href="<?= base_url ?>"><i class="text-light fa-solid fa-house fa-xl"></i></a>  
            </span>
          <a href="./register.php" class="mx-3 text-light me-2" style="">Register</a>
          <div class="dropdown">
            <a href="#" class="mx-2 text-light me-2 dropdown-toggle" style="position: relative; top: -12px;" id="loginDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Login As
            </a>
            <div class="dropdown-menu" aria-labelledby="loginDropdown">
              <a class="dropdown-item" href="./login.php">Student</a>
              <a class="dropdown-item" href="./admin">Admin</a>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</nav>
<script>
$(function(){
    $('#search-input').focusout(function(){
        setTimeout(function(){
            $('#suggestions').hide();
        }, 200);
    });

    $('#search-input').on('input', function() {
        var query = $(this).val();
        if(query.length > 0){
            $.ajax({
                url: './suggest.php',
                method: 'GET',
                data: { query: query },
                success: function(response){
                    var suggestions = JSON.parse(response);
                    var suggestionsHtml = '';
                    suggestions.forEach(function(suggestion){
                        suggestionsHtml += '<a href="./?page=projects&q=' + suggestion + '" class="list-group-item list-group-item-action">' + suggestion + '</a>';
                    });
                    $('#suggestions').html(suggestionsHtml).show();
                }
            });
        } else {
            $('#suggestions').hide();
        }
    });

    $('#search-input').keydown(function(e){
        if(e.which == 13){
            location.href = "./?page=projects&q="+encodeURI($(this).val());
        }
    });
});
</script>
