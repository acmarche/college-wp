<?php
/**
 * This file is part of wordpress application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 18/11/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

require __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../../wp-load.php';//pour db

use AcMarche\College\AgendaCollege;
use AcMarche\College\CollegeDb;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$reponse = (int)$request->get('reponse');
$token = $request->get('token');
$event = (int)$request->get('event');

$collegeDb = new CollegeDb();
$agendaCollege = new AgendaCollege();
$destinataire = $collegeDb->getDestinaireByToken($token);
$session = $agendaCollege->createSession();
$session->start();

if ($destinataire && $event) {

    if ($collegeDb->checkRepondu($destinataire['id'], $event)) {
        $session->getFlashBag()->add('warning', 'Vous avez déjà répondu');
        $response = new RedirectResponse('/AcMarche/College/src/?token='.$token);
        $response->send();
        die();
    }

    try {
        $collegeDb->insertVote($destinataire['id'], $event, $reponse);
        $session->getFlashBag()->add('success', 'Merci pour votre réponse');
        $response = new RedirectResponse('/AcMarche/College/src/?token='.$token);
        $response->send();
        die();
    } catch (Exception $e) {
        $session->getFlashBag()->add('danger', 'Une erreur est survenue: '.$e->getMessage());
        $response = new RedirectResponse('/AcMarche/College/src/?token='.$token);
        $response->send();
        die();
    }
} else {
    $session->getFlashBag()->add('danger', 'Destinataire ou évènement non trouvé ');
    $response = new RedirectResponse('/AcMarche/College/src/?token='.$token);
    $response->send();
    die();
}
