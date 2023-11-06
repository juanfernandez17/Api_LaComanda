<?php
include_once './DataBase/DataAccess.php';

class Producto
{
    public $id;
    public $nombre;
    public $sector;
    public $precio;
    public $cant_vendida;

    public function __construct()
    {

    }

    public function setId($id) { $this->id = $id; }    
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setSector($sector) { $this->sector = $sector; }
    public function setPrecio($precio) { $this->precio = $precio; }
    public function setCantVendida($cant_vendida) { $this->cant_vendida = $cant_vendida; }

    public function getId() { return $this->id; }    
    public function getNombre() { return $this->nombre; }
    public function getSector() { return $this->sector; }    
    public function getPrecio() { return $this->precio; }
    public function getCantVendida() { return $this->cant_vendida; }

    public static function crearProducto($producto)
    {        
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("INSERT INTO productos (nombre, sector, precio, cant_vendida) VALUES (:nombre, :sector, :precio, :cant_vendida)");
        $query->bindValue(":nombre", $producto->getNombre(), PDO::PARAM_STR);
        $query->bindValue(":sector", $producto->getSector(), PDO::PARAM_STR);
        $query->bindValue(":precio", $producto->getPrecio(), PDO::PARAM_INT);        
        $query->bindValue(":cant_vendida", $producto->getCantVendida(), PDO::PARAM_INT);  
        $query->execute();

        return $objDataAccess->getLastInsertedId();    
    } 

    public static function obtenerProducto($id)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT id, nombre, sector, precio, cant_vendida FROM productos WHERE id = :id");
        $query->bindValue(':id',$id, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchObject('Producto');
    }

    public static function obtenerProductos()
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT id, nombre, sector, precio, cant_vendida FROM productos");
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS,"Producto");
    }

    public static function modificarProducto($producto)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("UPDATE Productos SET nombre = :nombre, sector = :sector, precio = :precio, cant_vendida = :cant_vendida WHERE id = :id");
        $query->bindValue(":id", $producto->getId(), PDO::PARAM_INT);
        $query->bindValue(":nombre", $producto->getNombre(), PDO::PARAM_STR);
        $query->bindValue(":sector", $producto->getRol(), PDO::PARAM_STR);
        $query->bindValue(":precio", $producto->getCantOperaciones(), PDO::PARAM_INT);        
        $query->bindValue(":cant_vendida", $producto->getEstado(), PDO::PARAM_INT);
        $query->execute();
    }

    public static function borrarProducto($id)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("DELETE FROM Productos WHERE id = :id");
        $query->bindValue(":id", $id, PDO::PARAM_INT);

        $query->execute();
    }

}
?>