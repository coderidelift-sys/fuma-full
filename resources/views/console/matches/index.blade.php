@extends('layouts.app')
@section('title', 'Manage Matches')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Matches</h5>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <select id="filter-status" class="form-select">
                            <option value="">All Status</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="live">Live</option>
                            <option value="paused">Paused</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="table-matches">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tournament</th>
                                <th>Home</th>
                                <th>Away</th>
                                <th>Scheduled At</th>
                                <th>Status</th>
                                <th>Score</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal fade" id="modal-score" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header"><h5 class="modal-title">Update Score</h5></div>
                            <div class="modal-body">
                                <form id="form-score">
                                    <div class="mb-3">
                                        <label class="form-label">Home Score</label>
                                        <input type="number" class="form-control" name="home_score" min="0" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Away Score</label>
                                        <input type="number" class="form-control" name="away_score" min="0" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Minute</label>
                                        <input type="number" class="form-control" name="minute" min="1" max="120" />
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button class="btn btn-primary" id="btn-save-score">Save</button>
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
            data: '{{ route('console.manage.matches.data') }}',
            update: '{{ route('console.manage.matches.update', ':id') }}',
            destroy: '{{ route('console.manage.matches.destroy', ':id') }}',
            status: '{{ route('console.manage.matches.status', ':id') }}',
        };

        const table = $('#table-matches').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: ENDPOINT.data,
                data: function(d){ d.status = $('#filter-status').val(); }
            },
            columns: [
                { data: 'id' },
                { data: 'tournament.name', defaultContent: '-' },
                { data: 'home_team.name', defaultContent: '-' },
                { data: 'away_team.name', defaultContent: '-' },
                { data: 'scheduled_at' },
                { data: 'status', render: s => `<span class="badge bg-label-${s==='live'?'success':(s==='completed'?'primary':(s==='paused'?'warning':'secondary'))}">${s}</span>` },
                { data: null, render: r => r.home_score!==null&&r.away_score!==null? `${r.home_score} - ${r.away_score}` : '-' },
                { data: null, orderable: false, searchable: false, render: row => {
                    return `
                        <div class=\"btn-group btn-group-sm\">\n                            <button class=\"btn btn-outline-secondary btn-status\" data-action=\"start\" data-id=\"${row.id}\">Start</button>\n                            <button class=\"btn btn-outline-secondary btn-status\" data-action=\"pause\" data-id=\"${row.id}\">Pause</button>\n                            <button class=\"btn btn-outline-secondary btn-status\" data-action=\"resume\" data-id=\"${row.id}\">Resume</button>\n                            <button class=\"btn btn-outline-secondary btn-status\" data-action=\"complete\" data-id=\"${row.id}\">Complete</button>\n                            <button class=\"btn btn-outline-primary btn-open-score\" data-id=\"${row.id}\">Score</button>\n                            <button class=\"btn btn-outline-danger btn-del\" data-id=\"${row.id}\">Delete</button>\n                        </div>`
                }},
            ]
        });

        $('#filter-status').on('change', () => table.ajax.reload());

        // Laravel Echo listeners: update table in real-time
        if (window.Echo) {
            try {
                window.Echo.channel('matches')
                    .listen('.match.status.updated', (e) => {
                        table.ajax.reload(null, false);
                        toastr.info(`Status match #${e.match_id} → ${e.status}`);
                    })
                    .listen('.match.score.updated', (e) => {
                        table.ajax.reload(null, false);
                        toastr.success(`Skor match #${e.match_id}: ${e.home_score}-${e.away_score}`);
                    });
            } catch (err) {
                console.warn('Echo not initialized:', err);
            }
        }

        $(document).on('click', '.btn-status', function() {
            const id = $(this).data('id');
            const action = $(this).data('action');
            $.post(ENDPOINT.status.replace(':id', id), { _token: '{{ csrf_token() }}', action }, function() {
                toastr.success('Status updated');
                table.ajax.reload(null, false);
            }).fail(() => toastr.error('Gagal update status'));
        });

        $(document).on('click', '.btn-del', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Hapus pertandingan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(res => {
                if (!res.isConfirmed) return;
                $.ajax({
                    url: ENDPOINT.destroy.replace(':id', id),
                    method: 'POST', data: { _method: 'DELETE', _token: '{{ csrf_token() }}' }
                }).done(() => { toastr.success('Berhasil dihapus'); table.ajax.reload(null, false); })
                  .fail(() => toastr.error('Gagal menghapus'));
            });
        });

        const scoreModal = new bootstrap.Modal(document.getElementById('modal-score'));
        let scoringMatchId = null;
        $(document).on('click', '.btn-open-score', function(){
            scoringMatchId = $(this).data('id');
            $('#form-score')[0].reset();
            scoreModal.show();
        });
        $('#btn-save-score').on('click', function(){
            const url = ENDPOINT.update.replace(':id', scoringMatchId);
            const payload = $('#form-score').serialize() + '&_token={{ csrf_token() }}&_method=POST';
            $.post(url, payload).done(() => {
                toastr.success('Score updated');
                scoreModal.hide();
                table.ajax.reload(null, false);
            }).fail((xhr)=> toastr.error(xhr.responseJSON?.message || 'Failed'));
        });
    });
</script>
@endpush


