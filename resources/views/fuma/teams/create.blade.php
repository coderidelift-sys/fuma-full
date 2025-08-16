@extends('layouts.fuma')

@section('title', 'Register Team - Football Tournament Management')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="fw-bold mb-2">Register New Team</h1>
                    <p class="mb-0">Add a new football team to the system</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('fuma.teams.index') }}" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i> Back to Teams
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('fuma.teams.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Team Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                           id="city" name="city" value="{{ old('city') }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                           id="country" name="country" value="{{ old('country', 'Indonesia') }}" required>
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="logo" class="form-label">Team Logo</label>
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                           id="logo" name="logo" accept="image/*">
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Max file size: 2MB. Supported formats: JPEG, PNG, JPG, GIF</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <h5 class="fw-bold mb-3 mt-4">Manager Information</h5>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="manager_name" class="form-label">Manager Name</label>
                                    <input type="text" class="form-control @error('manager_name') is-invalid @enderror" 
                                           id="manager_name" name="manager_name" value="{{ old('manager_name') }}">
                                    @error('manager_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="manager_phone" class="form-label">Manager Phone</label>
                                    <input type="tel" class="form-control @error('manager_phone') is-invalid @enderror" 
                                           id="manager_phone" name="manager_phone" value="{{ old('manager_phone') }}">
                                    @error('manager_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="manager_email" class="form-label">Manager Email</label>
                                    <input type="email" class="form-control @error('manager_email') is-invalid @enderror" 
                                           id="manager_email" name="manager_email" value="{{ old('manager_email') }}">
                                    @error('manager_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Register Team
                                </button>
                                <a href="{{ route('fuma.teams.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection