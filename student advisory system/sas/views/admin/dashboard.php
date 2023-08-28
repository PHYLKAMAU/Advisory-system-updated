<?php
include_once __DIR__ . "/../shared/header.php";
include_once __DIR__ . "/../shared/nav.php";

if(!has_session()){
    redirect(base_url() . "/views/auth/login.php");
}

only('admin');
?>

<?php
    $users = $GLOBALS['CONN']->query("
        SELECT u.*, d.name as department_name, s.name as school_name
        FROM `users` u
        LEFT JOIN `departments` d ON d.id = u.department_id
        LEFT JOIN `schools` s ON s.id = u.school_id
    ")->all();

    $meetings = $GLOBALS['CONN']->query("
        SELECT m.*
        FROM `meetings` m
        ORDER BY m.scheduled_on DESC
    ")->all();
    
    array_map(function($meeting) use ($users){
        $student = array_filter($users, function($user) use($meeting){
            return $user->id == $meeting->student_id;
        });
        $student = array_shift($student);

        $advisor = array_filter($users, function($user) use($meeting){
            return $user->id == $meeting->advisor_id;
        });
        $advisor = array_shift($advisor);
        
        $meeting->advisor_email = $advisor->email ?? '-';
        $meeting->department_name = $advisor->department_name ?? '-';
        $meeting->school_name = $student->school_name ?? '-';
        $meeting->student_email = $student->email ?? '-';
        $meeting->registration_number = $student->registration_number ?? '-';

        return $meeting;
    }, $meetings);

    $events = $GLOBALS['CONN']->query("
        SELECT e.*
        FROM `events` e
        ORDER BY e.scheduled_on DESC
    ")->all();

    array_map(function($event) use ($users){
        $advisor = array_filter($users, function($user) use($event){
            return $user->id == $event->advisor_id;
        });
        $advisor = array_shift($advisor);
        
        $event->advisor_email = $advisor->email ?? '-';
        $event->department_name = $advisor->department_name ?? '-';
        
        return $event;
    }, $events);

?>

<main class="container col-12">
    <div class="container col-8 py-4 mx-4">
        <a href="<?php echo_base_url();?>/views/admin/school.php?mode=create" class="btn btn-primary row-4 px-2">
            Create New School
        </a>

        <a href="<?php echo_base_url();?>/views/admin/department.php?mode=create" class="btn btn-primary row-4 px-2">
            Create New Department
        </a>
    </div>

    <div id="users" class="container col-10 py-4">
        <h1 class="h3 mb-3 fw-normal">Users</h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Registration Number (Student)</th>
                    <th scope="col">School Name (Student)</th>
                    <th scope="col">Department Name (Advisor)</th>
                    <th scope="col">Joined</th>
                    <th scope="col">Last Seen</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $i = 0;
                    foreach($users as $user){
                ?>

                <tr>
                    <th scope="row"><?php echo ++$i; ?></th>
                    <td><?php echo $user->email; ?></td>
                    <td><?php echo $user->role; ?></td>
                    <td><?php echo $user->registration_number ?? '-'; ?></td>
                    <td><?php echo $user->school_name ?? '-'; ?></td>
                    <td><?php echo $user->department_name ?? "-"; ?></td>
                    <td><?php echo $user->joined_on; ?></td>
                    <td><?php echo !is_null($user->lastaccess) ? timeago($user->lastaccess) : "-"; ?></td>
                </tr>

                <?php } ?>
                
            </tbody>
        </table>
    </div>

    <div id="meetings" class="container col-10 py-4">
        <h1 class="h3 mb-3 fw-normal">Meetings</h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Student Details</th>
                    <th scope="col">Advisor Details</th>
                    <th scope="col">Scheduled On</th>
                    <th scope="col">Accept Status</th>
                    <th scope="col">Expired</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $i = 0;
                    foreach($meetings as $meeting){
                ?>

                <tr>
                    <th scope="row"><?php echo ++$i; ?></th>
                    <td><?php echo $meeting->title; ?></td>
                    <td><?php echo $meeting->description ?? '-'; ?></td>
                    <td><?php echo sprintf("%s %s ( %s )", $meeting->student_email, $meeting->registration_number, $meeting->school_name) ?></td>
                    <td><?php echo sprintf("%s ( %s )", $meeting->advisor_email, $meeting->department_name) ?></td>
                    <td><?php echo $meeting->scheduled_on ?? "-"; ?></td>
                    <td><?php echo is_null($meeting->accepted) ? "-" : ($meeting->accepted ? "Accepted" : "Rejected"); ?></td>
                    <td><?php echo $meeting->scheduled_on <= date("Y-m-d H:i:s") ? "Yes" : "No"; ?></td>
                </tr>

                <?php } ?>
                
            </tbody>
        </table>
    </div>

    <div id="events" class="container col-10 py-4">
        <h1 class="h3 mb-3 fw-normal">Events</h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Advisor Details</th>
                    <th scope="col">Scheduled On</th>
                    <th scope="col">Expired</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $i = 0;
                    foreach($events as $event){
                ?>

                <tr>
                    <th scope="row"><?php echo ++$i; ?></th>
                    <td><?php echo $event->title; ?></td>
                    <td><?php echo $event->description ?? '-'; ?></td>
                    <td><?php echo sprintf("%s ( %s )", $meeting->advisor_email, $meeting->department_name) ?></td>
                    <td><?php echo $event->scheduled_on ?? "-"; ?></td>
                    <td><?php echo $event->scheduled_on <= date("Y-m-d H:i:s") ? "Yes" : "No"; ?></td>
                </tr>

                <?php } ?>
                
            </tbody>
        </table>
    </div>

    <div class="container col-10 py-4">
        <div class="card px-2 mx-2">
            <div class="card-header">
                Reports
            </div>
            <div class="card-body row">
                <a href="#" class="card-text row report" data-target="users">Download Users Report</a>
                <a href="#" class="card-text row report" data-target="meetings">Download Meetings Report</a>
                <a href="#" class="card-text row report" data-target="events">Download Events Report</a>
            </div>
        </div>
    </div>
</div>
</main>

<script src= "./../../assets/vendor/jquery/jquery-3.7.0.min.js"></script>
<script src= "./../../assets/vendor/jspdf/jspdf.umd.min.js" defer></script>
<script src= "./../../assets/vendor/html2canvas/html2canvas.min.js" defer></script>

<script>
    $(".report").on('click', function(e){
        e.preventDefault();
        e.stopPropagation();

        let report = $(this).data("target");

        if(! report){
            return false;
        }

        /** @see https://stackoverflow.com/questions/75265690/how-to-print-a-div-with-jspdf */
        const myDiv = document.getElementById(report);

        if(!myDiv){
            return false;
        }

        html2canvas(myDiv)
        .then((canvas) => {
                const canvasData = canvas.toDataURL("image/png", 1.0);
                const canvasWidth = canvas.width - 200;
                const canvasHeight = canvas.height;
                const pdf = (new jspdf.jsPDF("", "pt", "a4"));
                pdf.addImage(canvasData, "PNG", 0, 0, canvasWidth - 300, canvasHeight, "", "FAST");
                pdf.save(report + ".pdf");
            }
        )
    });
</script>

<?php
include_once __DIR__ . "/../shared/footer.php";
?>