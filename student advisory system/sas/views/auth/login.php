<?php

use Opis\ORM\EntityManager;

include_once __DIR__ . "/../shared/header.php";
include_once __DIR__ . "/../shared/nav.php";

if(has_session()){
  redirect(dashboard());
}
?>

<?php
  // Login logic
  $errors = [];
  if(isset($_POST['login'])){
    $validator = validate($_POST, [
      'email' => ['email', 'required', "exists:users,email"],
      'password' => ['string', 'required']
    ]);

    $errors = $validator['errors'];
    $validated_data = $validator['data'];

    if(count($errors) == 0){
      /** @var EntityManager $user_orm */
      $user_orm = $GLOBALS['ORM_USER'];
      $user = $user_orm->where('email')->is($validated_data['email'])->get();

      if($user){
        $password = $user->password;

        if(password_verify($validated_data['password'], $password)){
          // dd($user);
          // Create session
          session_destroy();
          my_session_start(true);
          $_SESSION['user'] = [
            'email' => $user->email,
            'id' => $user->id,
            'lastaccess' => $user->lastaccess,
            'joined_on' => $user->joined_on,
          ];
          $_SESSION['role'] = $user->role;

          $user->lastaccess = date("Y-m-d H:i:s");
          $GLOBALS['ORM']->save($user);

          redirect(sprintf("%s/views/%s/dashboard.php", base_url(), $_SESSION['role']));
        }

        $errors['email'] = "Incorrect username or password";
      }
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

.form-signin .form-floating:focus-within {
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
    <form method="POST" action="<?php echo_current_url();?>">
        <?php
          if(count($errors)){
              ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  Incorrect username or password
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php
          }
        ?>

        <h1 class="h3 mb-3 fw-normal">Please Log In</h1>

        <div class="form-floating">
        <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email" required value="<?php old('email');?>">
        <label for="email">Email address</label>
        </div>
        <div class="form-floating">
        <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
        <label for="password">Password</label>
        </div>

        <div class="checkbox mb-3">
        <label>
            <input type="checkbox" value="remember-me" name="remember"> Remember me
        </label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit" name="login">Sign In</button>
    </form>
    </main>
</div>

<?php
include_once __DIR__ . "/../shared/footer.php";
?>

