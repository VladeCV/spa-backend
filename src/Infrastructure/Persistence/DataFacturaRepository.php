<?php

namespace App\Infrastructure\Persistence;

use App\Application\Actions\RepositoryConnection\Connect;
use App\Domain\FacturaRepository;
use PDO;
use Ramsey\Uuid\Uuid;

class DataFacturaRepository implements FacturaRepository
{
    private $db;

    public function setDb($db)
    {
        $this->db = $db;
    }

    public function __construct()
    {
        $con = new Connect();
        $this->db = $con->getConnection();
    }

    public function getDataLista(): array
    {
        $sql = "SELECT * FROM spa.factura";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $dataLista = [];
        foreach ($data as $row) {
            $dataLista[] = [
                'id' => $row['id'],
                'nro' => $row['nro'],
                'id_cliente' => $row['id_cliente'],
                'servicio' => $row['servicio'],
                'periodo' => $row['periodo'],
                'monto' => $row['monto'],
                'estado' => [
                    'codigo' => $row['estado_codigo'],
                    'valor' => $row['estado_valor']
                ],
                'activo' => $row['activo'],
                'f_emision' => $row['f_emision'],
                'f_pago' => $row['f_pago'] ?? null
            ];
        }
        return ['data' => $dataLista, 'message' => 'Lista de facturas obtenida', 'statusCode' => 200, 'success' => true];
    }

    public function getDataId($body): array
    {
        $sql = "SELECT * FROM spa.factura WHERE id = :id_factura";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_factura', $body['id_factura']);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = [
            'id' => $data[0]['id'],
            'nro' => $data[0]['nro'],
            'id_cliente' => $data[0]['id_cliente'],
            'servicio' => $data[0]['servicio'],
            'periodo' => $data[0]['periodo'],
            'monto' => $data[0]['monto'],
            'estado' => [
                'codigo' => $data[0]['estado_codigo'],
                'valor' => $data[0]['estado_valor']
            ],
            'activo' => $data[0]['activo'],
            'f_emision' => $data[0]['f_emision'],
            'f_pago' => $data[0]['f_pago'] ?? null
        ];
        return ['data' => $data, 'message' => 'Factura obtenida por ID', 'statusCode' => 200, 'success' => true];
    }

    public function setDataSave($body): array
    {
        if (!isset($body['nro'])) {
            return ['data' => [], 'message' => 'Numero no definido', 'statusCode' => 400, 'success' => false];
        }
        if (!isset($body['id_cliente'])) {
            return ['data' => [], 'message' => 'Id cliente paterno no definido', 'statusCode' => 400, 'success' => false];
        }
        if (!isset($body['servicio'])) {
            return ['data' => [], 'message' => 'Servicio no definido', 'statusCode' => 400, 'success' => false];
        }
        if (!isset($body['periodo'])) {
            return ['data' => [], 'message' => 'Periodo no definido', 'statusCode' => 400, 'success' => false];
        }
        if (!isset($body['monto'])) {
            return ['data' => [], 'message' => 'Monto no definido', 'statusCode' => 400, 'success' => false];
        }

        $uuid = Uuid::uuid6();
        $id = $uuid->toString();

        $estado_codigo = 'PEN';
        $estado_valor = 'PENDIENTE';
        $activo = true;

        $sql = "
            INSERT INTO spa.factura (id, nro, id_cliente, servicio, periodo, monto, estado_codigo, estado_valor, activo, f_emision)
            VALUES (:id, :nro, :id_cliente, :servicio, :periodo, :monto, :estado_codigo, :estado_valor, :activo, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nro', $body['nro']);
        $stmt->bindParam(':id_cliente', $body['id_cliente']);
        $stmt->bindParam(':servicio', $body['servicio']);
        $stmt->bindParam(':periodo', $body['periodo']);
        $stmt->bindParam(':monto', $body['monto']);
        $stmt->bindParam(':estado_codigo', $estado_codigo);
        $stmt->bindParam(':estado_valor', $estado_valor);
        $stmt->bindParam(':activo', $activo);
        $stmt->execute();
        $data = [
            'id' => $id
        ];
        return ['data' => $data, 'message' => 'Factura creada exitosamente', 'statusCode' => 200, 'success' => true];
    }

    public function getFacturaByClienteIdLista($body): array
    {
        if (!isset($body['id_cliente'])) {
            return ['data' => [], 'message' => 'ID de cliente no definido', 'statusCode' => 400, 'success' => false];
        }
        $sql = "SELECT * FROM spa.cliente WHERE id = :id_cliente AND activo = true";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_cliente', $body['id_cliente']);
        $stmt->execute();
        $resultCliente = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultCliente) == 0) {
            return ['data' => [], 'message' => 'Cliente no encontrado', 'statusCode' => 404, 'success' => false];
        }
        $resultCliente = $resultCliente[0];
        $resultCliente = [
            'id' => $resultCliente['id'],
            'nombre' => $resultCliente['nombre'],
            'apellido_paterno' => $resultCliente['apellido_paterno'],
            'apellido_materno' => $resultCliente['apellido_materno'],
            'f_nacimiento' => $resultCliente['f_nacimiento'],
            'tel_celular' => $resultCliente['tel_celular'],
            'nro_documento' => $resultCliente['nro_documento'],
            'email_personal' => $resultCliente['email_personal'],
            'estado' => [
                'codigo' => $resultCliente['estado_codigo'],
                'valor' => $resultCliente['estado_valor']
            ],
            'activo' => $resultCliente['activo']
        ];

        $sql = "SELECT * FROM spa.factura WHERE id_cliente = :id_cliente";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_cliente', $body['id_cliente']);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $dataLista = [];
        foreach ($data as $row) {
            $dataLista[] = [
                'id' => $row['id'],
                'nro' => $row['nro'],
                'id_cliente' => $row['id_cliente'],
                'servicio' => $row['servicio'],
                'periodo' => $row['periodo'],
                'monto' => $row['monto'],
                'estado' => [
                    'codigo' => $row['estado_codigo'],
                    'valor' => $row['estado_valor']
                ],
                'activo' => $row['activo'],
                'f_emision' => $row['f_emision'],
                'f_pago' => $row['f_pago'] ?? null
            ];
        }
        $data = [
            'cliente' => $resultCliente,
            'facturas' => $dataLista
        ];
        return ['data' => $data, 'message' => 'Facturas obtenidas por ID de cliente', 'statusCode' => 200, 'success' => true];
    }
}
