<!--Agregar usuario-->
<div class="modal fade" id="agregar_usuario" tabindex="-1" role="modal" aria-hidden="true" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg p-2" role="document">
        <div class="modal-content">
            <form method="POST" id="form_enviar">
                <input type="hidden" value="<?=$_SESSION['super_empresa']?>" name="super_empresa">
                <input type="hidden" value="<?=$_SESSION['id']?>" name="id_log">
                <div class="modal-header p-3">
                    <h4 class="modal-title text-hebreo font-weight-bold">Agregar Usuario</h4>
                </div>
                <div class="modal-body border-0">
                    <div class="row  p-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Documento <span class="text-danger">*</span></label>
                                <input type="text" class="form-control numeros" name="documento" id="doc_user" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control letras" name="nombre" id="nombre" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Apellido</label>
                                <input type="text" class="form-control letras" name="apellido" id="apellido">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Telefono</label>
                                <input type="text" class="form-control numeros" name="telefono" id="telefono">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Correo <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="correo" id="correo" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Asignatura</label>
                                <input type="text" class="form-control" name="asignatura" id="asignatura">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Usuario <span class="text-danger">*</span></label>
                                <input type="text" class="form-control user" name="usuario" id="user_name" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Contrase&ntilde;a <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password" id="password" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Confirmar Contrase&ntilde;a <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="conf_password" id="conf_password" required>
                            </div>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Nivel <span class="text-danger">*</span></label>
                            <select name="nivel" id="nivel" class="form-control" required>
                                <option value="" selected>Seleccione una opcion...</option>
                                <?php
                                foreach ($datos_nivel as $nivel) {
                                    $id_nivel  = $nivel['id'];
                                    $nom_nivel = $nivel['nombre'];
                                    ?>
                                    <option value="<?=$id_nivel?>"><?=$nom_nivel?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Curso</label>
                            <select name="curso" class="form-control">
                                <option value="" selected>Seleccione una opcion...</option>
                                <?php
                                foreach ($datos_curso as $curso) {
                                    $id_curso  = $curso['id'];
                                    $nom_curso = $curso['nombre'];
                                    ?>
                                    <option value="<?=$id_curso?>"><?=$nom_curso?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Perfil <span class="text-danger">*</span></label>
                                <select class="form-control" name="perfil" id="perfil" required>
                                    <option selected value="">Seleccione una opcion...</option>
                                    <?php
                                    foreach ($datos_perfil as $perfiles) {
                                        $id_perfil  = $perfiles['id_perfil'];
                                        $nom_perfil = $perfiles['nombre'];

                                        if ($id_perfil != 1) {
                                            ?>
                                            <option value="<?=$id_perfil?>"><?=$nom_perfil?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button class="btn btn-danger btn-sm" data-dismiss="modal">
                        <i class="fa fa-times"></i>
                        &nbsp;
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-hebreo btn-sm" id="enviar_datos">
                        <i class="fa fa-save"></i>
                        &nbsp;
                        Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>