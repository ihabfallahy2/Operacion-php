<?php

class BBDD{

    static function conexion(){
        try {
            $bd = new PDO("mysql:host=localhost;dbname=dwes", "root", "");
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
        return $bd;
    }

    static function checkUser($user, $pass){
        $estado = 3;
        $sql = "SELECT * FROM usuarios " .
            "WHERE user='$user' " .
            "AND password='" . $pass . "'";
        $bd = BBDD::conexion();
        if ($datos = $bd->query($sql)) {
            $fila = $datos->fetch();
            if ($fila != null) {
                $estado = $fila['estado'];
            }
        }
        return $estado;
    }

    static function addUser($nombre, $pass, $email, $token){
        $bd = BBDD::conexion();
        $reg = 0;
        if ($bd != null) {
    
            try {
    
                $reg = conexion()->exec("INSERT INTO usuarios (user,password,email,token,estado,identidad) 
                 VALUES ('$nombre','$pass','$email','$token',3,'usuario')");
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
        $bd = null;
        return $reg;
    }

    static function validaUser($token){
        $reg = 0;
        $bd = BBDD::conexion();
        if ($bd != null) {
            $reg = $bd->exec("UPDATE usuarios SET estado=1
                    WHERE token='$token'");
    
        }
        $bd = null;
        return $reg;
    }
    
    static function select(){
        $array = [];
        $resultado = BBDD::conexion()->query("SELECT cod,nombre_corto, PVP FROM producto", PDO::FETCH_OBJ);
        while ($row = $resultado->fetch()) {
            $array[] = array("cod" => $row->cod, "nombre" => $row->nombre_corto, "precio" => $row->PVP);
        }
        return $array;
    }

    static function identidad($nombre){
        $identidad = "";
        $resultado = conexion()->query("SELECT identidad FROM usuarios where user='$nombre'", PDO::FETCH_OBJ);
        while ($row = $resultado->fetch()) {
            $identidad = $row;
        }
        return $identidad;
    }

    static function bloquear($nombre){
        $bd = BBDD::conexion();
        if ($bd != null) {
            $reg = $bd->exec("UPDATE usuarios SET estado=4 WHERE user='$nombre'");
            if ($reg) {
                return true;
            }
        }
    }

    static function email($nombre){

        $resultado = BBDD::conexion()->query("SELECT email FROM usuarios where user='$nombre'", PDO::FETCH_OBJ);
    
        $email = $resultado->fetch();
    
        return $email->email;
    }

    #construccion html en AvisoBloqueo() y enviaCorreoValidacion()

    static function AvisoBloqueo($nombre){

        $email = email($nombre);
        $to = $email;
        $subject = "Riesgo de seguridad de su Cuenta a nombre de $nombre";
        $mensaje = "<html><body><h1>PcTools</h1><p>Estimado Cliente,le informamos de que han habido varios intentos de ingreso fallidos a su cuenta y que por lo tanto hemos de decidido bloquear dicha cuenta.Si quiere recuperar la cuenta ingrese al Enlace postrior.</p>
                <p>Para mas informacion llamenos a nuestro numero <b>Atencion al cliente</b> que se encuentra en la pagina principal de nuestra tienda</p>
                <br><a href='http://192.168.18.146/Servidor/proyecto/PcTools/scr/php/recuperaCuenta.php'>ENLACE</a>
                <article>
                    <p>PcTools, Inteligencia y Confianza</p>
                    <footer>
                        <p>Copyright &copy; 1990-2021 <b>PcTools Inc.</b> , todos los derechos reservados.</p>
                    </footer>
                </article></body></html>";
        $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $enviado = mail($to, $subject, $mensaje, $cabeceras);
        return $enviado;
    }

    static function enviaCorreoValidacion($email, $token){
        $mensaje="<td class='esd-stripe' align='center'>
        <table bgcolor='#ffffff' class='es-content-body' align='center' cellpadding='0' cellspacing='0' width='700'>
            <tbody>
                <tr>
                    <td class='esd-structure es-p40t es-p20b es-p20r es-p20l' align='left' esd-custom-block-id='334499'>
                        <table cellpadding='0' cellspacing='0' width='100%'>
                            <tbody>
                                <tr>
                                    <td width='660' class='esd-container-frame' align='center' valign='top'>
                                        <table cellpadding='0' cellspacing='0' width='100%'>
                                            <tbody>
                                                <tr>
                                                    <td align='center' class='esd-block-image' style='font-size: 0px;'><a target='_blank'><img src='https://tlr.stripocdn.email/content/guids/CABINET_2663efe83689b9bda1312f85374f56d2/images/10381620386430630.png' alt style='display: block;' width='100'></a></td>
                                                </tr>
                                                <tr>
                                                    <td align='center' class='esd-block-text'>
                                                        <h2>Verifica tu correo para terminar de registrarte</h2>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align='center' class='esd-block-spacer es-p10t es-p10b es-m-txt-c' style='font-size:0'>
                                                        <table border='0' width='40%' height='100%' cellpadding='0' cellspacing='0' style='width: 40% !important; display: inline-table;'>
                                                            <tbody>
                                                                <tr>
                                                                    <td style='border-bottom: 1px solid #cccccc; background:none; height:1px; width:100%; margin:0px 0px 0px 0px;'></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align='center' class='esd-block-text es-p5t es-p5b es-p40r es-m-p0r' esd-links-underline='none'>
                                                        <p>Gracias por elegir IhabTecnologies®</p>
                                                        <p><br></p>
                                                        <p>Por favor confirma que <strong><a target='_blank' href='mailto:colin_washington@email.com' style='text-decoration: none;'>colin_washington@email.com</a></strong>&nbsp;es su dirección de correo electrónico haciendo clic en el botón de abajo con limite de <strong> 24 hours</strong>.</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align='center' class='esd-block-spacer es-p10t es-p10b es-m-txt-c' style='font-size:0'>
                                                        <table border='0' width='40%' height='100%' cellpadding='0' cellspacing='0' style='width: 40% !important; display: inline-table;'>
                                                            <tbody>
                                                                <tr>
                                                                    <td style='border-bottom: 1px solid #cccccc; background:none; height:1px; width:100%; margin:0px 0px 0px 0px;'></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align='center' class='esd-block-button es-p10t es-p10b es-m-txt-l'><span class='es-button-border' style='background: #ffffff;'><a href='http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]/scr/php/validar.php?token=$token' class='es-button' target='_blank' style='background: #ffffff; border-color: #ffffff;'>Verificar mi Email</a></span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>";
        $to = $email;
        $subject = "Validacion usuario";
        $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $enviado = mail($to, $subject, $mensaje, $cabeceras);
        return $enviado;
    }

}
