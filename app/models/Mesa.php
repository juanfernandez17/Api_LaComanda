<?php
include_once './DataBase/DataAccess.php';

class Mesa
{
    public $id;
    public $codigo_mesa;
    public $estado;
    public $cant_utilizada;
    public $cant_facturacion;
    public $mayor_importe;
    public $menor_importe;
    

    public function __construct()
    {

    }

    public function setId($id) { $this->id = $id; }    
    public function setCodigoMesa($codigo_mesa) { $this->codigo_mesa = $codigo_mesa; }
    public function setEstado($estado) { $this->estado = $estado; }
    public function setCantUtilizada($cant_utilizada) { $this->cant_utilizada = $cant_utilizada; }
    public function setCantFacturacion($cant_venvdida) { $this->cant_facturacion = $cant_venvdida; }    
    public function setMayorImporte($mayor_importe) { $this->mayor_importe = $mayor_importe; }
    public function setMenorImporte($menor_importe) { $this->menor_importe = $menor_importe; }

    public function getId() { return $this->id; }    
    public function getCodigoMesa() { return $this->codigo_mesa; }
    public function getEstado() { return $this->estado; }    
    public function getCantUtilizada() { return $this->cant_utilizada; }
    public function getCantFacturacion() { return $this->cant_facturacion; }    
    public function getMayorImporte() { return $this->mayor_importe; }
    public function getMenorImporte() { return $this->menor_importe; }

    public static function crearMesa($mesa)
    {        
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("INSERT INTO mesas (codigo_mesa, estado, cant_utilizada, cant_facturacion, mayor_importe, menor_importe) VALUES (:codigo_mesa, :estado, :cant_utilizada, :cant_facturacion, :mayor_importe,:menor_importe)");
        $query->bindValue(":codigo_mesa", $mesa->getCodigoMesa(), PDO::PARAM_STR);
        $query->bindValue(":estado", $mesa->getEstado(), PDO::PARAM_STR);
        $query->bindValue(":cant_utilizada", $mesa->getCantUtilizada(), PDO::PARAM_INT);        
        $query->bindValue(":cant_facturacion", $mesa->getCantFacturacion(), PDO::PARAM_INT);  
        $query->bindValue(":mayor_importe", $mesa->getMayorImporte(), PDO::PARAM_INT);        
        $query->bindValue(":menor_importe", $mesa->getMenorImporte(), PDO::PARAM_INT);  
        $query->execute();

        return $objDataAccess->getLastInsertedId();    
    } 

    public static function obtenerMesa($id)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT id, codigo_mesa, estado, cant_utilizada, cant_facturacion, mayor_importe, menor_importe FROM mesas WHERE id = :id");
        $query->bindValue(':id',$id, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchObject('Mesa');
    }

    public static function obtenerMesas()
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT id, codigo_mesa, estado, cant_utilizada, cant_facturacion, mayor_importe, menor_importe FROM mesas");
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS,"Mesa");
    }

    public static function modificarMesa($mesa)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("UPDATE mesas SET codigo_mesa = :codigo_mesa, estado = :estado, cant_utilizada = :cant_utilizada, cant_facturacion = :cant_facturacion, mayor_importe = :mayor_importe, menor_importe = :menor_importe WHERE id = :id");
        $query->bindValue(":id", $mesa->getId(), PDO::PARAM_INT);
        $query->bindValue(":codigo_mesa", $mesa->getCodigoMesa(), PDO::PARAM_STR);
        $query->bindValue(":estado", $mesa->getEstado(), PDO::PARAM_STR);
        $query->bindValue(":cant_utilizada", $mesa->getCantUtilizada(), PDO::PARAM_INT);        
        $query->bindValue(":cant_facturacion", $mesa->getCantFacturacion(), PDO::PARAM_INT);        
        $query->bindValue(":menor_importe", $mesa->getMayorImporte(), PDO::PARAM_INT);        
        $query->bindValue(":mayor_importe", $mesa->getMenorImporte(), PDO::PARAM_INT);
        $query->execute();
    }

    public static function borrarMesa($id)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("DELETE FROM mesas WHERE id = :id");
        $query->bindValue(":id", $id, PDO::PARAM_INT);

        $query->execute();
    }

}
?>