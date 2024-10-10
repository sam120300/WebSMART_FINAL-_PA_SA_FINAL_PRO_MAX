<?php require_once('./config.php') ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">

<?php require_once('inc/header.php') ?>

<body class="hold-transition ">
  <script>
    start_loader()
  </script>

  <style>
    html,
    body {
      height: calc(100%) !important;
      width: calc(100%) !important;
    }

    body {
      background-image: url("<?php echo validate_image($_settings->info('cover')) ?>");
      background-size: cover;
      background-repeat: no-repeat;
    }

    .login-title {
      text-shadow: 2px 2px black
    }

    #login {
      direction: rtl
    }

    #login>* {
      direction: ltr
    }

    #logo-img {
      height: 350px;
      width: 350px;
      object-fit: scale-up;
      object-position: center center;
      border-radius: 100%;
    }
  </style>

  <div class="h-100 d-flex  align-items-center w-100" id="login">
    <div class="col-7 h-100 d-flex align-items-center justify-content-center">
      <div class="w-100">
        <center><img src="./uploads/payment.png"></center>
      </div>
    </div>

    <div class="col-5 h-100 bg-gradient " style="background-color: light-orange; position: relative; left: 100px;">
      <div class="w-100 d-flex justify-content-center align-items-center h-100 text-navy">
        <div class="card card-outline card-success rounded-0 shadow col-lg-10 col-md-10 col-sm-5">
          <div class="card-header">
            <h5 class="card-title text-center text-dark"><b>Payment Form</b></h5>
          </div>

          <div class="card-body">
            <form action="" id="registration-form">
              <input type="hidden" name="id">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <input type="text" name="firstname" id="firstname" autofocus placeholder="Firstname" class="form-control form-control-border" required>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <input type="text" name="lastname" id="lastname" placeholder="Lastname" class="form-control form-control-border" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <input type="email" name="email" id="email" placeholder="Email" class="form-control form-control-border" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <span class="text-navy"><small>Payment Method</small></span>
                    <select id="payment_method" name="payment_method"  class="form-control form-control-border select2" data-placeholder="Select Payment Method" required>
                      <option value="" disabled selected>Select Payment Method</option>
                      <option value="GCash">Onsite</option>
                      <option value="Paypal">Gcash</option>
                      <option value="Maya">Maya</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <input type="text" name="refno" id="refno" placeholder="Reference No." class="form-control form-control-border" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <span class="text-navy"><small>Date of Payment</small></span>
                    <input type="date" id="paymentdate" name="payment_date" class="form-control form-control-border" required>
                  </div>
                </div>
              </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                    <label for="img" class="control-label text-muted">Receipt Screenshot</label>
                    <input type="file" id="img" name="img" class="form-control form-control-border" accept="image/png,image/jpeg">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                    <label for="img" class="control-label text-muted">Pay amount</label>
                    <input type="text" id="amount" name="amount" class="form-control form-control-border">
                    </div>
                </div>
            </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group text-right">
                    <button class="btn btn-default bg-success btn-flat">Submit Payment</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- Select2 -->
  <script src="<?php echo base_url ?>plugins/select2/js/select2.full.min.js"></script>
  <script>
    $(document).ready(function () {
    var el = $('<div class="pop-msg alert"></div>');
      end_loader();
      $('.select2').select2({
        width: "100%"
      })

      $('#registration-form').submit(function (e) {
    e.preventDefault();
    var _this = $(this);
    $(".pop-msg").remove();

    start_loader();

    var formData = new FormData(this);

    $.ajax({
        url: _base_url_ + "classes/Users.php?f=save_payment",
        method: 'POST',
        data: formData,
        dataType: 'json',
        contentType: false,
        processData: false,
        cache: false,
        success: function (resp) {
            location.href = "./outsiders.php";
        },
        error: function (err) {
            console.log(err);
            el.text("An error occurred while saving the data");
            el.addClass("alert-danger");
            _this.prepend(el);
            el.show('slow');
            end_loader();
        },
    });

});
    });
  </script>
</body>

</html>
