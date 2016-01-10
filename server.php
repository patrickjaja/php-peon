<?php
/**
 * Created by IntelliJ IDEA.
 * User: Jennifer Hormes
 * Date: 09.01.2016
 * Time: 16:45
 */

/*
 * Einbinden der über composer definierten Abhängigkeiten
 * zur Zeit gibt es nur eine
 *  - MeekroDB für den MySQL Zugriff als Hilfsklasse
 */
require __DIR__ . '/vendor/autoload.php';

class Server {
    private $dbinstance, $className, $functionName, $classObj;
    public $classFolder, $params;

    //Default Konstruktor - Alle Konfigurationen setzen und Datenbank Verbindung aufbauen
    public function __construct($params) {
        $this->dbinstance   = new MeekroDB('localhost', 'root', 'ZuDQOqtmqlFLeHQ2x39c', 'hsnr', '3306', 'utf8');
        $this->className    =$params["class"];
        $this->functionName =$params["func"];
        $this->classFolder  ="classes";
        $this->params       =$params;
        $this->requireClasses($this->className);
        $this->callFunc();
    }
    //Dynamisches Laden der angeforderten Funktion und ein Objekt der Klasse erstellen
    public function requireClasses($className) {
        foreach (scandir($this->classFolder) as $filename) {
            $file=$this->classFolder.DIRECTORY_SEPARATOR.$filename;
            if (strpos($filename, strtolower($className)) !== false) {
                if (is_file($file)) {
                    require_once($file);
                    $this->classObj=new $this->className($this->dbinstance);
                }
            }
        }
    }
    //Die angeforderte Funktion aufrufen und alle Parameter übergeben (aus $_REQUEST)
    public function callFunc() {
        if (method_exists($this->classObj, $this->functionName)) {
            return call_user_func(array($this->classObj, $this->functionName), $this->params);
        }
        else {
            die("Funktion nicht gefunden");
        }
    }
}

//Alle Parameter aus der URL holen (POST+GET)
//TODO: Verarbeiten und validieren
$parsedParams=array();
foreach($_REQUEST as $keyname=>$param) {
    $parsedParams[$keyname]=$param;
}
//Eine Instanz von der server Klasse erstellen
$server=new Server($parsedParams);