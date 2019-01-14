<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: text/html; charset=utf-8');
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');


// insertDiaTrabajo();
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST'://insert
        switch ($_REQUEST['operacion']) {
            case 'tfd':
                insertDiaTrabajo();
                break;
            case 'atu':
                addTrabajoUsuario();
                break;
            case 'it':
                insertTrabajo();
                break;
            case 'iu':
                insertUsuario();
                break;
        }
        break;
    case 'PUT'://update
    switch ($_REQUEST['operacion']) {
        case 'mus':
            insertDiaTrabajo();
            break;
    }
        echo 'Llamada hecha a traves de PUT';
    case 'GET'://select
        switch ($_REQUEST['operacion']) {
            case 'lg':
                isUsuario();
                break;
            case 'gt':
                getTrabajos();
                break;
            case 'gtu':
                $trabajosAsignados = $_REQUEST['trabajosAsignados'];
                getTrabajosUsuario();
                break;
        }
        break;
    case 'DELETE'://delete
        echo 'Llamada hecha a traves de DELETE';
        break;
    default:
        echo 'ERROR';
}


function insertTrabajo()
{
    $response = array();

    $conn = new mysqli('localhost', 'root', '', 'dambbdd');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $nombre = $_REQUEST["nombre"];
    $descripcion = $_REQUEST['descripcion'];

    $sql = "insert into trabajo (id, nombre, descripcion)
            values (default, '" . $nombre . "', '" . $descripcion . "')";

    if ($conn->query($sql) === true) {

        $trabajo[] = array();
        $trabajo['id'] = $conn->insert_id;
        $trabajo['nombre'] = $nombre;
        $trabajo['descripcion'] = $descripcion;

        $response["trabajo"] = $trabajo;

    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
    $response['response'] = true;
    // echo(json_encode($response));
    return json_encode($response);
// echoResponse(200, $response);
}

function getTrabajosUsuario()
{
// echo($_REQUEST['trabajosAsignados']);
    if ($_REQUEST['trabajosAsignados'] == "true") {
        
        //si son los trabajos asignados a un usuario se debera pasar por parametro 
        //el id del usuario
        $idUsuario = $_REQUEST['idUsuario'];
        $response = array();

        $conn = new mysqli('localhost', 'root', '', 'dambbdd');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "select t.id, t.nombre, t.descripcion 
        from trabajo_usuario tu, trabajo t 
        where t.id=tu.id_trabajo and tu.id_usuario=" . $idUsuario . " ;";

        $result = $conn->query($sql);

        $trabajos = array();
        if ($result->num_rows > 0) {
            $response['response'] = true;
            while ($row = $result->fetch_assoc()) {
                $trabajo['id'] = $row["id"];
                $trabajo['nombre'] = $row["nombre"];
                $trabajo['descripcion'] = $row["descripcion"];

                $trabajos[] = $trabajo;
            }
        } else {
            $response['response'] = false;
        }
        $response['trabajos'] = $trabajos;

        $conn->close();
        echo (json_encode($response));
        return json_encode($response);
    } else {
        $response = array();
        $idUsuario = $_REQUEST['idUsuario'];
        $conn = new mysqli('localhost', 'root', '', 'dambbdd');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "select * from trabajo t 
        where t.id not in (select id_trabajo from trabajo_usuario where id_usuario=" . $idUsuario . ") ;";

        $result = $conn->query($sql);

        $trabajos = array();
        if ($result->num_rows > 0) {
            $response['response'] = true;
            while ($row = $result->fetch_assoc()) {
                $trabajo['id'] = $row["id"];
                $trabajo['nombre'] = $row["nombre"];
                $trabajo['descripcion'] = $row["descripcion"];

                $trabajos[] = $trabajo;
            }
        } else {
            $response['response'] = false;
        }
        $response['trabajos'] = $trabajos;

        $conn->close();
        echo (json_encode($response));
        return json_encode($response);
    }
}

function getTrabajos()
{
    $response = array();

    $conn = new mysqli('localhost', 'root', '', 'dambbdd');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql;
    if (isset($_REQUEST['idTrabajo'])) {
        $sql = "select * from trabajo where id=" . $_REQUEST['idTrabajo'] . " ;";
    } else {
        $sql = "select * from trabajo ;";
    }

    $result = $conn->query($sql);

    $trabajos = array();
    if ($result->num_rows > 0) {
        // array_push($response, "response", true);
        // $response['response']=true;
        while ($row = $result->fetch_assoc()) {
            $trabajo['id'] = $row["id"];
            $trabajo['nombre'] = $row["nombre"];
            $trabajo['descripcion'] = $row["descripcion"];

            $trabajos[] = $trabajo;
        }
    } else {
        $response['response'] = false;
    }
    $response['trabajos'] = $trabajos;

    $conn->close();
    echo (json_encode($response));
    return json_encode($response);

}

function insertDiaTrabajo()
{ 
    //tfd
    $response = array();

    $conn = new mysqli('localhost', 'root', '', 'dambbdd');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //     $idUsuario = 25;
    //     $idTrabajo = 1;
    //     $jornadaContinua = true;
    //     $horaInicioManana = '9:00';
    //     $horaFinManana = '14:00';
    //     $comentario = 'xxx';
    //     $fecha = '2018-2-2';
    $idUsuario = $_REQUEST["idUsuario"];
    $idTrabajo = $_REQUEST['idTrabajo'];
    $jornadaContinua = $_REQUEST['jornadaContinua'];
    $horaInicioManana = $_REQUEST['horaInicioManana'];
    $horaFinManana = $_REQUEST['horaFinManana'];
    $comentario = $_REQUEST['comentario'];
    $fecha = $_REQUEST['fecha'];

    $horaInicioTarde;
    $horaFinTarde;

    $idTrabajoUsuario;

    
        
    //recoger de tabla trabajo_usuario el id, para asi hacer el insert
    //trabajo_usuario(id, id_trabajo, id_usuario)
    $sql = "select id from trabajo_usuario where id_usuario=" . $idUsuario . " "
        . "and id_trabajo=" . $idTrabajo . " ;";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $idTrabajoUsuario = $row['id'];
    }

    if ($jornadaContinua) {//JORNADA CONTINUA

        $sql = "insert into dia_trabajo (id, id_trabajo_usuario, hora_inicio_manana, hora_fin_manana, fecha, comentario) 
            values                      (default," . $idTrabajoUsuario . ", '" . $horaInicioManana . ":00','" . $horaFinManana . ":00',CURRENT_TIMESTAMP(), '" . $comentario . "' ) ;";
        $result = $conn->query($sql);
        if ($result) {
            $response['response'] = true;
        } else {
            $response['response'] = false;
        }
    } else {//JORNADA PARTIDA
//             $horaInicioTarde = '10:00';
//             $horaFinTarde = '14:00';
        $horaInicioTarde = $_REQUEST['horaInicioTarde'];
        $horaFinTarde = $_REQUEST['horaFinTarde'];
        $sql = "insert into dia_trabajo (id, id_trabajo_usuario, hora_inicio_manana, hora_fin_manana, hora_inicio_tarde, hora_fin_tarde, fecha, comentario) 
            values (default," . $idTrabajoUsuario . ",'" . $horaInicioManana . ":00','" . $horaFinManana . ":00' " .
            ",'" . $horaInicioTarde . ":00','" . $horaFinTarde . ":00','" . $fecha . "', '" . $comentario . "' ) ;";
        $result = $conn->query($sql);
        if ($result) {
            $response['response'] = true;
        } else {
            $response['response'] = false;
        }

    }


    $conn->close();
    echo (json_encode($response));
    return json_encode($response);//1-0
}

function insertUsuario()
{
    $response = array();

    $conn = new mysqli('localhost', 'root', '', 'dambbdd');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $nombre = $_REQUEST["nombre"];
    $apellidos = $_REQUEST['apellidos'];
    $rol = 'worker';
    $email = $_REQUEST["email"];
    $contrasena = $_REQUEST['contrasena'];

    $sql = "insert into usuario (id, nombre, apellidos, rol, email, contrasena)
            values (default, '" . $nombre . "', '" . $apellidos . "', '" . $rol . "', '" . $email . "', '" . $contrasena . "')";

    if ($conn->query($sql) === true) {

        $usuario[] = array();
        $usuario['id'] = $conn->insert_id;
        $usuario['nombre'] = $nombre;
        $usuario['apellidos'] = $apellidos;
        $usuario['email'] = $email;
        $usuario['rol'] = $rol;

        $response["usuario"] = $usuario;

    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
    $response['response'] = true;
    return json_encode($response);
    // echoResponse(200, $response);
}

function isUsuario()
{

    $response = array();

    $conn = new mysqli('localhost', 'root', '', 'dambbdd');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $email = $_REQUEST["usuario"];
    $contrasena = $_REQUEST['contrasena'];

    $sql = "select * from usuario where  email='" . $email . "' and contrasena='" . $contrasena . "' ;";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // array_push($response, "response", true);
        $response['response'] = true;
        while ($row = $result->fetch_assoc()) {
            $usuario['id'] = $row["id"];
            $usuario['nombre'] = $row["nombre"];
            $usuario['apellidos'] = $row["apellidos"];
            $usuario['email'] = $row["email"];
            $usuario['rol'] = $row["rol"];

            $response['usuario'] = $usuario;
        }
    } else {
        $response['response'] = false;
    }

    $conn->close();
    echo (json_encode($response));
    return json_encode($response);


}


function addTrabajoUsuario()
{
    $idTrabajo = $_REQUEST['idTrabajo'];
    $idUsuario = $_REQUEST['idUsuario'];

    $response = array();

    $conn = new mysqli('localhost', 'root', '', 'dambbdd');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "insert into trabajo_usuario (id, id_usuario, id_trabajo)
            values (default, " . $idUsuario . ", " . $idTrabajo . ")";

    if ($conn->query($sql) === true) {
    } else {
        $response['response'] = false;
        // echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $response['response'] = true;
    $conn->close();
    // echo(json_encode($response));
    return json_encode($response);
}
?>