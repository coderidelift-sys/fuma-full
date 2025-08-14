<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-0">
                <div class="text-center mb-6">
                    <h4 class="role-title mb-2 pb-0">Add New Role</h4>
                    <p>Set role permissions</p>
                </div>
                <!-- Add role form -->
                @include('_partials._forms.add_role')
                <!--/ Add role form -->
            </div>
        </div>
    </div>
</div>
