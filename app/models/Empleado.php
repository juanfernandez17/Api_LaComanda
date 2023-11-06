<?php
include_once './DataBase/DataAccess.php';


class Empleado
{
    public $id;
    public $nombre;
    public $rol;
    public $cant_operaciones;
    public $estado;
    public $fecha_alta;
    public $fecha_baja;
   
    public function __construct()
    {
   
    }

    public function setId($id) { $this->id = $id; }    
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setRol($rol) { $this->rol = $rol; }
    public function setCantOperaciones($cant_operaciones) { $this->cant_operaciones = $cant_operaciones; }
    public function setEstado($estado) { $this->estado = $estado; }
    public function setFechaAlta($fecha_alta) { $this->fecha_alta = $fecha_alta; }
    public function setFechaBaja($fecha_baja) { $this->fecha_baja = $fecha_baja; }

    public function getId() { return $this->id; }    
    public function getNombre() { return $this->nombre; }
    public function getRol() { return $this->rol; }    
    public function getCantOperaciones() { return $this->cant_operaciones; }
    public function getEstado() { return $this->estado; }
    public function getFechaAlta() { return $this->fecha_alta; }    
    public function getFechaBaja() { return $this->fecha_baja; }

    public static function crearEmpleado($empleado)
    {        
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("INSERT INTO Empleados (nombre, rol, cant_operaciones, estado, fecha_alta, fecha_baja) VALUES (:nombre, :rol, :cant_operaciones, :estado, :fecha_alta, :fecha_baja)");
        $query->bindValue(":nombre", $empleado->getNombre(), PDO::PARAM_STR);
        $query->bindValue(":rol", $empleado->getRol(), PDO::PARAM_STR);
        $query->bindValue(":cant_operaciones", $empleado->getCantOperaciones(), PDO::PARAM_INT);        
        $query->bindValue(":estado", $empleado->getEstado(), PDO::PARAM_BOOL);
        $query->bindValue(":fecha_alta", $empleado->getFechaAlta());
        $query->bindValue(":fecha_baja", $empleado->getFechaBaja());
        $query->execute();

        return $objDataAccess->getLastInsertedId();    
    } 

    public static function obtenerEmpleado($id)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT id, nombre, rol, cant_operaciones, estado, fecha_alta, fecha_alta FROM empleados WHERE id = :id");
        $query->bindValue(':id',$id, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchObject('Empleado');
    }

    public static function obtenerEmpleados()
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT id, nombre, rol, cant_operaciones, estado, fecha_alta, fecha_baja FROM Empleados");
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS,"Empleado");
    }

    public static function modificarEmpleado($empleado)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("UPDATE Empleados SET nombre = :nombre, cant_operaciones = :cant_operaciones, estado = :estado, fecha_alta = :fecha_alta WHERE id = :id AND fecha_baja IS NULL");
        $query->bindValue(":id", $empleado->getId(), PDO::PARAM_INT);
        $query->bindValue(":nombre", $empleado->getNombre(), PDO::PARAM_STR);
        $query->bindValue(":rol", $empleado->getRol(), PDO::PARAM_STR);
        $query->bindValue(":cant_operaciones", $empleado->getCantOperaciones(), PDO::PARAM_INT);        
        $query->bindValue(":estado", $empleado->getEstado(), PDO::PARAM_BOOL);
        $query->bindValue(":fecha_alta", $empleado->getFechaAlta());
        $query->execute();
    }

    public static function DarDeBajaEmpleado($id)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("UPDATE Empleados SET estado = :estado, fecha_baja = :fecha_baja WHERE id = :id AND fecha_baja IS NULL");
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->bindValue(":estado", "De baja", PDO::PARAM_STR);

        $fecha = new DateTime(date("d-m-Y"));
        $query->bindValue(':fecha_baja', date_format($fecha, 'Y-m-d H:i:s'));
        $query->execute();
    }
    
}
?>