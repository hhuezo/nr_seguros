{{-- Contenido del body del drawer de permisos. Se carga por AJAX desde rol/permissions-html/{id} --}}
<div class="permissions-container">
    @forelse($groupedPermissions as $groupName => $permissions)
        <div class="permissions-section-title">{{ $groupName }}</div>
        @foreach($permissions as $permission)
            <div class="permission-item">
                <input type="checkbox"
                    id="permission_{{ $permission['id'] }}_{{ $roleId }}"
                    class="js-switch"
                    data-switchery="true"
                    onchange="updateRolePermission_{{ $roleId }}({{ $roleId }}, {{ $permission['id'] }})"
                    @if(in_array($permission['id'], $rolePermissions)) checked @endif>
                <label class="control-label permission-item-label"
                    onclick="document.getElementById('permission_{{ $permission['id'] }}_{{ $roleId }}').click()">
                    {{ $permission['name'] }}
                </label>
            </div>
        @endforeach
    @empty
        <p class="text-center text-muted permissions-section-title">No hay permisos disponibles</p>
    @endforelse
</div>
