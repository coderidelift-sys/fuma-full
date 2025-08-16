@extends('layouts.fuma')

@section('title', 'Page Not Found')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="error-content">
                    <i class="fas fa-exclamation-triangle fa-5x text-warning mb-4"></i>
                    <h1 class="display-4 fw-bold text-primary">404</h1>
                    <h3 class="mb-3">Page Not Found</h3>
                    <p class="text-muted mb-4">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
                    
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="{{ route('fuma.index') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i> Go Home
                        </a>
                        <a href="javascript:history.back()" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Go Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .error-content {
        padding: 3rem 0;
    }
</style>
@endpush