<?php
include_once __DIR__ . "/views/shared/header.php";
include_once __DIR__ . "/views/shared/nav.php";
?>
<div class="col-lg-8 mx-auto p-3 py-md-5">
<div class="container my-5">
    <div class="row p-4 pb-0 pe-lg-0 pt-lg-5 align-items-center rounded-3 border shadow-lg">
      <div class="col-lg-7 p-3 p-lg-5 pt-lg-3">
        <h1 class="display-4 fw-bold lh-1">Student Advisory System (SAS)</h1>
        <p class="lead">
        The motivation for creating an online student academic advisory system is to more efficiently 
        manage and track student academic progress and also to solve the current problems facing students 
        while seeking solutions concerning their academic affairs from administration offices. By creating 
        this system, the problems will be solved.   
        </p>
        <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-4 mb-lg-3">
          <a href="<?php echo_base_url();?>/views/auth/login.php">
            <button type="button" class="btn btn-primary btn-lg px-4 me-md-2 fw-bold">Login Now</button>
          </a>
        </div>
      </div>
      <div class="col-lg-4 offset-lg-1 p-0 overflow-hidden shadow-lg">
          <img class="rounded-lg-2" src="<?php echo_base_url();?>/assets/images/advisory.jpg" alt="" width="720">
      </div>
    </div>
  </div>
</div>

<?php
include_once __DIR__ . "/views/shared/footer.php";
?>
