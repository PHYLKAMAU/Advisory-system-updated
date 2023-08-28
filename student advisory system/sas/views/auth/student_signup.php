<?php
include_once __DIR__ . "/../shared/header.php";
include_once __DIR__ . "/../shared/nav.php";
?>

<?php
    use App\Models\User;

  $school_orm = $GLOBALS['ORM_SCHOOL'];
  $schools = $school_orm->all();

  // Login logic
  $errors = [];

  if(isset($_POST['signup'])){
    $validator = validate($_POST, [
      'email' => ['email', 'required', "unique:users,email"],
      'reg_no' => ['string', 'required', "unique:users,registration_number"],
      'password' => ['string', 'required', 'confirmed'],
      'school' => ['integer', 'required', 'exists:schools,id'],
      'password_confirmation' => ['string'],
    ]);

    $errors = $validator['errors'];
    $validated_data = $validator['data'];

    if(count($errors) == 0){
      /** @var User $user */
      $user = $GLOBALS['ORM']->create(User::class);

      $user->email = $validated_data['email'];
      $user->password = $validated_data['password'];
      $user->registration_number = $validated_data['reg_no'];
      $user->role = 'student';
      $user->school_id = $validated_data['school'];
      
      if($GLOBALS['ORM']->save($user)){
        redirect(sprintf("%s/views/auth/login.php", base_url()));
      }

      $errors['general'] = "Something went wrong please try again!";
    }

  }
?>

<style>
    html,
    body {
        height: 100%;
    }

    body {
        display: flex;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
    }

    .form-signin {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: auto;
    }

    .form-signin .checkbox {
        font-weight: 400;
    }

    .form-signin .b-3:focus-within {
        z-index: 2;
    }

    .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
</style>

<div class="col-lg-6 mx-auto p-3 py-md-5">
    <main class="form-signin mx-auto">
        <form method="POST" action="<?php echo_current_url(); ?>">

            <h1 class="h3 mb-3 fw-normal">Student Sign Up</h1>
            <?php 
                if(isset($errors['general'])){
            ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?php echo $errors['general']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php } ?>
            
            <div class="b-3">
                <label for="email">Email address</label>
                <input type="email" class="form-control <?php echo_is_invalid($errors, 'email'); ?>" id="email" name="email" placeholder="name@example.com" value="<?php old('email'); ?> " required>
                
                <?php echo_error($errors, 'email'); ?>
            </div>

            <div class="b-3">
                <label for="reg_no">Registration Number</label>
                <input type="text" class="form-control <?php echo_is_invalid($errors, 'reg_no'); ?>" id="reg_no" name="reg_no" placeholder="AB01/000/000" value="<?php old('reg_no'); ?>" required>
                <?php echo_error($errors, 'reg_no'); ?>
            </div>

            <div class="b-3">
                <label for="school">School</label>
                <select class="form-control form-select py-2 <?php echo_is_invalid($errors, 'school'); ?>" id="school" name="school" required>
                    <!-- TODO: dynamically load this -->
                    <option selected value="">Choose School</option>
                    <?php
                        foreach($schools as $school){
                            ?>
                        <option <?php echo isset($_POST['school']) && $_POST['school'] == $school->id ? "selected" : "" ?> 
                         value="<?php echo $school->id;?>"><?php echo $school->name; ?></option>
                            <?php
                        }
                    ?>
                </select>
            
                <?php echo_error($errors, 'school'); ?>
            </div>

            <div class="b-3">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control <?php echo_is_invalid($errors, 'password'); ?>" id="password" placeholder="Password" required>
            
                <?php echo_error($errors, 'password'); ?>
            </div>

            <div class="b-3">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation" id="confirm_password" placeholder="Confirm Password" required>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit" name="signup">Sign Up</button>
        </form>
    </main>
</div>

<?php
include_once __DIR__ . "/../shared/footer.php";
?>