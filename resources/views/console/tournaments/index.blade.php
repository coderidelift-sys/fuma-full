@extends('layouts.app')
@section('title', 'Manage Tournaments')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tournaments</h5>
                <button class="btn btn-primary" id="btn-add">Add Tournament</button>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <select id="filter-status" class="form-select">
                            <option value="">All Status</option>
                            <option value="upcoming">Upcoming</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped" id="table-tournaments">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Teams</th>
                                <th>Matches</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="modal fade" id="modal-form" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header"><h5 class="modal-title">Tournament</h5></div>
                            <div class="modal-body">
                                <form id="form-entity">
                                    <input type="hidden" name="_method" value="POST" />
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input class="form-control" name="name" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" class="form-control" name="start_date" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">End Date</label>
                                        <input type="date" class="form-control" name="end_date" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Max Teams</label>
                                        <input type="number" class="form-control" name="max_teams" min="2" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Venue</label>
                                        <input class="form-control" name="venue" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="status" required>
                                            <option value="upcoming">Upcoming</option>
                                            <option value="ongoing">Ongoing</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button class="btn btn-primary" id="btn-save">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function() {
        const ENDPOINT = {
            data: '{{ route('console.manage.tournaments.data') }}',
            store: '{{ route('console.manage.tournaments.store') }}',
            update: '{{ route('console.manage.tournaments.update', ':id') }}',
            destroy: '{{ route('console.manage.tournaments.destroy', ':id') }}',
            show: '{{ route('console.manage.tournaments.show', ':id') }}',
        };

        const table = $('#table-tournaments').DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            ajax: {
                url: ENDPOINT.data,
                data: function(d){
                    d.status = $('#filter-status').val();
                }
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'status', render: s => `<span class="badge bg-label-${s==='ongoing'?'success':(s==='upcoming'?'warning':'primary')}">${s}</span>` },
                { data: 'start_date' },
                { data: 'end_date' },
                { data: 'teams_count', name: 'teams_count', defaultContent: 0, render: (d)=> d ?? 0 },
                { data: 'matches_count', name: 'matches_count', defaultContent: 0, render: (d)=> d ?? 0 },
                { data: null, orderable: false, searchable: false, render: row => {
                    const editBtn = `<button class="btn btn-sm btn-outline-primary btn-edit" data-id="${row.id}">Edit</button>`;
                    const delBtn = `<button class="btn btn-sm btn-outline-danger btn-del" data-id="${row.id}">Delete</button>`;
                    return `${editBtn} ${delBtn}`;
                }},
            ]
        });

        $('#filter-status').on('change', () => table.ajax.reload());

        // Delete with SweetAlert2
        $(document).on('click', '.btn-del', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Hapus turnamen?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(res => {
                if (!res.isConfirmed) return;
                $.ajax({
                    url: ENDPOINT.destroy.replace(':id', id),
                    method: 'POST',
                    data: {_method: 'DELETE', _token: '{{ csrf_token() }}'},
                }).done(() => {
                    toastr.success('Berhasil dihapus');
                    table.ajax.reload();
                }).fail(() => toastr.error('Gagal menghapus'));
            });
        });

        const modal = new bootstrap.Modal(document.getElementById('modal-form'));
        let editingId = null;

        $('#btn-add').on('click', () => {
            editingId = null;
            $('#form-entity')[0].reset();
            $('input[name=_method]').val('POST');
            modal.show();
        });

        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            $.getJSON(ENDPOINT.show.replace(':id', id), function(data){
                editingId = id;
                $('input[name=name]').val(data.name);
                $('input[name=start_date]').val(data.start_date);
                $('input[name=end_date]').val(data.end_date);
                $('input[name=max_teams]').val(data.max_teams);
                $('input[name=venue]').val(data.venue ?? '');
                $('select[name=status]').val(data.status);
                $('input[name=_method]').val('PUT');
                modal.show();
            });
        });

        $('#btn-save').on('click', function(){
            const form = $('#form-entity');
            const url = editingId ? ENDPOINT.update.replace(':id', editingId) : ENDPOINT.store;
            const method = editingId ? 'POST' : 'POST';
            const payload = form.serialize() + '&_token={{ csrf_token() }}';
            $.ajax({ url, method, data: payload }).done(() => {
                toastr.success('Saved');
                modal.hide();
                table.ajax.reload(null, false);
            }).fail((xhr) => {
                toastr.error(xhr.responseJSON?.message || 'Failed');
            });
        });
    });
</script>
@endpush


