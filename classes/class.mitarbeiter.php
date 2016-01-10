<?php
/**
 * Created by IntelliJ IDEA.
 * User: Jennifer Hormes
 * Date: 09.01.2016
 * Time: 16:45
 */

class Mitarbeiter {
    private $dbinstance;

    /**
     * mitarbeiter constructor.
     */
    public function __construct($db) {
        //Die Datenbankverbindung wird vom Server erstellt und als Instanz übergeben
        $this->dbinstance=$db;
    }
    public function select_mitarbeiter($params) {
        //Mitarbeiter auslesen
        //  in einem Array speichern
        /// im Browser ausgeben
        $row = $this->dbinstance->query("SELECT * FROM mitarbeiter");
        echo json_encode(array("content"=>$row));
    }
    /*
     * Einen Mitarbeiter in der Datenbank speichern
     * TODO: Automatisches Parameterparsing einfügen pro Webservice
     */
    public function save_mitarbeiter($params) {
        $this->dbinstance->insert('mitarbeiter', array(
            'Name' => $params["mitarbeitername"],
            'E-Mail' => $params["mitarbeiteremail"]
        ));
        echo json_encode(array("content"=>"OK"));
    }
}