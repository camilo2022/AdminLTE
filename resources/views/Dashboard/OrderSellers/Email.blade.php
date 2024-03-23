<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>A Simple Responsive HTML Email</title>
    <style>
        /* Estilos generales */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        /* Estilos de la tabla */
        .container {
            width: 100%;
            background-color: #f6f8f1;
        }
        .content {
            width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #ccc;
        }
        .header, .footer {
            background-color: #44525f;
            color: #ffffff;
            padding: 10px 20px;
        }
        .innerpadding {
            padding: 20px;
        }
        .h2 {
            font-size: 18px;
            margin-bottom: 15px;
        }
        .bodycopy {
            font-size: 14px;
            margin-bottom: 20px;
        }
        .footer p {
            margin-bottom: 10px;
        }
        /* Estilos específicos */
        .contact-info {
            margin-top: 10px;
        }
        .contact-info li {
            margin-bottom: 5px;
        }
        .contact-info b {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <table class="container" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <table class="content" align="center" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td class="header">
                            <!-- Aquí puedes colocar el logo de tu empresa si lo deseas -->
                        </td>
                    </tr>
                    <tr>
                        <td class="innerpadding borderbottom">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="h2">
                                        Estimado(a) Camilo Andres Acacio Gutierrez
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bodycopy">
                                        <p>Espero que este mensaje te encuentre bien. Queremos agradecerte por confiar en <b>MARIANGEL FULL MODA SAS</b> para tus necesidades de moda.</p>

                                        <p>Nos complace informarte que hemos recibido tu pedido con éxito. Adjunto a este correo electrónico, encontrarás un documento PDF que contiene los detalles completos de tu pedido, incluyendo los artículos seleccionados, cantidades, tamaños, colores y precios correspondientes.</p>

                                        <p>Revisaremos cuidadosamente la información proporcionada para garantizar la precisión de tu pedido.</p>

                                        <p>Sin embargo, si encuentras algún error o necesitas realizar algún cambio, te pedimos que nos contactes de inmediato a través de las siguientes vías:</p>
                                        
                                        <ul class="contact-info">
                                            <li>Teléfono: <b>5834481 - 3118800104 - 3114374088 - 3138092414</b></li>
                                            <li>Correo Electrónico: <b>mariangel.indu@hotmail.com</b></li>
                                        </ul>

                                        <p>Estaremos encantados de ayudarte y corregir cualquier problema que pueda haber surgido.</p>

                                        <p>Queremos expresarte nuestro más sincero agradecimiento por elegirnos y nos esforzaremos al máximo para garantizar tu completa satisfacción.</p>

                                        <p>Si necesitas cualquier otra cosa o tienes alguna pregunta adicional, no dudes en ponerte en contacto con nosotros. Estamos aquí para ayudarte en todo lo que necesites.</p>

                                        <p>Atentamente,</p>

                                        <p>MARIANGEL FULL MODA SAS</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="footer">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <p>© {{ Carbon::now()->format('Y') }} MARIANGEL FULL MODA SAS. Todos los derechos reservados.</p>
                                        <p>Este correo electrónico y su contenido están destinados únicamente para el uso del destinatario y pueden contener información confidencial y privilegiada. Si has recibido este mensaje por error, te rogamos que nos lo notifiques de inmediato y lo elimines de tu sistema. Cualquier divulgación, distribución o copia de este correo electrónico está estrictamente prohibida. MARIANGEL FULL MODA SAS no se hace responsable de los errores u omisiones en este mensaje ni de los daños derivados de la recepción o uso de este correo electrónico.</p>
                                        <p>Por favor, no respondas a este correo electrónico. Este mensaje se ha generado automáticamente y no se monitorea de manera activa. Si necesitas asistencia adicional o tienes alguna pregunta, por favor ponte en contacto con nosotros utilizando la información de contacto proporcionada anteriormente. Gracias.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>