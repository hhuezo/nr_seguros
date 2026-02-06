

<div class="drawer-overlay" id="permissionsDrawerOverlay_{{ $rol->id }}"
    onclick="closePermissionsDrawer_{{ $rol->id }}({{ $rol->id }})"></div>

<!-- Drawer para Asignar Permisos -->
<div class="drawer drawer-permissions" s id="permissionsDrawer_{{ $rol->id }}">
    <div class="drawer-header">
        <h5><strong>Asignar Permisos - {{ $rol->name }}</strong></h5>
        <button type="button" class="drawer-close"
            onclick="closePermissionsDrawer_{{ $rol->id }}({{ $rol->id }})">
            <i class="fa fa-times"></i>
        </button>
    </div>
    <div class="drawer-body">
        <input type="hidden" id="permissions_role_id_{{ $rol->id }}" value="{{ $rol->id }}">
        <div id="permissionsContainer_{{ $rol->id }}">
            <p class="text-center text-muted">Cargando permisos...</p>
        </div>
    </div>
    {{-- <div class="drawer-footer">
        <button type="button" class="btn btn-default btn-block" onclick="closePermissionsDrawer_{{ $rol->id }}({{ $rol->id }})">
            Cerrar
        </button>
    </div> --}}
</div>

<script src="{{ asset('vendors/switchery/dist/switchery.min.js') }}"></script>
<script>
    function openPermissionsDrawer_{{ $rol->id }}(roleId) {
        document.getElementById('permissionsContainer_{{ $rol->id }}').innerHTML =
            '<p class="text-center text-muted">Cargando permisos...</p>';

        document.getElementById('permissionsDrawer_{{ $rol->id }}').classList.add('active');
        document.getElementById('permissionsDrawerOverlay_{{ $rol->id }}').classList.add('active');
        document.body.style.overflow = 'hidden';

        // Cargar permisos del rol
        fetch(`{{ url('rol/get-permissions') }}/${roleId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let html = '<div class="permissions-container">';
                    // Verificar si data.permissions es un objeto (agrupado) y tiene claves
                    if (data.permissions && Object.keys(data.permissions).length > 0) {
                        // Iterar sobre los grupos (claves del objeto)
                        for (const [groupName, permissions] of Object.entries(data.permissions)) {
                            // Agregar encabezado del grupo
                            html += `<div class="col-12"><h5 class="mt-3 mb-2 font-weight-bold" style="border-bottom: 2px solid #eee; padding-bottom: 5px;">${groupName}</h5></div>`;
                            
                            // Iterar sobre los permisos del grupo
                            permissions.forEach(permission => {
                                const isChecked = data.rolePermissions.includes(permission.id) ? 'checked' : '';
                                html += `
                                <div class="permission-item">
                                    <input type="checkbox"
                                        id="permission_${permission.id}_${roleId}"
                                        class="js-switch"
                                        data-switchery="true"
                                        onchange="updateRolePermission_{{ $rol->id }}(${roleId}, ${permission.id})"
                                        ${isChecked}>
                                    <label class="control-label" style="margin-bottom: 0; cursor: pointer; margin-left: 10px;" onclick="document.getElementById('permission_${permission.id}_${roleId}').click()">${permission.name}</label>
                                </div>
                            `;
                            });
                        }
                    } else {
                        html = '<p class="text-center text-muted">No hay permisos disponibles</p>';
                    }
                    html += '</div>';
                    document.getElementById('permissionsContainer_{{ $rol->id }}').innerHTML = html;

                    // Inicializar Switchery para los nuevos elementos
                    var elems = document.querySelectorAll('#permissionsContainer_{{ $rol->id }} .js-switch');
                    elems.forEach(function(elem) {
                        var switchery = new Switchery(elem, {
                            size: 'small'
                        });
                    });
                } else {
                    document.getElementById('permissionsContainer_{{ $rol->id }}').innerHTML =
                        '<p class="text-center text-danger">Error al cargar los permisos</p>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('permissionsContainer_{{ $rol->id }}').innerHTML =
                    '<p class="text-center text-danger">Error al cargar los permisos</p>';
            });
    }

    function closePermissionsDrawer_{{ $rol->id }}(roleId) {
        document.getElementById('permissionsDrawer_{{ $rol->id }}').classList.remove('active');
        document.getElementById('permissionsDrawerOverlay_{{ $rol->id }}').classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    function updateRolePermission_{{ $rol->id }}(roleId, permissionId) {
        const url = new URL('{{ url('role/permission_link') }}');
        url.searchParams.append('permission_id', permissionId);
        url.searchParams.append('role_id', roleId);

        fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    permission_id: permissionId,
                    role_id: roleId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje según la acción (asignar o remover)
                    if (data.action === 'assigned') {
                        toastr.success(data.message || 'Permiso asignado correctamente');
                    } else if (data.action === 'removed') {
                        toastr.success(data.message || 'Permiso removido correctamente');
                    } else {
                        toastr.success(data.message || 'Permiso actualizado correctamente');
                    }
                } else {
                    toastr.error(data.message || 'Error al actualizar el permiso');
                    // Recargar los permisos para revertir el cambio
                    openPermissionsDrawer_{{ $rol->id }}(roleId);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('Error al actualizar el permiso');
                // Recargar los permisos para revertir el cambio
                openPermissionsDrawer_{{ $rol->id }}(roleId);
            });
    }
</script>
