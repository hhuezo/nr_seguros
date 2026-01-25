<!-- Drawer para Modificar Usuario -->
<div class="drawer-overlay" id="editDrawerOverlay_{{ $usuario->id }}" onclick="closeEditDrawer({{ $usuario->id }})"></div>
<div class="drawer" id="editDrawer_{{ $usuario->id }}">
    <div class="drawer-header">
        <h4>Modificar Usuario</h4>
        <button type="button" class="drawer-close" onclick="closeEditDrawer({{ $usuario->id }})">
            <i class="fa fa-times"></i>
        </button>
    </div>
    <form id="editUserForm_{{ $usuario->id }}" method="POST">
        @method('PUT')
        @csrf
        <div class="drawer-body">
            <input type="hidden" name="user_id" value="{{ $usuario->id }}">
            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 8px; display: block;">Nombre</label>
                <input type="text" name="name" value="{{ $usuario->name }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 8px; display: block;">Correo</label>
                <input type="email" name="email" value="{{ $usuario->email }}" class="form-control" required onblur="this.value = this.value.toLowerCase();">
            </div>
            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 8px; display: block;">Clave <small>(dejar vacío para no cambiar)</small></label>
                <input type="text" name="password" class="form-control" minlength="8">
                <small class="text-muted">Mínimo 8 caracteres</small>
            </div>
        </div>
        <div class="drawer-footer">
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fa fa-save"></i> Guardar Cambios
            </button>
            <button type="button" class="btn btn-default btn-block" onclick="closeEditDrawer({{ $usuario->id }})" style="margin-top: 10px;">
                Cancelar
            </button>
        </div>
    </form>
</div>

<script>
    // Manejar el submit del formulario de edición para este usuario
    document.getElementById('editUserForm_{{ $usuario->id }}').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const userId = {{ $usuario->id }};

        fetch(`{{ url('usuario') }}/${userId}`, {
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
                toastr.success(data.message || 'Usuario actualizado correctamente');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                toastr.error(data.message || 'Error al actualizar el usuario');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error(error.message || 'Error al actualizar el usuario');
        });
    });
</script>
