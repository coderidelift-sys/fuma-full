@if ($id != 1)
    <div class="d-flex align-items-center">
        <!-- Edit Button -->
        <button class="btn btn-sm btn-icon btn-text-secondary text-body rounded-pill waves-effect waves-light"
            data-bs-target="#editPermissionModal" data-bs-toggle="modal" data-bs-dismiss="modal"
            data-bs-id="{{ $id }}" data-bs-name="{{ $name }}">
            <i class="ri-edit-box-line ri-20px"></i>
        </button>

        <!-- Delete Button -->
        <form id="delete-form-{{ $id }}" class="delete-form d-inline-block" method="POST"
            action="{{ route('permissions.destroy', $id) }}">
            @csrf
            @method('DELETE')
            <button type="button"
                class="btn btn-sm btn-icon btn-text-secondary rounded-pill text-body waves-effect waves-light me-1 delete-record">
                <i class="ri-delete-bin-7-line ri-20px"></i>
            </button>
        </form>
    </div>
@endif
