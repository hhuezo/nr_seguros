<div class="drawer-overlay" id="permissionsDrawerOverlay_{{ $rol->id }}"
    onclick="closePermissionsDrawer_{{ $rol->id }}({{ $rol->id }})"></div>

<!-- Drawer para Asignar Permisos -->
<div class="drawer drawer-permissions" id="permissionsDrawer_{{ $rol->id }}">
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
</div>

<script src="{{ asset('vendors/switchery/dist/switchery.min.js') }}"></script>
<script>
    function openPermissionsDrawer_{{ $rol->id }}(roleId) {
        var container = document.getElementById('permissionsContainer_{{ $rol->id }}');
        container.innerHTML = '<p class="text-center text-muted">Cargando permisos...</p>';

        document.getElementById('permissionsDrawer_{{ $rol->id }}').classList.add('active');
        document.getElementById('permissionsDrawerOverlay_{{ $rol->id }}').classList.add('active');
        document.body.style.overflow = 'hidden';

        fetch('{{ url('rol/permissions-html') }}/' + roleId, {
            method: 'GET',
            headers: {
                'Accept': 'text/html',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(function(response) {
            if (!response.ok) throw new Error('Error al cargar');
            return response.text();
        })
        .then(function(html) {
            container.innerHTML = html;
            var elems = container.querySelectorAll('.js-switch');
            elems.forEach(function(elem) {
                new Switchery(elem, { size: 'small' });
            });
        })
        .catch(function(error) {
            console.error('Error:', error);
            container.innerHTML = '<p class="text-center text-danger">Error al cargar los permisos</p>';
        });
    }

    function closePermissionsDrawer_{{ $rol->id }}(roleId) {
        document.getElementById('permissionsDrawer_{{ $rol->id }}').classList.remove('active');
        document.getElementById('permissionsDrawerOverlay_{{ $rol->id }}').classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    function updateRolePermission_{{ $rol->id }}(roleId, permissionId) {
        fetch('{{ url('role/permission_link') }}', {
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
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                if (data.action === 'assigned') {
                    toastr.success(data.message || 'Permiso asignado correctamente');
                } else if (data.action === 'removed') {
                    toastr.success(data.message || 'Permiso removido correctamente');
                } else {
                    toastr.success(data.message || 'Permiso actualizado correctamente');
                }
            } else {
                toastr.error(data.message || 'Error al actualizar el permiso');
                openPermissionsDrawer_{{ $rol->id }}(roleId);
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            toastr.error('Error al actualizar el permiso');
            openPermissionsDrawer_{{ $rol->id }}(roleId);
        });
    }
</script>
