<form id="addRoleForm" class="row g-3" onsubmit="return false" action="{{ route('roles.store') }}" method="POST">
    @csrf
    <div class="col-12 mb-3">
        <div class="form-floating form-floating-outline">
            <input type="text" id="name" name="name" class="form-control" placeholder="Enter a role name"
                tabindex="-1" />
            <label for="name">Role Name</label>
        </div>
    </div>
    <div class="col-12">
        <h5 class="mb-6">Role Permissions</h5>
        <!-- Permission table -->
        <div class="table-responsive">
            <table class="table table-flush-spacing">
                <thead>
                    <tr>
                        <th class="text-nowrap fw-medium">
                            Administrator Access
                            <i class="ri-information-line" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Allows full access to the system"></i>
                        </th>
                        <th>
                            <div class="d-flex justify-content-end">
                                <div class="form-check mb-0 mt-1">
                                    <input class="form-check-input" type="checkbox" id="selectAll"
                                        name="all_permissions" value="1" />
                                    <label class="form-check-label" for="selectAll">Select
                                        All</label>
                                </div>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $header => $permissionItem)
                        <tr>
                            <td colspan="2">
                                <div class="accordion" id="accordion{{ str_replace(' ', '', $header) }}">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ str_replace(' ', '', $header) }}">
                                            <button class="accordion-button d-flex align-items-center" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ str_replace(' ', '', $header) }}"
                                                aria-expanded="true"
                                                aria-controls="collapse{{ str_replace(' ', '', $header) }}">
                                                <input class="form-check-input me-2" type="checkbox"
                                                    id="headerCheckbox{{ str_replace(' ', '', $header) }}" />
                                                <b>{{ ucfirst(str_replace('_', ' ', $header)) }}</b>
                                            </button>
                                        </h2>
                                        <div id="collapse{{ str_replace(' ', '', $header) }}"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="heading{{ str_replace(' ', '', $header) }}"
                                            data-bs-parent="#accordion{{ str_replace(' ', '', $header) }}">
                                            <div class="accordion-body">
                                                @foreach ($permissionItem->chunk(4) as $chunk)
                                                    <div class="row">
                                                        @foreach ($chunk as $permission => $id)
                                                            @php
                                                                $checkboxId = str_replace(
                                                                    ' ',
                                                                    '',
                                                                    strtolower($header . '_' . $permission),
                                                                );
                                                            @endphp
                                                            <div class="col-md-3">
                                                                <div class="form-check mb-2">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="{{ $id }}" name="permissions[]"
                                                                        value="{{ $id }} | {{ $header }}" />
                                                                    <label class="form-check-label"
                                                                        for="{{ $id }}">
                                                                        {{ ucfirst(str_replace('_', ' ', $permission)) }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Permission table -->
    </div>
    <div class="col-12 d-flex flex-wrap justify-content-center gap-4 row-gap-4">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
            Cancel
        </button>
    </div>
</form>
