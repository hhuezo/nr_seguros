<!-- Drawer para Modificar Rol -->
<div class="drawer-overlay" id="editDrawerOverlay_{{ $rol->id }}" onclick="closeEditDrawer({{ $rol->id }})"></div>
<div class="drawer" id="editDrawer_{{ $rol->id }}">
    <div class="drawer-header">
        <h4>Modificar Rol</h4>
        <button type="button" class="drawer-close" onclick="closeEditDrawer({{ $rol->id }})">
            <i class="fa fa-times"></i>
        </button>
    </div>
    <form id="editRoleForm_{{ $rol->id }}" method="POST">
        @method('PUT')
        @csrf
        <div class="drawer-body">
            <input type="hidden" name="role_id" value="{{ $rol->id }}">
            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 8px; display: block;">Nombre</label>
                <input type="text" name="name" value="{{ $rol->name }}" class="form-control" required>
            </div>
        </div>
        <div class="drawer-footer">
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fa fa-save"></i> Guardar Cambios
            </button>
            <button type="button" class="btn btn-default btn-block" onclick="closeEditDrawer({{ $rol->id }})" style="margin-top: 10px;">
                Cancelar
            </button>
        </div>
    </form>
</div>

<script>
    // Manejar el submit del formulario de edición para este rol
    document.getElementById('editRoleForm_{{ $rol->id }}').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const roleId = {{ $rol->id }};

        fetch(`{{ url('rol') }}/${roleId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': 'PUT',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Error en el servidor');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                toastr.success(data.message || 'Rol actualizado correctamente');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                toastr.error(data.message || 'Error al actualizar el rol');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error(error.message || 'Error al actualizar el rol');
        });
    });
</script>
