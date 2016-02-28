<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/package-php-peon.php';

//Main API Class
class Server {
    public $dbinstance, $className, $functionName, $output, $classObj;
    public $classFolder, $params;

    //Default Construktor - Load and set configurations
    public function __construct($params) {
        $this->params       =   $params;
        $this->output       =   new OutputPeon($this->getParam("format"));
        $this->dbinstance   =   new MeekroDB(
            $GLOBALS["DbInfo"]["DB_HOST"]
            , $GLOBALS["DbInfo"]["DB_USER"]
            , $GLOBALS["DbInfo"]["DB_PASS"]
            , $GLOBALS["DbInfo"]["DB_NAME"]
            , $GLOBALS["DbInfo"]["DB_PORT"]
            , $GLOBALS["DbInfo"]["DB_FORMAT"]);
        $this->className    =   $this->getParam("class",true);
        $this->functionName =   $this->getParam("func",true);
        $this->classFolder  =   "public";
        $this->requireClasses($this->className);
        $this->callFunc();
    }

    //Can be used to prevent undefined Index Warning
    //Get Parameter helper function
    public function getParam($name,$important=false) {
        if (isset($this->params[$name])) {
            return $this->params[$name];
        } else {
            if ($important) {
                $this->output->plainError("Please set Param $name");
            }
            return "";
        }
    }

    //Dynamic loading and instancing the requested class
    public function requireClasses($className) {
        foreach (scandir($this->classFolder) as $filename) {
            $file=$this->classFolder.DIRECTORY_SEPARATOR.$filename;
            if (strpos($filename, strtolower($className)) !== false) {
                if (is_file($file)) {
                    require_once($file);
                    $this->classObj=new $this->className($this);
                }
            }
        }
    }
    //Call requested function and pass request parameters $_GET/$_POST
    public function callFunc() {
        if (method_exists($this->classObj, $this->functionName)) {
            return call_user_func(array($this->classObj, $this->functionName), $this->params);
        }
        else {
            die("Funktion nicht gefunden");
        }
    }
}

//TODO: Parse and validate parameters -> via additional dev dependency
$parsedParams=array();
foreach($_REQUEST as $keyname=>$param) {
    $parsedParams[$keyname]=$param;
}

//create a new php-peon server
$server=new Server($parsedParams);