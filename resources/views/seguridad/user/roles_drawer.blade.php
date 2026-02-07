<style>
    .drawer-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1040;
        transition: opacity 0.3s ease;
    }

    .drawer-overlay.active {
        display: block;
    }

    .drawer {
        position: fixed;
        top: 0;
        right: -400px;
        width: 400px;
        height: 100%;
        background: #ffffff;
        box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
        z-index: 1050;
        transition: right 0.3s ease;
        overflow-y: auto;
    }

    .drawer.active {
        right: 0;
    }

    .drawer-header {
        padding: 20px;
        border-bottom: 1px solid #e8e8e8;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8f9fa;
    }

    .drawer-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }

    .drawer-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #6b7280;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .drawer-close:hover {
        color: #1f2937;
    }

    .drawer-body {
        padding: 20px;
    }

    .drawer-footer {
        padding: 20px;
        border-top: 1px solid #e8e8e8;
        background: #f8f9fa;
    }

    .role-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px;
    border: 1px solid #f0f0f0;
    border-radius: 4px;
    background: #fafafa;

    }

    .role-item:last-child {
        border-bottom: none;
    }

    .role-item label {
        margin: 0;
        font-weight: normal;
        cursor: pointer;
    }
</style>

<!-- Overlay del drawer -->
<div class="drawer-overlay" id="rolesDrawerOverlay_{{ $usuario->id }}" onclick="closeRolesDrawer_{{ $usuario->id }}({{ $usuario->id }})"></div>

<!-- Drawer para Asignar Roles -->
<div class="drawer" id="rolesDrawer_{{ $usuario->id }}">
    <div class="drawer-header">
        <h4>Asignar Roles</h4>
        <button type="button" class="drawer-close" onclick="closeRolesDrawer_{{ $usuario->id }}({{ $usuario->id }})">
            <i class="fa fa-times"></i>
        </button>
    </div>
    <div class="drawer-body">
        <input type="hidden" id="roles_user_id_{{ $usuario->id }}" value="{{ $usuario->id }}">
        <div id="rolesContainer_{{ $usuario->id }}">
            <p class="text-center text-muted">Cargando roles...</p>
        </div>
    </div>
    <div class="drawer-footer">
        <button type="button" class="btn btn-default btn-block" onclick="closeRolesDrawer_{{ $usuario->id }}({{ $usuario->id }})">
            Cerrar
        </button>
    </div>
</div>

<script src="{{ asset('vendors/switchery/dist/switchery.min.js') }}"></script>
<script>
    function openRolesDrawer_{{ $usuario->id }}(userId) {
        document.getElementById('rolesContainer_{{ $usuario->id }}').innerHTML = '<p class="text-center text-muted">Cargando roles...</p>';
        
        document.getElementById('rolesDrawer_{{ $usuario->id }}').classList.add('active');
        document.getElementById('rolesDrawerOverlay_{{ $usuario->id }}').classList.add('active');
        document.body.style.overflow = 'hidden';

        // Cargar roles del usuario
        fetch(`{{ url('usuario/get-roles') }}/${userId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let html = '';
                if (data.roles && data.roles.length > 0) {
                    data.roles.forEach(role => {
                        const isChecked = data.userRoles.includes(role.id) ? 'checked' : '';
                        html += `
                            <div class="role-item card" style="margin-bottom: 10px;">
                                <input type="checkbox" 
                                    id="role_${role.id}_${userId}"
                                    class="js-switch" 
                                    data-switchery="true"
                                    onchange="updateUserRole_{{ $usuario->id }}(${userId}, ${role.id})"
                                    ${isChecked}>
                                <label class="control-label" style="margin-bottom: 0; cursor: pointer; margin-left: 10px;" onclick="document.getElementById('role_${role.id}_${userId}').click()">${role.name}</label>
                            <br>
                            </div>
                        `;
                    });
                } else {
                    html = '<p class="text-center text-muted">No hay roles disponibles</p>';
                }
                document.getElementById('rolesContainer_{{ $usuario->id }}').innerHTML = html;
                
                // Inicializar Switchery para los nuevos elementos
                var elems = document.querySelectorAll('#rolesContainer_{{ $usuario->id }} .js-switch');
                elems.forEach(function(elem) {
                    var switchery = new Switchery(elem, { size: 'small' });
                });
            } else {
                document.getElementById('rolesContainer_{{ $usuario->id }}').innerHTML = '<p class="text-center text-danger">Error al cargar los roles</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('rolesContainer_{{ $usuario->id }}').innerHTML = '<p class="text-center text-danger">Error al cargar los roles</p>';
        });
    }

    function closeRolesDrawer_{{ $usuario->id }}(userId) {
        document.getElementById('rolesDrawer_{{ $usuario->id }}').classList.remove('active');
        document.getElementById('rolesDrawerOverlay_{{ $usuario->id }}').classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    function updateUserRole_{{ $usuario->id }}(userId, roleId) {
        const url = new URL('{{ url('usuario/rol_link') }}');
        url.searchParams.append('user_id', userId);
        url.searchParams.append('role_id', roleId);

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                user_id: userId,
                role_id: roleId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar mensaje según la acción (asignar o remover)
                if (data.action === 'assigned') {
                    toastr.success(data.message || 'Rol asignado correctamente');
                } else if (data.action === 'removed') {
                    toastr.success(data.message || 'Rol removido correctamente');
                } else {
                    toastr.success(data.message || 'Rol actualizado correctamente');
                }
            } else {
                toastr.error(data.message || 'Error al actualizar el rol');
                // Recargar los roles para revertir el cambio
                openRolesDrawer_{{ $usuario->id }}(userId);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Error al actualizar el rol');
            // Recargar los roles para revertir el cambio
            openRolesDrawer_{{ $usuario->id }}(userId);
        });
    }
</script>
