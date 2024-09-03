<?php

function getPlantillaOrden($numero_anexo, $observaciones, $nombre_colaborador, $cedula_colabo, $resultadosTabla)
{

    $contenidoPlantillaOrden = '
        <body>
            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="text-align: right;"><img src="../../../assets/image/logoAnexos.png" style="width: 240px;" alt=""></td>
                    </tr>
                </tbody>
            </table>
            <table style="border-collapse: collapse; width: 100%; margin-bottom: 12px;">
                <tbody>
                    <tr>
                        <td style="padding: 3px; text-align: center;">
                            <h3>ANEXO ' . $numero_anexo . '</h3>
                            <h4>DESCRIPCIÓN DE ACTIVIDADES Y PUNTOS</h4>
                        </td>
                    </tr>
                    <tr style="text-align: justify; margin-bottom: 12px;">
                        <td>
                            <p>La siguiente es la tabla con las que se liquidará la orden de servicio.</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="text-align: justify;">El cumplimiento de cada orden de servicio estará sujeto al cumplimiento de un número determinado de puntos, el cual estará repartido en actividades, es así como cada actividad dentro de la orden de servicios tendrá un valor diferente de puntos de la siguiente manera:</p>
                        </td>
                    </tr>
               </tbody>
            </table>
            <table style="border-collapse: collapse; width: 100%; border: 1px solid; margin-bottom: 12px;">
                <tbody>
                    <tr>
                        <td style="border: 1px solid; text-align: center;">
                            <h4>Actividades</h4>
                        </td>
                        <td style="border: 1px solid;  text-align: center;">
                            <h4>Puntos por mes</h4>
                        </td>
                    </tr>';

    foreach ($resultadosTabla as $resultado) {
        $contenidoPlantillaOrden .= '
                                                    <tr>
                                                        <td style="border: 1px solid; text-align: left; width: 80%;">' . $resultado['actividades'] . '</td>
                                                        <td style="border: 1px solid; text-align: center;">' . $resultado['puntos'] . '</td>
                                                    </tr>';
    }

    $contenidoPlantillaOrden .= '
                </tbody>
            </table>

            <!-- descripción -->
            <table style="width: 100%; margin-bottom: 12px;">
                <tbody>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <h5 style="text-align: center; margin-bottom: 12px;">CALCULO PARA EL CUMPLIMIENTO CUANTITATIVO DE LAS ORDENES DE SERVICIO</h5>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: justify;">
                            <p>
                                ' . $observaciones . '
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- --datos Firma- -->
            <table style=" width: 100%; margin-bottom: 24px;">
                <tbody>
                    <tr>
                        <td>
                            <table style=" width: 100%;">
                                <tbody>
                                    <tr>
                                        <td style="width: 9%; text-align: left;">NOMBRE: </td>
                                        <td>' . $nombre_colaborador . '</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 9%; text-align: left;">CÉDULA: </td>
                                        <td>' . $cedula_colabo . '</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- firma colaborador -->
            <table style="width: 100%; margin-bottom: 30px;">
                <tbody>
                    <tr>
                        <td>
                            <table style=" width: 100%;">
                                <tbody>
                                    <tr>
                                        <td style="width: 6%; text-align: left;">FIRMA:</td>
                                        <td>______________________</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- ----Información-- -->
            <table style="border-collapse: collapse; width: 100%;">
                <tbody>
                    <tr>
                        <td style="width: 30%;">
                            <table style="border-collapse: collapse; width: 100%;">
                                <tbody>
                                    <tr>
                                        <td>
                                            <img src="../../../assets/image/anexo/ISO.png" style="width: 70px;" alt="">
                                        </td>
                                        <td>
                                            <img src="../../../assets/image/anexo/Imagen2.png" style="width: 70px;" alt="">
                                        </td>
                                        <td>
                                            <img src="../../../assets/image/anexo/Imagen3.png" style="width: 70px;" alt="">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table style="border-collapse: collapse; width: 100%;">
                                <tbody>
                                    <tr>
                                        <td style="text-align: right;">
                                            <p style="font-size: 12px; margin: 0px;">Compañía Colombiana de Servicios de Valor Agregado y Telemáticos S.A E.S.P</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;">
                                            <p style="font-size: 12px; margin: 0px;">Diagonal 23k N° 96 F-62, Parque Empresarial la Cofradía, interior 2</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;">
                                            <p style="font-size: 12px; margin: 0px;">PBX: (601) 438 7000 – Bogotá D.C. Colombia </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;">
                                            <a href="www.colvatel.com.co" style="font-size: 12px; margin: 0px;">www.colvatel.com.co</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right;">
                                            <p style="font-size: 12px; margin: 0px;">M01.P02.F07. V3</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>

    </body>';
    return $contenidoPlantillaOrden;
}
