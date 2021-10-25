<?php

require '../../../vendor/autoload.php';

use AcMarche\College\AgendaCollege;
use AcMarche\College\CollegeDb;

$collegeDb = new CollegeDb();
$agendaCollege = new AgendaCollege();
$session = $agendaCollege->createSession();
$session->start();
$events = $collegeDb->getEvents();
$vars = ['events' => $events, 'flashes'=>$session->getFlashBag()->all()];
$agendaCollege->render('index', $vars);
die();
