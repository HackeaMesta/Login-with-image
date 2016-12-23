<?php

/**
 * Created by PhpStorm.
 * User: rk521
 * Date: 9/10/16
 * Time: 04:59 PM
 */

include 'connector.php';

class Restore
{
    private $password;
    private $password2;
    private $conn;

    /**
     * Restore constructor.
     */
    public function __construct()
    {
        try {
            $db = new Connector();
            $this->conn = $db->DBConnection();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    public function restore($pass, $pass2) {
        $this->setPassword($pass);
        $this->setPassword2($pass2);

        if ($this->specialChars()) {
          if ($this->getPassword() === $this->getPassword2()) {
            if ($this->historicalPassword() == 0) {
              try {
                  $stmt = $this->conn->prepare("UPDATE users SET password = :password WHERE id = 1 ;");
                  $stmt->bindValue(":password", $this->getPassword(), PDO::PARAM_STR );

                  if ($stmt->execute()) {
                      $this->savePassword();
                      return "ok";
                  } else {
                      return "Ocurrio un error al actualizar";
                  }
              } catch (PDOException $e) {
                  return $e->getMessage();
              }
            } else {
              return "Anteriormente esta contraseña ya fue registrada. <br> <strong>Ingresa una contraseña diferente</strong>";
            }
          } else {
              return "Las contraseñas no coinciden";
          }
        } else {
          return "La contraseña solo puede contener numeros y letras";
        }
    }

    public function specialChars() {
      $pattern = "^[a-zA-Z0-9]*$^";
      if (!preg_match($pattern, $this->getPassword())) {
       return false;
      } else {
        return true;
      }
    }

    public function historicalPassword() {
      $result = $this->conn->query("SELECT password FROM historical_password WHERE password = '" . $this->getPassword() . "';")->fetchAll();
      return count($result);
    }

    public function savePassword() {
      try {
          $stmt = $this->conn->prepare("INSERT INTO historical_password VALUES (1, :hash);");
          $stmt->bindValue(":hash", $this->getPassword(), PDO::PARAM_STR );
          $stmt->execute();
      } catch (PDOException $e) {
          echo $e->getMessage();
      }
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword2()
    {
        return $this->password2;
    }

    /**
     * @param mixed $password2
     */
    public function setPassword2($password2)
    {
        $this->password2 = $password2;
    }
}

if (isset($_POST['btn-login'])) {
    if (!empty($_POST['ui_login_password']) || !empty($_POST['ui_login_password_2'])) {
        $password = htmlentities($_POST['ui_login_password']);
        $password = hash('sha256', $password);

        $password2 = htmlentities($_POST['ui_login_password_2']);
        $password2 = hash('sha256', $password2);

        $l = new Restore();
        echo $l->restore($password, $password2);

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
