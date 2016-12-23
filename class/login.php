<?php
/**
 * Created by @HackeaMesta
 */
session_start();
// DB Connection
include 'connector.php';

class LoginApi
{
    private $conn;
    private $user;
    private $pass;
    private $response;
    private $master_key;
    private $date_end;

    protected $expected = array();

    function __construct()
    {
        try {
            $db = new Connector();
            $this->conn = $db->DBConnection();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
        $this->response = array(
            '1' => 'Usuario y password incorrectos',
            '2' => 'ok',
            '3' => 'Usuario correcto, password incorrecto',
            '4' => 'Password correcto, usuario incorrecto',
            '5' => 'Login incorrecto',
            '6' => 'Cuenta bloqueada'
        );

        $this->setMasterKey("a05ac87e074e841c4c3ce1631523e4c314d1806e6b32696b2a5466c47cb387e5");
    }

    // Una vez que el user ingresa se actualiza la bandera
    function setSecondTime() {
      try {
          $stmt = $this->conn->prepare("UPDATE login_user SET first_type = 0 WHERE users_id = 1 ;");
          $stmt->execute();
      } catch (PDOException $e) {
          echo $e->getMessage();
      }
    }

    // Es la primer vez en el sistema?
    function isFirstTime() {
      $result = $this->conn->query("SELECT first_type FROM login_user WHERE users_id = 1;")->fetchAll();
      return $result[0]["first_type"];
    }

    // Debe mostrar el captcha?
    function showCaptcha() {
      $result = $this->conn->query("SELECT intents FROM login_user WHERE users_id = 1;")->fetchAll();
      if ($result[0]["intents"] >= 2) {
        return true;
      } else {
        return false;
      }
    }

    function returnMasterKey() {
      $result = $this->conn->query("SELECT tmp_key FROM login_user WHERE users_id = 1;")->fetchAll();
      return $result[0]["tmp_key"];
    }

    function createMasterKey() {
      try {
          $key = hash("sha256", rand(81099, 810999));
          $stmt = $this->conn->prepare("UPDATE login_user SET tmp_key = :hash WHERE users_id = 1 ;");
          $stmt->bindValue(":hash", $key, PDO::PARAM_STR );
          $stmt->execute();
      } catch (PDOException $e) {
          echo $e->getMessage();
      }
    }

    // Devuelve el numero de datos obtenido
    function getNumberData($user, $pass) {
        $result = $this->conn->query("SELECT * FROM users WHERE email = '" . $user . "' and password  = '" . $pass . "';")->fetchAll();
        return count($result);
    }

    // Todo...
    // Registra el intento de inicio de sesion
    function setIntentLogin() {
        $intents = $this->getIntentsLogin(1);
        $date = date("Y-m-d H:i:s");

        // Si intents == 0, lo actualiza a 1 y coloca la fecha de inicio
        if ($intents == 0) {
            try {
                $stmt = $this->conn->prepare("UPDATE login_user SET date_start = :date1, intents = 1 WHERE users_id = 1 ;");
                $stmt->bindValue(":date1", $date, PDO::PARAM_STR );
                $stmt->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        } elseif ($intents == 2) {
            // Si intentes == 3, verifica si la cuenta esta bloqueada, si no la bloquea, si esta bloqueada no hace nada
            if (!$this->isLocked(1)) {
                try {
                    //  Query para actualizar + fecha de bloqueo
                    $stmt = $this->conn->prepare("UPDATE login_user SET date_start = :date1, date_end = :date2  WHERE users_id = 1 ;");
                    $stmt->bindValue(":date1", $date, PDO::PARAM_STR);
                    $stmt->bindValue(":date2", $this->createDateBlock(), PDO::PARAM_STR);
                    $stmt->execute();
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            }
        } else {
            // Actualiza el intents
            $intents++;
            try {
                $stmt = $this->conn->prepare("UPDATE login_user SET intents = :intents WHERE users_id = 1 ;");
                $stmt->bindValue(":intents", $intents, PDO::PARAM_INT);
                $stmt->execute();
            } catch  (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }

    // Crea la fecha de bloqueo
    function createDateBlock() {
        $actualDate = date('Y-m-d H:i:s');
        $newDate = strtotime ( '+1 day' , strtotime ( $actualDate ) ) ;
        $newDate = date ( 'Y-m-d H:i:s' , $newDate );
        return $newDate;
    }

    // Devuelve el numero de intentos de inicio de sesion
    function getIntentsLogin($user) {
        $result = $this->conn->query("SELECT intents FROM login_user WHERE users_id = $user;")->fetchAll();
        return $result[0]["intents"];
    }

    // Valida el inicio de sesion mostrando errores errores
    function getLoginErrors($user, $pass) {
        $this->_setUser($user);
        //$this->setPassword(hash("sha256", $pass));
        $this->_setPassword($pass);

        // Actualiza el numero de intents
        $this->setIntentLogin();

        if ($this->isLocked(1)) {
            $val = $this->response[6] . ". <br> " . $this->date_end;
        } else {
            $val = ($this->getNumberDataError() == 1) ? $this->getSuccessError() : $this->response[1];
        }

        if ($val == $this->response[2]) {
            $_SESSION["token"] = $this->getMasterKey();
            $_SESSION["ytrfvbnjjhgfgb"] = md5(rand(0, 9999) * rand(0, 9999));
            session_write_close();
            $this->unlockAccount($user);
        }

        return $val;
    }

    // Extrae la fecha de bloqueo, si existe, la cuenta esta bloqueada
    function isLocked($user) {
        $result = $this->conn->query("SELECT date_end FROM login_user WHERE users_id =  $user ;")->fetchAll();
        $blockDate = $result[0]["date_end"];

        if ($blockDate) {
            $this->date_end = "Fecha de desbloqueo: <strong>" . $result[0]["date_end"] . "</strong>.";
            return true;
        } else {
            return false;
        }
    }

    // Devuelve true si es que el timpo de bloqueo ya expiro, false si aun no y procede a desbloquar
    function getUnlockAccount($user) {
        $actualDate = date('Y-m-d H:i:s');
        $actualDate = strtotime($actualDate);

        $result = $this->conn->query("SELECT date_end FROM login_user WHERE users_id = (SELECT id FROM users WHERE email = '" . $user . "');")->fetchAll();

        $blockDate = $result[0]["date_end"];
        $blockDate = strtotime($blockDate);

        if ($actualDate >= $blockDate) {
            // Desbloquea la cuenta
            $this->unlockAccount($user);
            $status = true;
        } else {
            $status = false;
        }

        return $status;
    }

    // Desbloquea la cuenta del usuario
    function unlockAccount($user) {
        try {
            $stmt = $this->conn->prepare("UPDATE login_user SET intents = 0, date_start = null, date_end = null  WHERE users_id = 1;");
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    // Valida el inicio de sesion, devuelve un mensaje
    function getSuccessError() {
        $status = ($this->getNumberData($this->user, $this->pass) == 1) ? $this->response[2] : $this->validateUserError() ;
        return $status;
    }

    // Devuelve el numero de datos obtenido
    function getNumberDataError() {
        $result = $this->conn->query("SELECT * FROM users WHERE email = '" . $this->user . "' or password  = '" . $this->pass . "';")->fetchAll();
        $this->setExpected($result[0]['email'], $result[0]['password']);
        return count($result);
    }

    // Valida el usuario
    function validateUserError() {
        $status = ($this->user == $this->expected['user']) ? $this->response[3] : $this->validatePasswordError() ;
        return $status;
    }

    // Valida la contrasena
    function validatePasswordError() {
        $status = ($this->pass == $this->expected['pass']) ? $this->response[4] : $this->response[5] ;
        return $status;
    }

    // Setter data esperada
    function setExpected($user, $pass) {
        $this->expected = array('user' => $user, 'pass' => $pass);
    }

    /**
     * Gets the value of user.
     *
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Gets the value of password.
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->pass;
    }

    /**
     * Sets the value of user.
     *
     * @param mixed $user the user
     *
     * @return self
     */
    private function _setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMasterKey()
    {
        return $this->master_key;
    }

    /**
     * @param mixed $master_key
     */
    public function setMasterKey($master_key)
    {
        $this->master_key = $master_key;
    }

    /**
     * Sets the value of password.
     *
     * @param mixed $password the password
     *
     * @return self
     */
    private function _setPassword($password)
    {
        $this->pass = $password;

        return $this;
    }
}
if (isset($_POST['btn-login'])) {
    if (!empty($_POST['ui_login_password']) || !empty($_POST['ui_login_username'])) {
        $password = htmlentities($_POST['ui_login_password']);
        $password = hash('sha256', $password);

        $user = htmlentities($_POST['ui_login_username']);
        $user = hash('sha256', $user);

        $l = new LoginApi();

        if (isset($_POST['g-recaptcha-response'])) {
          if ( !empty($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response'] !== null ) {
            echo $l->getLoginErrors($user, $password);
          } else {
            echo "Captcha incorrecto";
          }
        } else {
          echo $l->getLoginErrors($user, $password);
        }

    } else {
        if (empty($_POST['ui_login_password'])) {
            echo "Ingrese una contraseña";
        } elseif (empty($_POST['ui_login_username'])) {
            echo "Ingrese un usuario";
        } else {
            echo "Datos incorrectos";
        }
    }
}
/*
$l = new LoginApi();
if ($l->isFirstTime()) {
  echo "Primera vez";
  $l->setSecondTime();
} else {
  echo "Bienvenido de nuevo";
}
*/
