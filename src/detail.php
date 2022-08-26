<?php

require_once __DIR__ . '/../../../../wp-load.php';
require __DIR__ . '/../../../../vendor/autoload.php';

use AcMarche\College\AgendaCollege;
use AcMarche\College\CollegeDb;
use Symfony\Component\HttpFoundation\Request;

$collegeDb     = new CollegeDb();
$agendaCollege = new AgendaCollege();

$request = Request::createFromGlobals();
$eventId = (int)$request->get('id');
$token   = $request->get('token');

$destinataire = $collegeDb->getDestinaireByToken($token);
if ( ! $destinataire) {
    $agendaCollege->render('token', []);

    return;
}

$event = $collegeDb->getDetailEvent($eventId);
if ( ! $event) {
    $agendaCollege->render('404', []);

    return;
}
$participations = $collegeDb->getParticipations($eventId);
$vars           = ['event' => $event, 'participations' => $participations, 'token' => $token];
$agendaCollege->render('detail', $vars);
