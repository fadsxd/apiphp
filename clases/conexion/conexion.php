<?php


class conexion {
    private $server;
    private $user;
    private $password;
    private $database;
    private $port;

    private $conexion;


    function __construct()
    {
        $listadatos = $this->datosconexion();
        foreach ($listadatos as $key => $value) {
            $this->server = $value['server'];
            $this->user = $value['user'];
            $this->password= $value['password'];
            $this->database =$value['database'];
            $this->port = $value['port'];

        }
        $this->conexion = new mysqli($this->server,$this->user,$this->password,$this->database,$this->port);
        if($this->conexion->connect_errno){
            echo "ERROR EN LA CONEXION";
            die();
        }
    }

    private function datosconexion(){
        $direccion = dirname(__FILE__);
        $jsondata = file_get_contents($direccion."/"."config");
        return json_decode($jsondata, true);
    }

    //CONVERTIR UTF-8

    private function convertirUtf8($array){
        array_walk_recursive($array,function(&$item,$key){
            if(!mb_detect_encoding($item,"utf-8",true)){
                $item = mb_convert_encoding($item,"utf-8");
            }
        });
        return $array;
    }
    public function obtenerDatos($sqlstr){
        $results = $this->conexion->query($sqlstr);
        $resultArray = array();
        foreach ($results as $key) {
            $resultArray[] = $key;
        }
        return $this->convertirUtf8($resultArray);
    }
     //CONVERTIR UTF-8


      //
      public function nonQuery($sqlstr){
        $resuts = $this->conexion->query($sqlstr);
        return $this->conexion->affected_rows;
      }

      // INSERT
      public function nonQueryId($sqlstr){
        $resuts = $this->conexion->query($sqlstr);
        $filas = $this->conexion->affected_rows;
        if($filas >= 1 ){
            return $this->conexion->insert_id;
        }else{
            return 0;
        }
      }

      //encriptar
      protected function encriptar($string){
        return md5($string);
      }



}

?>
