@extends('layouts.app')
@section('title', 'Manage Teams')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Teams</h5>
                <button class="btn btn-primary" id="btn-add">Add Team</button>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <input id="filter-q" class="form-control" placeholder="Search name...">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="table-teams">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>City</th>
                                <th>Country</th>
                                <th>Rating</th>
                                <th>Players</th>
                                <th>Tournaments</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal fade" id="modal-form" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header"><h5 class="modal-title">Team</h5></div>
                            <div class="modal-body">
                                <form id="form-entity">
                                    <input type="hidden" name="_method" value="POST" />
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input class="form-control" name="name" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">City</label>
                                        <input class="form-control" name="city" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Country</label>
                                        <input class="form-control" name="country" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Manager</label>
                                        <input class="form-control" name="manager_name" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <input class="form-control" name="status" />
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
            data: '{{ route('console.manage.teams.data') }}',
            store: '{{ route('console.manage.teams.store') }}',
            update: '{{ route('console.manage.teams.update', ':id') }}',
            destroy: '{{ route('console.manage.teams.destroy', ':id') }}',
        };

        const drawTable = () => $('#table-teams').DataTable({
            destroy: true,
            serverSide: true,
            processing: true,
            ajax: {
                url: ENDPOINT.data,
                data: function(d){ d.q = $('#filter-q').val(); }
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'city' },
                { data: 'country' },
                { data: 'rating' },
                { data: 'players_count', name: 'players_count' },
                { data: 'tournaments_count', name: 'tournaments_count' },
                { data: null, orderable: false, searchable: false, render: row => {
                    const editBtn = `<button class=\"btn btn-sm btn-outline-primary btn-edit\" data-id=\"${row.id}\">Edit</button>`;
                    const delBtn = `<button class=\"btn btn-sm btn-outline-danger btn-del\" data-id=\"${row.id}\">Delete</button>`;
                    return `${editBtn} ${delBtn}`;
                }},
            ]
        });

        let table = drawTable();

        const debounce = (fn, wait = 350) => {
            let t;
            return (...args) => {
                clearTimeout(t);
                t = setTimeout(() => fn.apply(this, args), wait);
            };
        };

        $('#filter-q').on('keyup', debounce(() => {
            table.destroy();
            table = drawTable();
        }, 350));

        $(document).on('click', '.btn-del', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Hapus tim?',
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
            $.getJSON(ENDPOINT.update.replace(':id', id).replace('/update',''), function(data){
                editingId = id;
                $('input[name=name]').val(data.name);
                $('input[name=city]').val(data.city ?? '');
                $('input[name=country]').val(data.country ?? '');
                $('input[name=manager_name]').val(data.manager_name ?? '');
                $('input[name=status]').val(data.status ?? '');
                $('input[name=_method]').val('PUT');
                modal.show();
            });
        });

        $('#btn-save').on('click', function(){
            const form = $('#form-entity');
            const url = editingId ? ENDPOINT.update.replace(':id', editingId) : ENDPOINT.store;
            const method = 'POST';
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


