<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: text/html; charset=utf-8');
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');


 insertDiaTrabajo();
//switch ($_SERVER['REQUEST_METHOD']) {
//   case 'POST'://insert
//       switch ($_REQUEST['operacion']) {
//           case 'tfd':
//               insertDiaTrabajo();
//               break;
//       }
//       break;
//   case 'PUT'://update
//
//       echo 'Llamada hecha a traves de PUT';
//   case 'GET'://select
//       isUsuario();
//       break;
//   case 'DELETE'://delete
//       echo 'Llamada hecha a traves de DELETE';
//       break;
//   default:
//       echo 'ERROR';
//}

function insertDiaTrabajo()
{ //tfd
    $response = array();

    $conn = new mysqli('localhost', 'root', '', 'dambbdd');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

     $idUsuario = 25;
     $idTrabajo = 1;
     $jornadaContinua = true;
     $horaInicioManana = '9:00';
     $horaFinManana = '14:00';
     $comentario = 'xxx';
     $fecha = '2018-2-2';
//    $idUsuario = $_REQUEST["idUsuario"];
//    $idTrabajo = $_REQUEST['idTrabajo'];
//    $jornadaContinua = $_REQUEST['jornadaContinua'];
//    $horaInicioManana = $_REQUEST['horaInicioManana'];
//    $horaFinManana = $_REQUEST['horaFinManana'];
//    $comentario = $_REQUEST['comentario'];
//    $fecha = $_REQUEST['fecha'];

    $horaInicioTarde;
    $horaFinTarde;

    $idTrabajoUsuario = 1;
//    $idTrabajoUsuario = $_REQUEST['idTrabajo'];

        //insert into dia_trabajo (id, id_trabajo_usuario, hora_inicio_manana, hora_fin_manana, hora_inicio_tarde, hora_fin_tarde, fecha, comentario) 
        //values(             default,      70,                 '20:15',           '21:15',           '22:00'  ,   '23:00'     , '2018-2-2'   , 'xxx');

        if ($jornadaContinua) {//JORNADA CONTINUA
            
            $sql = "insert into dia_trabajo (id, id_trabajo_usuario, id_usuario, hora_inicio_manana, hora_fin_manana, fecha, comentario) 
            values(default," . $idTrabajoUsuario . ",'".$idUsuario."," . $horaInicioManana . ":00','" . $horaFinManana .
                ":00','" . $fecha . "', '" . $comentario . "' ) ;";
            $result = $conn->query($sql);
            if($result){
                $response['response'] = true;
            }else{
                $response['response'] = false;
            }
        } else {//JORNADA PARTIDA
             $horaInicioTarde = '10:00';
             $horaFinTarde = '14:00';
//            $horaInicioTarde = $_REQUEST['horaInicioTarde'];
//            $horaFinTarde = $_REQUEST['horaFinTarde'];
            $sql = "insert into dia_trabajo (id, id_trabajo_usuario, hora_inicio_manana, hora_fin_manana, hora_inicio_tarde, hora_fin_tarde, fecha, comentario) 
            values (default," . $idTrabajoUsuario . ",'" . $horaInicioManana . ":00','" . $horaFinManana . ":00' " .
                ",'" . $horaInicioTarde . ":00','" . $horaFinTarde . ":00','" . $fecha . "', '" . $comentario . "' ) ;";
            $result = $conn->query($sql);
            if($result){
                $response['response'] = true;
            }else{
                $response['response'] = false;
            }
            
        }
    
//    echo($response['response']);
    $conn->close();
    return json_encode($response);//1-0
}


?>