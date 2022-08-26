<?php

require_once __DIR__ . '/../../../../wp-load.php';
require_once __DIR__ . '/../../../../vendor/autoload.php';

use AcMarche\College\AgendaCollege;
use AcMarche\College\CollegeDb;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

$collegeDb = new CollegeDb();
$agendaCollege = new AgendaCollege();
$session = $agendaCollege->createSession();
$session->start();

$request = Request::createFromGlobals();
$token = $request->get('token');
$destinataire = $collegeDb->getDestinaireByToken($token);
if(!$destinataire){
    $agendaCollege->render('token', []);
    return;
}
$events = $collegeDb->getEvents();
$vars = ['events' => $events, 'flashes'=>$session->getFlashBag()->all()];
$agendaCollege->render('index', $vars);
die();
