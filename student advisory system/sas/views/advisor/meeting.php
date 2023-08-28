<?php
include_once __DIR__ . "/../shared/header.php";

only('advisor');

if(isset($_GET['reject']) && isset($_GET['meeting_id'])){
  $meeting_orm = $GLOBALS['ORM_MEETING'];
  $meeting = $meeting_orm->where('advisor_id')->is($_SESSION['user']['id'])
              ->where('id')->is($_GET['meeting_id'])
              ->get();

  if($meeting){
      $meeting->accepted = false;
      $GLOBALS['ORM']->save($meeting);
      redirect(dashboard());
  }
}

if(isset($_GET['accept']) && isset($_GET['meeting_id'])){
  $meeting_orm = $GLOBALS['ORM_MEETING'];
  $meeting = $meeting_orm->where('advisor_id')->is($_SESSION['user']['id'])
              ->where('id')->is($_GET['meeting_id'])
              ->get();

  if($meeting){
      $meeting->accepted = true;
      $GLOBALS['ORM']->save($meeting);
      redirect(dashboard());
  }
}

redirect(dashboard());
