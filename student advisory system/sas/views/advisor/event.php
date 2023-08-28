<?php

use App\Models\event;

include_once __DIR__ . "/../shared/header.php";
include_once __DIR__ . "/../shared/nav.php";

if(!has_session()){
    redirect(base_url() . "/views/auth/login.php");
}

only('advisor');
?>

<?php 
    if(isset($_GET['cancel']) && isset($_GET['event_id'])){
        $event_orm = $GLOBALS['ORM_EVENT'];
        $event = $event_orm->where('advisor_id')->is($_SESSION['user']['id'])
                    ->where('id')->is($_GET['event_id']);

        if($event){
            $event->delete();
            redirect(dashboard());
        }
    }
?>

<?php
  $tomorrow = new DateTime('tomorrow');
  $errors = [];

  if(isset($_POST['add_event'])){
    $validator = validate($_POST, [
      'title' => ['string', 'required'],
      'description' => ['nullable', 'string', 'required'],
      'date' => ['date:Y-m-d\TH:i', 'required', sprintf("after:%s", $tomorrow->format('Y-m-d 08:00'))],
    ], [
        'advisor' => 'Invalid advisor selected',
    ]);

    $errors = $validator['errors'];
    $validated_data = $validator['data'];

    if(count($errors) == 0){
        $date =  str_replace("T", " ", $validated_data['date']);
        $date = date_create_from_format( "Y-m-d H:i", $date);

        $event = $GLOBALS['ORM']->create(event::class);
        $event->title = $validated_data['title'];
        $event->description = $validated_data['description'];
        $event->scheduled_on = $date;
        $event->advisor_id = $_SESSION['user']['id'];

        if($GLOBALS['ORM']->save($event)){
            redirect(dashboard());
        }

      $errors['general'] = "Something went wrong please try again!";
    }
  }

?>

<div class="col-lg-6 mx-auto p-3 py-md-5">
    <main class="form-signin mx-auto">
        <form method="POST" action="<?php echo_current_url();?>">
            <h1 class="h3 mb-3 fw-normal">Add Event</h1>

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
                <input type="text" class="form-control <?php echo_is_invalid($errors, 'title'); ?>" id="title" placeholder="event Title" name="title" value="<?php old('title');?>">
                <?php echo_error($errors, 'title'); ?>
            </div>

            <div class="mb-3">
                <label for="title">Date</label>
                <input type="datetime-local" class="form-control <?php echo_is_invalid($errors, 'date'); ?>" id="date" placeholder="event Date" name="date" min="<?php echo $tomorrow->format('Y-m-d 08:00'); ?>" value="<?php old('date');?>">
                <?php echo_error($errors, 'date'); ?>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description....</label>
                <textarea class="form-control <?php echo_is_invalid($errors, 'description'); ?>" id="description" rows="3" name="description"><?php old('description');?></textarea>
                <?php echo_error($errors, 'description'); ?>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit" name="add_event">Add Event</button>
        </form>
    </main>
</div>

<?php
include_once __DIR__ . "/../shared/footer.php";
?>