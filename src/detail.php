<?php

require_once '../../../wp-load.php';
require '../../../vendor/autoload.php';

use AcMarche\College\AgendaCollege;
use AcMarche\College\CollegeDb;
use Symfony\Component\HttpFoundation\Request;

$collegeDb     = new CollegeDb();
$agendaCollege = new AgendaCollege();

$request = Request::createFromGlobals();
$eventId = (int)$request->get('id');

$event = $collegeDb->getDetailEvent($eventId);
if ( ! $event) {
    $agendaCollege->render('404', []);
    return;
}
$participations = $collegeDb->getParticipations($eventId);
$vars           = ['event' => $event, 'participations' => $participations];
$agendaCollege->render('detail', $vars);
