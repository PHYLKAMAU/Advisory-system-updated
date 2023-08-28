<?php

use App\Models\Department;

include_once __DIR__ . "/../shared/header.php";
include_once __DIR__ . "/../shared/nav.php";

if(!has_session()){
    redirect(base_url() . "/views/auth/login.php");
}

only('admin');
?>

<?php 
    if(isset($_GET['delete']) && isset($_GET['department_id'])){
        $department_orm = $GLOBALS['ORM_DEPARTMENT'];
        $department = $department_orm->where('id')->is($_GET['department_id']);

        if($department){
            $department->delete();
            redirect(dashboard());
        }
    }
?>

<?php
  $errors = [];

  if(isset($_POST['book_department'])){
    $validator = validate($_POST, [
      'name' => ['string', 'required', 'unique:departments,name'],
    ]);

    $errors = $validator['errors'];
    $validated_data = $validator['data'];

    if(count($errors) == 0){
        $department = $GLOBALS['ORM']->create(Department::class);
        $department->name = $validated_data['name'];

        if($GLOBALS['ORM']->save($department)){
            redirect(dashboard());
        }

      $errors['general'] = "Something went wrong please try again!";
    }
  }

?>

<div class="col-lg-6 mx-auto p-3 py-md-5">
    <main class="form-signin mx-auto">
        <form method="POST" action="<?php echo_current_url();?>">
            <h1 class="h3 mb-3 fw-normal">Add department</h1>

            <?php 
                if(isset($errors['general'])){
            ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?php echo $errors['general']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php } ?>

            <div class="mb-3">
                <label for="title">Department Name</label>
                <input type="text" class="form-control <?php echo_is_invalid($errors, 'name'); ?>" id="name" placeholder="department Name" name="name" value="<?php old('name');?>">
                <?php echo_error($errors, 'name'); ?>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit" name="book_department">Add Department</button>
        </form>
    </main>
</div>

<?php
include_once __DIR__ . "/../shared/footer.php";
?>