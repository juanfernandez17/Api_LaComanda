<?php

require_once './models/Mesa.php';
require_once './models/Pedido.php';
require_once './models/Producto.php';

class Pedido_producto
{
    public $id;
    public $codigo_mesa;
    public $nombre_cliente;
    public $id_producto;
    public $estado;
    public $codigo_pedido;

    public function __construct()
    {

    }

    public function setId($id) { $this->id = $id; }    
    public function setCodigoMesa($codigo_mesa) { $this->codigo_mesa = $codigo_mesa; }
    public function setNombreCliente ($nombre_cliente) { $this->nombre_cliente = $nombre_cliente; }
    public function setIdProducto($id_producto) { $this->id_producto = $id_producto; }
    public function setEstado($estado) { $this->estado = $estado; }
    public function setCodigoPedido($codigo_pedido) { $this->codigo_pedido = $codigo_pedido; }

    public function getId() { return $this->id;}
    public function getCodigoMesa() {return $this->codigo_mesa; }
    public function getNombreCliente() { return $this->nombre_cliente;} 
    public function getIdProducto() { return $this->id_producto; }
    public function getEstado() { return $this->estado;}
    public function getCodigoPedido() { return $this->codigo_pedido; }   
 
    public static function crearPedidoProducto($pedidoProducto)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("INSERT INTO pedido_producto (codigo_mesa, nombre_cliente, id_producto, estado, codigo_pedido) VALUES (:codigo_mesa, :nombre_cliente, :id_producto, :estado, :codigo_pedido)");
        $query->bindValue(":codigo_mesa", $pedidoProducto->getCodigoMesa(), PDO::PARAM_STR);
        $query->bindValue(":nombre_cliente", $pedidoProducto->getNombreCliente(), PDO::PARAM_STR);
        $query->bindValue(":id_producto", $pedidoProducto->getIdProducto(), PDO::PARAM_INT);        
        $query->bindValue(":estado", $pedidoProducto->getEstado(), PDO::PARAM_STR);
        $query->bindValue(":codigo_pedido", $pedidoProducto->getCodigoPedido(), PDO::PARAM_STR);

        $query->execute();

        return $objDataAccess->getLastInsertedId();    
    }

    public function obtenerPedidosProductos()
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT id, codigo_mesa, nombre_cliente, id_producto, estado, codigo_pedido FROM pedido_productos");
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS,"Pedido_producto");
    }

    public static function obtenerPedidoProducto($id)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT id, codigo_mesa, nombre_cliente, id_producto, estado, codigo_pedido FROM pedido_productos WHERE id = :id");
        $query->bindValue(':id',$id, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchObject('Pedido_producto');
    }

    public static function obtenerPedidoProductoPorCodigo($codigo_pedido)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT id, codigo_mesa, nombre_cliente, id_producto, estado, codigo_pedido FROM pedido_producto  WHERE codigo_pedido = :codigo_pedido");
        $query->bindValue(':codigo_pedido',$codigo_pedido, PDO::PARAM_STR);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS,'Pedido_producto');
    }

    public static function obtenerPedidosDeUnaMesa($codigo_pedido)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT id, codigo_mesa, nombre_cliente, id_producto, estado, codigo_pedido FROM pedido_productos WHERE codigo_pedido = :codigo_pedido");
        $query->bindValue(':codigo_pedido',$codigo_pedido, PDO::PARAM_STR);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS,"Pedido_producto");
    }

    public static function finalizarPedidoProducto($codigo_mesa)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("UPDATE  pedido_productos SET estado = :estado WHERE codigo_mesa = :codigo_mesa");
        $query->bindValue(':estado','Finalizado', PDO::PARAM_STR);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS,"Pedido_producto");
    }
}
?>