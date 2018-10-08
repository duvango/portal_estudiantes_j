<input type="button" class="btn btn-success" name="crear_usr" value="Crear usuario" onclick="DialogCrearUsuario()">
<!--<input type="button" class="btn btn-success" name="crear_usr_ldap" value="Agregar usuario LDAP" onclick="DialogCrearUsuarioLDAP()">-->
<br>
<br>


<table id="usuarios_t" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Nombre usuario</th>
            <th>Nombres</th>
            <th>Correo</th>
            <th>Directorio activo</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Nombre usuario</th>
            <th>Nombres</th>
            <th>Correo</th>
            <th>Directorio activo</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
    </tfoot>
</table>
<div id="dialog_n_usuario"></div>
<div id="dialog_n_usuario_ldap"></div>
<div id="dialog_edit_usuario"></div>
<script>
    var json = GridUsuarios();

    //console.log(json);


    $(document).ready(function () {
        $('#usuarios_t').DataTable({
            data: json,
            language: {
                url: "js/espanol.json"
            }
        });
    });
</script>