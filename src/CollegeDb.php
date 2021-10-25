<?php

namespace AcMarche\College;

class CollegeDb
{
    private \PDO $dbh;

    public function __construct()
    {
        Env::loadEnv();
        $dsn      = 'mysql:host=localhost;dbname=invitation';
        $username = $_ENV['DB_COLLEGE_USER'];
        $password = $_ENV['DB_COLLEGE_PASS'];
        $options = array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        );

        $this->dbh = new \PDO($dsn, $username, $password, $options);
    }

    public function getDestinaireByToken(string $token)
    {
        $sql   = "SELECT * FROM destinataires WHERE `token` = '$token' ";
        $query = $this->execQuery($sql);

        return $query->fetch();
    }

    public function checkRepondu(int $destinataire, int $event)
    {
        $sql   = "SELECT * FROM participation WHERE `destinataire_id` = '$destinataire' AND `event_id` = '$event' ";
        $query = $this->execQuery($sql);

        return $query->fetch();
    }

    public function getEvents()
    {
        $today       = new \DateTime();
        $todayString = $today->format('Y-m-d');

        $sql   = "SELECT * FROM events WHERE `date_fin` >= '$todayString' ";
        $query = $this->execQuery($sql);

        return $query->fetchAll();
    }

    public function getDetailEvent(int $eventId)
    {
        $sql   = "SELECT * FROM events WHERE `id` = '$eventId' ";
        $query = $this->execQuery($sql);

        return $query->fetch();
    }

    public function getParticipations(int $eventId)
    {
        $sql   = "SELECT * FROM `participation` AS `p` LEFT JOIN `destinataires` AS `d` ON p.destinataire_id = d.id 
WHERE `event_id` = '$eventId' ";
        $query = $this->execQuery($sql);

        return $query->fetchAll();
    }

    /**
     * @param int $destinataire
     * @param int $idEvent
     * @param int $response
     *
     * @return bool|string
     * @throws \Exception
     */
    public function insertVote(int $destinataire, int $idEvent, int $response)
    {
        $today       = new \DateTime();
        $todayString = $today->format('Y-m-d H:i:s');

        $req = "INSERT INTO `participation` (`event_id`,`destinataire_id`,`reponse`,`createdAt`, `updatedAt`) 
VALUES (?,?,?,?,?)";

        $stmt = $this->dbh->prepare($req);
        $stmt->bindParam(1, $idEvent);
        $stmt->bindParam(2, $destinataire);
        $stmt->bindParam(3, $response);
        $stmt->bindParam(4, $todayString);
        $stmt->bindParam(5, $todayString);

        return $stmt->execute();
    }

    public function execQuery($sql)
    {
        $query = $this->dbh->query($sql);
        $error = $this->dbh->errorInfo();
        if ($error[0] != '0000') {
            // mail('jf@marche.be', 'duobac error sql', $error[2]);

            throw new \Exception($error[2]);
        };

        return $query;
    }


}

?>
