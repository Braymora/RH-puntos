<?php

function getPlantillaOrden($numeroOrden, $nombre_proyecto, $cedula, $cantidad_puntos, $ceco, $nombre_colaborador, $nombre_cargo, $fecha_inicio, $fecha_fin, $justificacion, $observaciones)
{
  $contenidoPlantillaOrden = '
  <body style="font-family: Arial, sans-serif;">

    <table style="width: 100%;">
      <tr>
        <td style="width: 20%;">
          <img src="../../../assets/image/logoFormto-ORDEN.png" alt="Logo" style="max-width: 100%;">
        </td>
        <td style="width: 60%;">
          <p style="font-size: 14px; font-weight: bold;">
            A05.P03. F08. ORDEN DE SERVICIO CONTRATO POR OBRA O LABOR
            DETERMINADA
          </p>
        </td>
        <td style="width: 20%;">
          <table style="width: 100%;">
            <tr>
              <td style="font-weight: bold;">Código</td>
              <td>A05.P03.F08</td>
            </tr>
            <tr>
              <td style="font-weight: bold;">Versión</td>
              <td>1</td>
            </tr>
            <tr>
              <td style="font-weight: bold;">Aprobación</td>
              <td>10/01/2020</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

    <table style="width: 100%; margin-top: 20px;">
      <tr>
        <td style="width: 10%; font-weight: bold;">Nro</td>
        <td style="width: 90%;">' . $numeroOrden . '</td>
      </tr>
    </table>

    <table style="width: 100%; margin-top: 20px;">
      <tr>
        <td style="width: 50%;">
          <table style="width: 100%;">
            <tr>
              <td style="font-weight: bold; font-size: 12px">PROYECTO:</td>
              <td>' . $nombre_proyecto . '</td>
            </tr>
            <tr>
              <td style="font-weight: bold;font-size: 12px;">No. CONT. COMERCIAL:</td>
              <td>' . $nombre_cargo . '</td>
            </tr>
            <tr>
              <td style="font-weight: bold;font-size: 12px;">DTO. IDENTIFICACIÓN:</td>
              <td>' . $cedula . '</td>
            </tr>
            <tr>
              <td style="font-weight: bold;font-size: 12px;">CANTIDAD PUNTOS:</td>
              <td>' . $cantidad_puntos . '</td>
            </tr>
          </table>
        </td>
        <td style="width: 50%;">
          <table style="width: 100%;">
            <tr>
              <td style="font-weight: bold;font-size: 12px;">CENTRO DE COSTO:</td>
              <td>' . $ceco . '</td>
            </tr>
            <tr>
              <td style="font-weight: bold;font-size: 12px;">NOMBRE EMPLEADO:</td>
              <td>' . $nombre_colaborador . '</td>
            </tr>
            <tr>
              <td style="font-weight: bold;font-size: 12px;">CARGO:</td>
              <td>' . $nombre_cargo . '</td>
            </tr>
            <tr>
              <td style="font-weight: bold;font-size: 12px;">FECHA DE INICIO LABOR PROPUESTA:</td>
              <td>' . $fecha_inicio . '</td>
            </tr>
            <tr>
              <td style="font-weight: bold;font-size: 12px;">FECHA FIN:</td>
              <td>' . $fecha_fin . '</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

    <table style="width: 100%; margin-top: 20px;">
      <tr>
        <td style="font-weight: bold;">Justificación y detalle del requerimiento:</td>
      </tr>
      <tr>
        <td>
        ' . $justificacion . '
        </td>
      </tr>
    </table>

    <table style="width: 100%; margin-top: 20px;">
      <tr>
        <td style="font-weight: bold;">Observaciones:</td>
      </tr>
      <tr>
        <td>' . $observaciones . '</td>
      </tr>
    </table>

    <table style="width: 100%; margin-top: 20px;">
      <tr>
        <td style="width: 50%;">
          <table style="width: 100%;">
            <tr>
              <td>
                <hr style="border: 0.5px solid #000;">
              </td>
            </tr>
            <tr>
              <td>' . $nombre_colaborador . '</td>
            </tr>
            <tr>
              <td>
                <table style="width: 100%;">
                  <tr>
                    <td style="font-weight: bold;">CC:</td>
                    <td>' . $cedula . '</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
        <td style="width: 50%;">
          <table style="width: 100%;">
            <tr>
              <td>
                <hr style="border: 0.5px solid #000;">
              </td>
            </tr>
            <tr>
              <td>Fecha firma</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

  </body>
  ';

  return $contenidoPlantillaOrden;
}
?>
