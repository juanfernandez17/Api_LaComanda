<?php

include_once './DataBase/DataAccess.php';

class Pedido
{
    public $id;
    public $codigo_pedido;
    public $estado;
    public $id_empleado;
    public $id_mesa;
    public $tiempo_estimado;
    public $horario_inicio;
    public $horario_fin;
   
    public function __construct()
    {   
        $horario_inicio = date("H:i:s");
    }

    public function setId($id) { $this->id = $id; }    
    public function setCodigoPedido($codigo_pedido) { $this->codigo_pedido = $codigo_pedido; }
    public function setEstado($estado) { $this->estado = $estado; }
    public function setIdEmpleado($id_empleado) { $this->id_empleado = $id_empleado; }
    public function setIdMesa($id_mesa) { $this->id_mesa = $id_mesa; }
    public function setIdProducto($id_producto) { $this->id_producto = $id_producto; }
    public function setNombreCliente($nombre_cliente) { $this->nombre_cliente = $nombre_cliente; }
    public function setTiempoEstimado($tiempo_estimado) { $this->tiempo_estimado = $tiempo_estimado; }
    public function setHorarioInicio($horario_inicio) { $this->horario_inicio = $horario_inicio; }
    public function setHorarioFin($horario_fin) { $this->horario_fin = $horario_fin; }

    public function getId() { return $this->id; } 
    public function getCodigoPedido() { return $this->codigo_pedido; }   
    public function getEstado() { return $this->estado; }
    public function getIdEmpleado() { return $this->id_empleado; }    
    public function getIdMesa() { return $this->id_mesa; }
    public function getIdProducto() { return $this->id_producto; }
    public function getNombreCliente() { return $this->nombre_cliente; }    
    public function getTiempoEstimado() { return $this->tiempo_estimado; }
    public function getHorarioInicio() { return $this->horario_inicio; }
    public function getHorarioFin() { return $this->horario_fin; }   

    public static function crearPedido($pedido)
    {                
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("INSERT INTO Pedidos (codigo_pedido, estado, id_empleado, id_mesa, tiempo_estimado, horario_inicio, horario_fin) VALUES (:codigo_pedido, :estado, :id_empleado, :id_mesa, :tiempo_estimado, :horario_inicio, :horario_fin)");
        $query->bindValue(":codigo_pedido", $pedido->getCodigoPedido(), PDO::PARAM_INT);
        $query->bindValue(":estado", $pedido->getEstado(), PDO::PARAM_STR);
        $query->bindValue(":id_empleado", $pedido->getIdEmpleado(), PDO::PARAM_INT);        
        $query->bindValue(":id_mesa", $pedido->getIdMesa(), PDO::PARAM_INT);
        $query->bindValue(":tiempo_estimado", $pedido->getTiempoEstimado(), PDO::PARAM_INT);
        $query->bindValue(":horario_inicio", $pedido->getHorarioInicio());
        $query->bindValue(":horario_fin", $pedido->getHorarioFin());
        $query->execute();

        return $objDataAccess->getLastInsertedId();    
    } 

    public static function obtenerPedidoPorCodigo($codigo_pedido)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT b.id, b.codigo_mesa, b.nombre_cliente, b.id_producto, b.estado, b.codigo_pedido FROM pedido_producto b INNER JOIN pedidos a WHERE b.codigo_pedido = :codigo_pedido AND b.codigo_pedido = a.codigo_pedido");
        $query->bindValue(':codigo_pedido',$codigo_pedido, PDO::PARAM_STR);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS,'Pedido_producto');
    }

    public static function obtenerPedidos()
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT id, codigo_mesa, nombre_cliente, id_producto, estado, codigo_pedido FROM pedido_producto");
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS,"pedido_producto");
    }

    public static function modificarPedido($pedido)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("UPDATE Pedido SET codigo_pedido = :codigo_pedido, estado = :estado, id_empleado = :id_empleado, id_mesa = :id_mesa, id_prodcuto = :id_producto, nombre_cliente = :nombre_cliente, tiempo_estimado = :tiempo_estimado, horario_inicio = :horario_inicio, horario_fin = :horario_fin WHERE id = :id");
        $query->bindValue(":id", $pedido->getId(), PDO::PARAM_INT);
        $query->bindValue(":codigo_pedido", $pedido->getCodigoPedido(), PDO::PARAM_INT);
        $query->bindValue(":estado", $pedido->getEstado(), PDO::PARAM_STR);
        $query->bindValue(":id_empleado", $pedido->getIdEmpleado(), PDO::PARAM_INT);        
        $query->bindValue(":id_mesa", $pedido->getIdMesa(), PDO::PARAM_INT);
        $query->bindValue(":id_producto", $pedido->getIdProducto(), PDO::PARAM_INT);
        $query->bindValue(":nombre_cliente", $pedido->getNombreCliente(), PDO::PARAM_STR);
        $query->bindValue(":tiempo_estimado", $pedido->getTiempoEstimado(), PDO::PARAM_INT);
        $query->bindValue(":horario_inicio", $pedido->getHorarioInicio());
        $query->bindValue(":horario_fin", $pedido->getHorarioFin());
        $query->execute();
    }

    public static function BorrarPedido($codigo_pedido)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("DELETE FROM pedido_producto WHERE codigo_pedido = :codigo_pedido");
        $query->bindValue(":codigo_pedido", $codigo_pedido, PDO::PARAM_STR);
        $query->execute();
    }  
}
?>