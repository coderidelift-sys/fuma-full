@if ($id != 1)
    <div class="d-flex align-items-center gap-2">
        <a href="javascript:;" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect delete-record"
            data-id="{{ $id }}" data-bs-toggle="tooltip" title="Delete User">
            <i class="ri-delete-bin-7-line ri-20px"></i>
        </a>
        <a href="{{ route('users.show', $id) }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect"
            data-bs-toggle="tooltip" title="Preview">
            <i class="ri-eye-line ri-20px"></i>
        </a>
        <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect dropdown-toggle hide-arrow"
            data-bs-toggle="dropdown">
            <i class="ri-more-2-line ri-20px"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end m-0">
            <a href="{{ route('users.show', $id) }}" class="dropdown-item">
                <i class="ri-eye-line me-2"></i><span>View</span>
            </a>
            <a href="{{ route('users.edit', $id) }}" class="dropdown-item">
                <i class="ri-edit-box-line me-2"></i><span>Edit</span>
            </a>
            <a href="javascript:;" class="dropdown-item delete-record" data-id="{{ $id }}">
                <i class="ri-delete-bin-7-line me-2"></i><span>Delete</span>
            </a>
        </div>
    </div>
@endif
