<?php

use PHPMailer\PHPMailer\{PHPMailer, SMTP, Exception};

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST['submit'])) {

    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $asunto = $_POST['asunto'];
    $mensaje = $_POST['mensaje'];

    $errors = array();

    if (empty($nombre)) {
        $errors[] = 'El campo nombre es obligatorio';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'La dirección de correo electrónico no es válida';
    }

    if (empty($asunto)) {
        $errors[] = 'El campo asunto es obligatorio';
    }

    if (empty($mensaje)) {
        $errors[] = 'El campo mensaje es obligatorio';
    }

    if (count($errors) == 0) {

        $msj = "De: $nombre <a href='mailto:$email'>$email</a><br>";
        $msj .= "Asunto: $asunto<br><br>";
        $msj .= "Cuerpo del mensaje:";
        $msj .= '<p>' . $mensaje . '</p>';
        $msj .= "--<p>Este mensaje se ha enviado desde un formulario de contacto de Código de programación.</p>";

        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host = 'mail.dominio.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'correo@dominio.com';
            $mail->Password = 'TuPassword';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('correo@dominio.com', 'Emisor');
            $mail->addAddress('contacto@dominio.com', 'Receptor');
            //$mail->addReplyTo('otro@dominio.com');

            $mail->isHTML(true);
            $mail->Subject = 'Formulario de contacto';
            $mail->Body = utf8_decode($msj);

            $mail->send();

            $respuesta = 'Correo enviado';
        } catch (Exception $e) {
            $respuesta = 'Mensaje ' . $mail->ErrorInfo;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <main>
        <div class="container py-3">
            <header class="mb-4 border-bottom">
                <h1 class="fs-4">Contacto</h1>
            </header>

            <?php
            if (isset($errors)) {
                if (count($errors) > 0) {
            ?>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php
                                foreach ($errors as $error) {
                                    echo $error . '<br>';
                                }
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>

            <div class="row">
                <div class="col-lg-6 col-md-12">

                    <form class="form" method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" autocomplete="off">
                        <div class="mb-3">
                            <label for="nombre" class="form-label"> Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label"> Correo electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="asunto" class="form-label"> Asunto</label>
                            <input type="text" class="form-control" id="asunto" name="asunto" required>
                        </div>

                        <div class="mb-3">
                            <label for="mensaje" class="form-label"> Mensaje</label>
                            <textarea class="form-control" id="mensaje" name="mensaje" rows="3" required></textarea>
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary">Enviar</button>
                    </form>

                </div>
            </div>

            <?php if (isset($respuesta)) { ?>
                <div class="row py-3">
                    <div class="col-lg-6 col-md-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $respuesta; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <footer class="pt-3 mt-4 text-muted border-top">
                Códigos de Programación &copy; 2021
            </footer>

        </div>
    </main>
</body>

</html>