<?php

use App\Models\Meeting;

include_once __DIR__ . "/../shared/header.php";
include_once __DIR__ . "/../shared/nav.php";

if(!has_session()){
    redirect(base_url() . "/views/auth/login.php");
}

only('student');
?>

<?php 
    if(isset($_GET['cancel']) && isset($_GET['meeting_id'])){
        $meeting_orm = $GLOBALS['ORM_MEETING'];
        $meeting = $meeting_orm->where('student_id')->is($_SESSION['user']['id'])
                    ->where('id')->is($_GET['meeting_id']);

        if($meeting){
            $meeting->delete();
            redirect(dashboard());
        }
    }
?>

<?php
  $users_orm = $GLOBALS['ORM_USER'];
  $advisors = $users_orm->where('department_id')->notNull()
  ->join('departments', function($join){
    return $join->on('users.department_id', 'departments.id');
  })->all();
  $advisor_ids = array_map(function($advisor){
    return $advisor->id;
  }, $advisors);

  $tomorrow = new DateTime('tomorrow');
  $errors = [];

  if(isset($_POST['book_meeting'])){
    $validator = validate($_POST, [
      'title' => ['string', 'required'],
      'description' => ['nullable', 'string', 'required'],
      'date' => ['date:Y-m-d\TH:i', 'required', sprintf("after:%s", $tomorrow->format('Y-m-d 08:00'))],
      'advisor' => ['required', sprintf("in:%s", implode(",", $advisor_ids))]
    ], [
        'advisor' => 'Invalid advisor selected',
    ]);

    $errors = $validator['errors'];
    $validated_data = $validator['data'];

    if(count($errors) == 0){
        $date =  str_replace("T", " ", $validated_data['date']);
        $date = date_create_from_format( "Y-m-d H:i", $date);

        $meeting = $GLOBALS['ORM']->create(Meeting::class);
        $meeting->title = $validated_data['title'];
        $meeting->description = $validated_data['description'];
        $meeting->scheduled_on = $date;
        $meeting->student_id = $_SESSION['user']['id'];
        $meeting->advisor_id = $validated_data['advisor'];

        if($GLOBALS['ORM']->save($meeting)){
            redirect(dashboard());
        }

      $errors['general'] = "Something went wrong please try again!";
    }
  }

?>

<div class="col-lg-6 mx-auto p-3 py-md-5">
    <main class="form-signin mx-auto">
        <form method="POST" action="<?php echo_current_url();?>">
            <h1 class="h3 mb-3 fw-normal">Book Meeting</h1>

            <?php 
                if(isset($errors['general'])){
            ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?php echo $errors['general']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php } ?>

            <div class="mb-3">
                <label for="title">Title</label>
                <input type="text" class="form-control <?php echo_is_invalid($errors, 'title'); ?>" id="title" placeholder="Meeting Title" name="title" value="<?php old('title');?>">
                <?php echo_error($errors, 'title'); ?>
            </div>

            <div class="mb-3">
                <label for="advisor">Advisor</label>
                <select class="form-select py-2 <?php echo_is_invalid($errors, 'advisor'); ?>" id="advisor" name="advisor">
                    <!-- TODO: dynamically load this -->
                    <option selected>Choose Advisor</option>

                    <?php
                        foreach($advisors as $advisor){
                            ?>
                        <option <?php echo isset($_POST['advisor']) && $_POST['advisor'] == $advisor->id ? "selected" : "" ?> 
                         value="<?php echo $advisor->id;?>"><?php echo $advisor->email; ?> (<?php echo $advisor->name; ?>)</option>
                            <?php
                        }
                    ?>
                </select>

                <?php echo_error($errors, 'advisor'); ?>
            </div>

            <div class="mb-3">
                <label for="title">Date</label>
                <input type="datetime-local" class="form-control <?php echo_is_invalid($errors, 'date'); ?>" id="date" placeholder="Meeting Date" name="date" min="<?php echo $tomorrow->format('Y-m-d 08:00'); ?>" value="<?php old('date');?>">
                <?php echo_error($errors, 'date'); ?>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description....</label>
                <textarea class="form-control <?php echo_is_invalid($errors, 'description'); ?>" id="description" rows="3" name="description"><?php old('description');?></textarea>
                <?php echo_error($errors, 'description'); ?>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit" name="book_meeting">Book Meeting</button>
        </form>
    </main>
</div>

<?php
include_once __DIR__ . "/../shared/footer.php";
?>