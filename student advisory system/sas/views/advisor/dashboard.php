<?php
include_once __DIR__ . "/../shared/header.php";
include_once __DIR__ . "/../shared/nav.php";

if(!has_session()){
  redirect(base_url() . "/views/auth/login.php");
}

only('advisor');
?>

<?php
  $meetings_orm = $GLOBALS['ORM_MEETING'];
  $meetings = $meetings_orm->where('advisor_id')->is($_SESSION['user']['id'])
    ->where('scheduled_on')->greaterThan(date("Y-m-d H:i:s"))
    ->join('users', function($join){
        $join->on('meetings.student_id', 'users.id');
    })
    ->all();

  $events_orm = $GLOBALS['ORM_EVENT'];
  $events= $events_orm->where('scheduled_on')->greaterThan(date("Y-m-d H:i:s"))
    ->join('users', function($join){
        $join->on('events.advisor_id', 'users.id');
    })
    ->all();
?>

<style>
.b-example-divider {
  height: 3rem;
  width: 100%;
  background-color: rgba(0, 0, 0, .1);
  border: solid rgba(0, 0, 0, .15);
  border-width: 1px 0;
  box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
}

.bi {
  vertical-align: -.125em;
  fill: currentColor;
}

.feature-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 4rem;
  height: 4rem;
  margin-bottom: 1rem;
  font-size: 2rem;
  color: #fff;
  border-radius: .75rem;
}

.icon-link {
  display: inline-flex;
  align-items: center;
}
.icon-link > .bi {
  margin-top: .125rem;
  margin-left: .125rem;
  transition: transform .25s ease-in-out;
  fill: currentColor;
}
.icon-link:hover > .bi {
  transform: translate(.25rem);
}

.icon-square {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 3rem;
  height: 3rem;
  font-size: 1.5rem;
  border-radius: .75rem;
}

.rounded-4 { border-radius: .5rem; }
.rounded-5 { border-radius: 1rem; }

.text-shadow-1 { text-shadow: 0 .125rem .25rem rgba(0, 0, 0, .25); }
.text-shadow-2 { text-shadow: 0 .25rem .5rem rgba(0, 0, 0, .25); }
.text-shadow-3 { text-shadow: 0 .5rem 1.5rem rgba(0, 0, 0, .25); }

.card-cover {
  background-repeat: no-repeat;
  background-position: center center;
  background-size: cover;
}
</style>

<main class="container my-5 mx-0 px-0">
    <div class="container px-4 py-5" id="hanging-icons">
        <h2 class="pb-2 border-bottom">Upcoming Meetings</h2>

        <?php
          if(count($meetings) > 0){
            
            foreach($meetings as $meeting){
        ?>

        <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
                    
            <div class="col d-flex align-items-start">
                <div>
                    <h2><?php echo $meeting->title; ?></h2>
                    <p><?php echo ($meeting->description == null || $meeting->description == "" ? '<i>No description</i>': $meeting->description); ?></p>
                    <p>Student: <?php echo $meeting->email; ?> (<?php echo $meeting->registration_number; ?>)</p>
                    <p>Scheduled On: <?php echo $meeting->scheduled_on; ?></p>
                    
                    <?php
                    if(is_null($meeting->accepted)){
                    ?>
                    <a href="<?php echo_base_url();?>/views/advisor/meeting.php?accept&meeting_id=<?php echo $meeting->id;?>" class="btn btn-success">
                        Accept
                    </a>

                    <a href="<?php echo_base_url();?>/views/advisor/meeting.php?rject&meeting_id=<?php echo $meeting->id;?>" class="btn btn-danger">
                        Reject
                    </a>
                      <?php } else { ?>
                        Status: <?php echo $meeting->accepted ? "Accepted" : "Rejected"; ?>
                      <?php } ?>
                </div>
            </div>
        </div>

        <?php
          }
      } else { ?>
          <h3 class="py-4"> No upcoming meetings currently scheduled </h3>
        <?php } ?>
    </div>

    <div class="b-example-divider"></div>
    
    <div class="container px-4 py-5" id="hanging-icons-events">
        <h2 class="pb-2 border-bottom py-4">Upcoming Events</h2>

        <a href="<?php echo_base_url();?>/views/advisor/event.php?mode=create" class="btn btn-primary">
            Create New Event
        </a>

        <div class="col d-flex align-items-start">
          <?php
          if(count($events) > 0){

            foreach($events as $event){
            ?>
            
            <div class="col-4 px-4 mx-4">
                <h3><?php echo $event->title; ?></h3>
                <p><?php echo $event->description ??  "-"; ?></p>
                <p>Scheduled On: <?php echo $event->scheduled_on ??  "-"; ?></p>
                <p>Organized By: <?php echo strtolower($event->email) == strtolower($_SESSION['user']['email']) ? "You" : $event->email; ?></p>
            </div>

          <?php 
          }
        } else { ?>
              <p class="py-4">No upcoming events currently scheduled.</p>
            <?php } ?>
        </div>
    </div>

    <div class="b-example-divider"></div>
</main>

<?php
include_once __DIR__ . "/../shared/footer.php";
?>