<?php

namespace App\Infrastructure\Persistence;

use App\Application\Actions\RepositoryConnection\Connect;
use App\Domain\ClienteRepository;
use DateTime;
use PDO;
use Ramsey\Uuid\Uuid;

class DataClienteRepository implements ClienteRepository
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

    public function setDataSave($body): array
    {
        if (!isset($body['nombre'])) {
            return ['data' => [], 'message' => 'Nombre no definido', 'statusCode' => 400, 'success' => false];
        }
        if (!isset($body['apellido_paterno'])) {
            return ['data' => [], 'message' => 'Apellido paterno no definido', 'statusCode' => 400, 'success' => false];
        }
        if (!isset($body['apellido_materno'])) {
            return ['data' => [], 'message' => 'Apellido materno no definido', 'statusCode' => 400, 'success' => false];
        }
        if (!isset($body['f_nacimiento'])) {
            return ['data' => [], 'message' => 'Fecha de nacimiento no definido', 'statusCode' => 400, 'success' => false];
        }
        if (!isset($body['tel_celular'])) {
            return ['data' => [], 'message' => 'Telefono celular no definido', 'statusCode' => 400, 'success' => false];
        }
        if (!isset($body['nro_documento'])) {
            return ['data' => [], 'message' => 'Nro. documento no definido', 'statusCode' => 400, 'success' => false];
        }
        if (!isset($body['email_personal'])) {
            return ['data' => [], 'message' => 'Email no definido', 'statusCode' => 400, 'success' => false];
        }

        $f_nac = DateTime::createFromFormat('d/m/Y', $body['f_nacimiento']);
        $f_nac = $f_nac->format('Y-m-d');

        $uuid = Uuid::uuid6();
        $id = $uuid->toString();

        $estado_codigo = 'HAB';
        $estado_valor = 'HABILITADO';
        $activo = true;

        $sql = "
            INSERT INTO spa.cliente (id, nombre, apellido_paterno, apellido_materno, f_nacimiento, tel_celular, nro_documento, email_personal, estado_codigo, estado_valor, activo)
            VALUES (:id, :nombre, :apellido_paterno, :apellido_materno, :f_nacimiento, :tel_celular, :nro_documento, :email_personal, :estado_codigo, :estado_valor, :activo)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $body['nombre']);
        $stmt->bindParam(':apellido_paterno', $body['apellido_paterno']);
        $stmt->bindParam(':apellido_materno', $body['apellido_materno']);
        $stmt->bindParam(':f_nacimiento', $f_nac);
        $stmt->bindParam(':tel_celular', $body['tel_celular']);
        $stmt->bindParam(':nro_documento', $body['nro_documento']);
        $stmt->bindParam(':email_personal', $body['email_personal']);
        $stmt->bindParam(':estado_codigo', $estado_codigo);
        $stmt->bindParam(':estado_valor', $estado_valor);
        $stmt->bindParam(':activo', $activo);
        $stmt->execute();

        $data = [
            'id' => $id
        ];
        return ['data' => $data, 'message' => 'Cliente creado exitosamente', 'statusCode' => 200, 'success' => true];
    }

    public function setDelete($body): array
    {
        if (!isset($body['id_cliente'])) {
            return ['data' => [], 'message' => 'ID de cliente no definido', 'statusCode' => 400, 'success' => false];
        }

        $sql = "
            UPDATE spa.cliente 
            SET activo = false
            WHERE id = :id_cliente AND activo = true
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_cliente', $body['id_cliente']);
        $stmt->execute();
        return ['data' => [], 'message' => 'Cliente eliminado exitosamente', 'statusCode' => 200, 'success' => true];
    }

    public function setDataPut($body): array
    {
        if (!isset($body['id_cliente'])) {
            return ['data' => [], 'message' => 'ID de cliente no definido', 'statusCode' => 400, 'success' => false];
        }
        if (!isset($body['nombre'])) {
            return ['data' => [], 'message' => 'Nombre no definido', 'statusCode' => 400, 'success' => false];
        }
        if (!isset($body['apellido_paterno'])) {
            return ['data' => [], 'message' => 'Apellido paterno no definido', 'statusCode' => 400, 'success' => false];
        }
        if (!isset($body['apellido_materno'])) {
            return ['data' => [], 'message' => 'Apellido materno no definido', 'statusCode' => 400, 'success' => false];
        }
        if (!isset($body['f_nacimiento'])) {
            return ['data' => [], 'message' => 'Fecha de nacimiento no definido', 'statusCode' => 400, 'success' => false];
        }
        if (!isset($body['tel_celular'])) {
            return ['data' => [], 'message' => 'Telefono celular no definido', 'statusCode' => 400, 'success' => false];
        }
        if (!isset($body['nro_documento'])) {
            return ['data' => [], 'message' => 'Nro. documento no definido', 'statusCode' => 400, 'success' => false];
        }
        if (!isset($body['email_personal'])) {
            return ['data' => [], 'message' => 'Email no definido', 'statusCode' => 400, 'success' => false];
        }

        $f_nac = DateTime::createFromFormat('d/m/Y', $body['f_nacimiento']);
        $f_nac = $f_nac->format('Y-m-d');

        $sql = "
            UPDATE spa.cliente 
            SET nombre = :nombre, apellido_paterno = :apellido_paterno, apellido_materno = :apellido_materno, f_nacimiento = :f_nacimiento, tel_celular = :tel_celular, nro_documento = :nro_documento, email_personal = :email_personal
            WHERE id = :id_cliente AND activo = true
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_cliente', $body['id_cliente']);
        $stmt->bindParam(':nombre', $body['nombre']);
        $stmt->bindParam(':apellido_paterno', $body['apellido_paterno']);
        $stmt->bindParam(':apellido_materno', $body['apellido_materno']);
        $stmt->bindParam(':f_nacimiento', $f_nac);
        $stmt->bindParam(':tel_celular', $body['tel_celular']);
        $stmt->bindParam(':nro_documento', $body['nro_documento']);
        $stmt->bindParam(':email_personal', $body['email_personal']);
        $stmt->execute();
        return ['data' => [], 'message' => 'Cliente actualizado exitosamente', 'statusCode' => 200, 'success' => true];


    }

    public function getDataLista(): array
    {
        $sql = "SELECT id, nombre, apellido_paterno, apellido_materno, f_nacimiento, tel_celular, nro_documento, email_personal, estado_codigo, estado_valor, activo FROM spa.cliente WHERE activo = true";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['data' => $result, 'message' => 'Lista de clientes obtenida exitosamente', 'statusCode' => 200, 'success' => true];
    }

    public function getDataId($body): array
    {
        if (!isset($body['id_cliente'])) {
            return ['data' => [], 'message' => 'ID de cliente no definido', 'statusCode' => 400, 'success' => false];
        }

        $sql = "SELECT id, nombre, apellido_paterno, apellido_materno, f_nacimiento, tel_celular, nro_documento, email_personal, estado_codigo, estado_valor, activo FROM spa.cliente WHERE id = :id_cliente AND activo = true";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_cliente', $body['id_cliente']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return ['data' => [], 'message' => 'Cliente no encontrado', 'statusCode' => 404, 'success' => false];
        }

        return ['data' => $result, 'message' => 'Datos del cliente obtenidos exitosamente', 'statusCode' => 200, 'success' => true];
    }
}
