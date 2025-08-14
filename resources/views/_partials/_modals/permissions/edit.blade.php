<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-simple">
        <div class="modal-content p-4 p-md-12">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-md-0">
                <div class="text-center mb-6">
                    <h3 class="mb-2 pb-1">Edit Permission</h3>
                    <p>Edit permission as per your requirements.</p>
                </div>
                <div class="alert alert-warning" role="alert">
                    <h6 class="alert-heading mb-2">Warning</h6>
                    <p class="mb-0">
                        By editing the permission name, you might break the system permissions functionality. Please
                        ensure you're absolutely certain before proceeding.
                    </p>
                </div>
                <form id="editPermissionForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="row pt-2">
                        <div class="col-sm-9 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="editPermissionName" name="name" class="form-control"
                                    placeholder="Permission Name" required />
                                <label for="editPermissionName">Permission Name</label>
                            </div>
                        </div>
                        <div class="col-sm-3 mb-4 mt-1">
                            <button type="submit" class="btn btn-primary mt-1 mt-sm-0">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
