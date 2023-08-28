<nav class="navbar navbar-expand-md navbar-dark bg-dark" style="top: 0;">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo_base_url();?>">SAS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?php echo_base_url();?>">Home</a>
        </li>
      </ul>
    <ul>
        <?php
        if(has_session()){
        ?>
           <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="<?php echo_dashboard(); ?>">My account (<?php echo $_SESSION['user']['email'] ?? "-"; ?>)[<?php echo $_SESSION['role'] ?? "-"; ?>]</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="<?php echo_base_url();?>/views/auth/logout.php">Logout</a>
                </li>
            </ul> 
            
            <!-- TODO: Extra navs based on role -->
        <?php
        } else {
        ?>
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?php echo_base_url();?>/views/auth/login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?php echo_base_url();?>/views/auth/student_signup.php">Signup (Student)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?php echo_base_url();?>/views/auth/advisor_signup.php">Signup (Advisor)</a>
                </li>
            </ul> 
        <?php 
        }
        ?>
    </ul>
    </div>
  </div>
</nav>