<?php
require_once CONTROL_PATH . 'Session.php';
$objss = new Session;
$objss->iniciar();
if (!$_SESSION['rol']) {
    $er = '2';
    $error = base64_encode($er);
    $salir = new Session;
    $salir->iniciar();
    $salir->outsession();
    header('Location:../login?er=' . $error);
    exit();
}
include_once VISTA_PATH . 'cabeza.php';
include_once VISTA_PATH . 'navegacion.php';
require_once CONTROL_PATH . 'padres' . DS . 'ControlPadres.php';
require_once CONTROL_PATH . 'admisiones' . DS . 'ControlAdmisiones.php';

$instancia = ControlPadres::singleton_padres();
$instancia_admisiones = ControlAdmisiones::singleton_admisiones();

$permisos = $instancia_permiso->permisosUsuarioControl(22, $perfil_log);

if (!$permisos) {
    include_once VISTA_PATH . DS . 'modulos' . DS . '403.php';
    exit();
}

if (isset($_GET['id_formato'])) {

    $id_formato = base64_decode($_GET['id_formato']);

    $formato = $instancia->mostrarDatosFormatoIdControl($id_formato);
    $hermanos = $instancia_admisiones->mostrarHermanosControl($formato['id_acudiente']);

?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-primary">
                            <a href="<?= BASE_URL ?>admisiones/index" class="text-decoration-none">
                                <i class="fa fa-arrow-left text-primary"></i>
                            </a>
                            &nbsp;
                            Proceso de admisión - Historia Clínica
                        </h4>
                    </div>
                    <div class="card-body">
                        <form class="p-3" method="POST">
                            <input type="hidden" value="<?= $id_log ?>" name="id_acudiente">
                            <input type="hidden" value="<?= $formato['id'] ?>" name="id_formato">
                            <!----------------------------------------------->
                            <h4 class="font-weight-bold mt-4">1. IDENTIFICACI&Oacute;N DEL ESTUDIANTE</h4>
                            <div class="row p-3">
                                <div class="form-group col-lg-4">
                                    <label>Nombre</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" required name="nombre_form" value="<?= $formato['nombre_form'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Fecha y lugar de nacimiento</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" required name="fecha_lug" value="<?= $formato['fecha_lug'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Edad</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" required name="edad_form" value="<?= $formato['edad_form'] ?>">
                                </div>
                            </div>
                            <!----------------------------------------------->

                            <!----------------------------------------------->
                            <h4 class="font-weight-bold mt-4">2. ESTRUCTURA FAMILIAR</h4>
                            <div class="row p-3">
                                <div class="form-group col-lg-4">
                                    <label>Nombre del padre</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="nombre_padre" value="<?= $formato['nombre_padre'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Edad</label>
                                    <input type="text" class="form-control" maxlength="3" minlength="1" name="edad_padre" value="<?= $formato['edad_padre'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Profesión</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="prof_padre" value="<?= $formato['prof_padre'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Ocupación</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="ocup_padre" value="<?= $formato['ocup_padre'] ?>">
                                </div>
                                <div class="col-lg-8"></div>
                                <div class="form-group col-lg-4">
                                    <label>Nombre del madre</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="nombre_madre" value="<?= $formato['nombre_madre'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Edad</label>
                                    <input type="text" class="form-control" maxlength="3" minlength="1" name="edad_madre" value="<?= $formato['edad_madre'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Profesión</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="prof_madre" value="<?= $formato['prof_madre'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Ocupación</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="ocup_madre" value="<?= $formato['ocup_madre'] ?>">
                                </div>
                                <div class="col-lg-8"></div>
                                <div class="form-group col-lg-6">
                                    <label>Relación entre los padres (tipo de unión)</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="tipo_union" value="<?= $formato['tipo_union'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Número de Hermanos</label>
                                    <input type="text" class="form-control" maxlength="3" minlength="1" name="cant_hermanos" id="cant_hermanos" value="<?= $formato['cant_hermanos'] ?>">
                                </div>
                                <div class="form-group col-lg-2 mt-4">
                                    <button type="button" class="btn btn-success mt-2" disabled id="button-agregar" data-tooltip="tooltip" title="Agregar hermano" data-toggle="modal" data-target="#agregar_hermano">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                                <div class="table-responsive mt-4">
                                    <table class="table table-hover table-sm" width="100%" cellspacing="0">
                                        <thead>
                                            <tr class="text-center font-weight-bold">
                                                <th scope="col">Nombre</th>
                                                <th scope="col">Edad</th>
                                                <th scope="col">Tipo de relaci&oacute;n</th>
                                            </tr>
                                        </thead>
                                        <tbody class="buscar text-lowercase" id="tabla_hermanos">
                                            <?php
                                            foreach ($hermanos as $row) {
                                            ?>
                                                <tr>
                                                    <td><?= $row['nombre'] ?></td>
                                                    <td><?= $row['edad'] ?></td>
                                                    <td><?= $row['tipo_rel'] ?></td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group col-lg-12">
                                <label>Otras personas significativas en casa</label>
                                <textarea name="per_sign" class="form-control" cols="30" rows="5"></textarea>
                            </div>
                            <div class="form-group col-lg-12">
                                <label class="font-weight-bold">Antecedentes familiares: (riesgos hereditarios)</label>
                                <br>
                                <label class="font-weight-bold">Físicos y orgánicos: (hipertensión, diabetes, epilepsia u otros)</label>
                                <br>
                                <label class="font-weight-bold">Psicológicos: (dificultades cognitivas, hiperactividad u otros)</label>
                                <textarea name="psicol" class="form-control" cols="30" rows="5"><?= $formato['psicol'] ?></textarea>
                            </div>
                            <div class="form-group col-lg-12">
                                <label>Algo que considere relevante que se deba tener en cuenta a nivel familiar con relación al niño</label>
                                <textarea name="nivel_familiar" class="form-control" cols="30" rows="5"><?= $formato['nivel_familiar'] ?></textarea>
                            </div>
                            <!----------------------------------------------->

                            <!----------------------------------------------->
                            <h4 class="font-weight-bold mt-4">3. EMBARAZO Y PARTO</h4>
                            <div class="row p-3">
                                <div class="form-group col-lg-4">
                                    <label>Embarazo (planeado, accidental)</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="embarazo" value="<?= $formato['embarazo'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Tiempo de gestación</label>
                                    <input type="text" class="form-control" maxlength="3" minlength="1" name="tiempo_gest" value="<?= $formato['tiempo_gest'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Parto (natural o cesárea)</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="parto" value="<?= $formato['parto'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Uso de fórceps</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="forcep" value="<?= $formato['forcep'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Dificultades durante el embarazo</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="dificultades" value="<?= $formato['dificultades'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>¿Requirió de oxígeno al nacer?</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="req_ox" value="<?= $formato['req_ox'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>¿Lloro inmediatamente al nacer?</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="lloro" value="<?= $formato['lloro'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>¿Presentó ictericia? (se puso amarillo)</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="ictericia" value="<?= $formato['ictericia'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>¿Sufrió de anoxia? (falta de oxígeno)</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="anoxia" value="<?= $formato['anoxia'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>¿Convulsionó?</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="convulsiono" value="<?= $formato['convulsiono'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>¿Presento erupciones en la piel?</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="erupciones" value="<?= $formato['erupciones'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>¿Permaneció más tiempo que la madre en el hospital?</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="tiempo_hos" value="<?= $formato['tiempo_hos'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Estado de salud de la madre</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="estado_madre" value="<?= $formato['estado_madre'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Estado emocional de la madre</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="estado_emocion_madre" value="<?= $formato['estado_emocion_madre'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Estado del ni&ntilde;o al nacer</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="estado_nino" value="<?= $formato['estado_nino'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Alimentación del ni&ntilde;o (seno, succión)</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="aliment_nino" value="<?= $formato['aliment_nino'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>¿Juega solo o junto a otros niños?</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="juega_nino" value="<?= $formato['juega_nino'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>¿Al tomar tetero, lo sostenía solo?</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="tetero" value="<?= $formato['tetero'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>¿Utiliza cuchara o tenedor para comer?</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="cuchara" value="<?= $formato['cuchara'] ?>">
                                </div>
                                <div class="form-group col-lg-12">
                                    <label>¿Cómo es la alimentación de su hijo? ¿Qué alimentos prefiere?</label>
                                    <textarea name="alimentacion_hijo" cols="30" rows="4" class="form-control"><?= $formato['alimentacion_hijo'] ?></textarea>
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>¿Tiene rabietas/pataletas a menudo?</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="rabietas" value="<?= $formato['rabietas'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>¿Llora fácilmente?</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="llora" value="<?= $formato['llora'] ?>">
                                </div>
                            </div>
                            <!----------------------------------------------->

                            <!----------------------------------------------->
                            <h4 class="font-weight-bold mt-4">4. DESARROLLO A EDAD TEMPRANA - ÁREA PSICOMOTORA </h4>
                            <h6 class="ml-4">Escriba SI en la opcion si su hijo realizó la acción y la edad, si la recuerda</h6>
                            <div class="row p-3">
                                <div class="form-group col-lg-4">
                                    <label>Sostenimiento de la cabeza</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="sostenimiento" value="<?= $formato['sostenimiento'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Sentarse por sí mismo</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="sentarse" value="<?= $formato['sentarse'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Equilibrio</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="equilibrio" value="<?= $formato['equilibrio'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Gateo</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="gateo" value="<?= $formato['gateo'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Caminar</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="caminar" value="<?= $formato['caminar'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Seguimiento ocular (seguir objetos con la mirada)</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="seguimiento" value="<?= $formato['seguimiento'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Agarre de pinza</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="agarre" value="<?= $formato['agarre'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Abotonarse</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="abotonarse" value="<?= $formato['abotonarse'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Recorte</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="recorte" value="<?= $formato['recorte'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Trazo</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="trazo" value="<?= $formato['trazo'] ?>">
                                </div>
                                <?php
                                $lateralidad_si = ($formato['lateralidad'] == 'derecha') ? 'checked' : '';
                                $lateralidad_no = ($formato['lateralidad'] == 'izquierda') ? 'checked' : '';
                                ?>
                                <div class="form-group col-lg-4">
                                    <label>Lateralidad</label>
                                    <div class="form-group mt-2">
                                        <label>
                                            Derecha
                                            &nbsp;
                                            <input type="radio" name="lateralidad" required value="derecha" <?= $lateralidad_si ?> id="lateralidad">
                                        </label>
                                        &nbsp;
                                        <label>
                                            Izquierdo
                                            &nbsp;
                                            <input type="radio" name="lateralidad" required value="izquierda" <?= $lateralidad_no ?> id="lateralidad">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!----------------------------------------------->

                            <!----------------------------------------------->
                            <h4 class="font-weight-bold mt-4">ÁREA COGNITIVA Y LENGUAJE </h4>
                            <div class="row p-3">
                                <div class="form-group col-lg-4">
                                    <label>Comprensión y seguimiento de instrucciones simples</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="comprension" value="<?= $formato['comprension'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Desarrollo del proceso de lectoescritura</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="lectoescritura" value="<?= $formato['lectoescritura'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Dominio de una segunda lengua</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="lengua" value="<?= $formato['lengua'] ?>">
                                </div>
                                <?php
                                $lenguaje_actual_si = ($formato['lenguaje_actual'] == 'fluido') ? 'checked' : '';
                                $lenguaje_actual_no = ($formato['lenguaje_actual'] == 'escaso') ? 'checked' : '';
                                ?>
                                <div class="form-group col-lg-4">
                                    <label>Su lenguaje actualmente es</label>
                                    <div class="form-group mt-2">
                                        <label>
                                            Fluido
                                            &nbsp;
                                            <input type="radio" name="lenguaje_actual" required value="fluido" <?= $lenguaje_actual_si ?> id="lenguaje_actual">
                                        </label>
                                        &nbsp;
                                        <label>
                                            Escaso
                                            &nbsp;
                                            <input type="radio" name="lenguaje_actual" required value="escaso" <?= $lenguaje_actual_no ?> id="lenguaje_actual">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!----------------------------------------------->

                            <!----------------------------------------------->
                            <h4 class="font-weight-bold mt-4">ÁREA PSICOAFECTIVA</h4>
                            <div class="row p-3">
                                <div class="form-group col-lg-4">
                                    <label>Actividad lúdica preferida</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="ludica" value="<?= $formato['ludica'] ?>">
                                </div>
                                <div class="form-group col-lg-12">
                                    <label>Expresión de afecto en las relaciones familiares</label>
                                    <textarea name="afecto" cols="30" rows="5" class="form-control"><?= $formato['afecto'] ?></textarea>
                                </div>
                                <div class="form-group col-lg-12">
                                    <label>Normas y consecuencias disciplinarias en el hogar</label>
                                    <textarea name="normas" cols="30" rows="5" class="form-control"><?= $formato['normas'] ?></textarea>
                                </div>
                                <div class="form-group col-lg-12">
                                    <label>¿Cómo reacciona frente a las consecuencias disciplinarias?</label>
                                    <textarea name="reaccion" cols="30" rows="5" class="form-control"><?= $formato['reaccion'] ?></textarea>
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Llora fácilmente</label>
                                    <input type="text" class="form-control" name="llora_fac" maxlength="60" minlength="1" value="<?= $formato['llora_fac'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Pataleta</label>
                                    <input type="text" class="form-control" name="pataleta" maxlength="60" minlength="1" value="<?= $formato['pataleta'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Agresividad</label>
                                    <input type="text" class="form-control" name="agresividad" maxlength="60" minlength="1" value="<?= $formato['agresividad'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Tics</label>
                                    <input type="text" class="form-control" name="tics" maxlength="60" minlength="1" value="<?= $formato['tics'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Fobias</label>
                                    <input type="text" class="form-control" name="fobias" maxlength="60" minlength="1" value="<?= $formato['fobias'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Mentiras</label>
                                    <input type="text" class="form-control" name="mentiras" maxlength="60" minlength="1" value="<?= $formato['mentiras'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Insomnio</label>
                                    <input type="text" class="form-control" name="insomnio" maxlength="60" minlength="1" value="<?= $formato['insomnio'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>¿Con quién duerme?</label>
                                    <input type="text" class="form-control" name="con_duerme" maxlength="60" minlength="1" value="<?= $formato['con_duerme'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Alimentación</label>
                                    <input type="text" class="form-control" name="alimentacion" maxlength="60" minlength="1" value="<?= $formato['alimentacion'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Dificultades estomacales</label>
                                    <input type="text" class="form-control" name="estomacal" maxlength="60" minlength="1" value="<?= $formato['estomacal'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Alergias</label>
                                    <input type="text" class="form-control" name="alergias" maxlength="60" minlength="1" value="<?= $formato['alergias'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Control de esfinteres (vesical y anal, diurno y nocturno)</label>
                                    <input type="text" class="form-control" name="esfinteres" maxlength="60" minlength="1" value="<?= $formato['esfinteres'] ?>">
                                </div>
                            </div>
                            <!----------------------------------------------->

                            <!----------------------------------------------->
                            <h4 class="font-weight-bold mt-4">5. HISTORIA ESCOLAR</h4>
                            <div class="row p-3">
                                <div class="form-group col-lg-4">
                                    <label>Edad de inicio de escolarización</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="edad_escolar" value="<?= $formato['edad_escolar'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Nombre del jardín o colegio</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="nombre_coleg" value="<?= $formato['nombre_coleg'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Adaptación</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="adaptacion" value="<?= $formato['adaptacion'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Relación con compañeros</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="rel_comp" value="<?= $formato['rel_comp'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Relación con profesores</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="rel_prof" value="<?= $formato['rel_prof'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Fortalezas a nivel académico (asignaturas)</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="fortaleza" value="<?= $formato['fortaleza'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Dificultades a nivel académico (asignaturas)</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="dif_academico" value="<?= $formato['dif_academico'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Refuerzo académico (si lo tuvo)</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="ref_academico" value="<?= $formato['ref_academico'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>A&ntilde;os perdidos, causas</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="anio_perd" value="<?= $formato['anio_perd'] ?>">
                                </div>
                            </div>
                            <!----------------------------------------------->

                            <!----------------------------------------------->
                            <h4 class="font-weight-bold mt-4">6. REMISIONES TERAPÉUTICAS</h4>
                            <h6 class="ml-4">Describa las razones por las que su hijo(a) requirió de intervención externa</h6>
                            <div class="row p-3">
                                <div class="form-group col-lg-4">
                                    <label>Neurodesarrollo</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="neurodesarrollo" value="<?= $formato['neurodesarrollo'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Fonoaudióloga</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="fono" value="<?= $formato['fono'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Psicología clínica</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="psico_clinica" value="<?= $formato['psico_clinica'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Psicología a nivel de aprendizaje</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="psico_aprend" value="<?= $formato['psico_aprend'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Terapia ocupacional</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="terapia" value="<?= $formato['terapia'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label>Otra</label>
                                    <input type="text" class="form-control" maxlength="60" minlength="1" name="otra" value="<?= $formato['otra'] ?>">
                                </div>
                            </div>
                            <!----------------------------------------------->

                            <!----------------------------------------------->
                            <h4 class="font-weight-bold mt-4">OBSERVACIONES</h4>
                            <div class="row p-3">
                                <div class="form-group col-lg-12">
                                    <textarea name="observacion" maxlength="1300" cols="30" rows="10" class="form-control"><?= $formato['observacion'] ?></textarea>
                                </div>
                            </div>
                            <!----------------------------------------------->

                            <div class="row p-1">
                                <div class="col-lg-6"></div>
                                <div class="col-lg-6 text-right">
                                    <button class="btn btn-success btn-sm" type="submit">
                                        <i class="fa fa-save"></i>
                                        &nbsp;
                                        Guardar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="agregar_hermano" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary">Agregar hermano</h5>
                </div>
                <form id="form_hermanos" method="POST">
                    <input type="hidden" name="id_log" value="<?= $id_log ?>">
                    <div class="modal-body border-0">
                        <div class="row p-3">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" class="form-control" maxlength="80" minlength="1" name="nombre">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Edad</label>
                                    <input type="text" class="form-control" maxlength="3" minlength="1" name="edad">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Tipo de relacion</label>
                                    <input type="text" class="form-control" maxlength="80" minlength="1" name="tipo_rel">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                            <i class="fa fa-times"></i>
                            &nbsp;
                            Cerrar
                        </button>
                        <button type="button" class="btn btn-success btn-sm enviar_datos">
                            <i class="fa fa-save"></i>
                            &nbsp;
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
    include_once VISTA_PATH . 'script_and_final.php';

    if (isset($_POST['id_formato'])) {
        $instancia->confirmarFormatoControl();
    }
}
?>
<script src="<?= PUBLIC_PATH ?>js/padres/preguntas.js"></script>