@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="row w-100">
        <div class="col-lg-5 col-md-7 col-sm-9 mx-auto">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="bi bi-exclamation-circle-fill" style="font-size: 3.5rem; color: #0d6efd;"></i>
                    </div>
                    <h4 class="mb-4 font-weight-bold text-primary">{{ __('Forgot Password') }}</h4>
                    <p class="text-muted mb-4">{{ __('Enter your cellphone number and we’ll send you a link to reset your password.') }}</p>

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.phone') }}">
                        @csrf

                        <div class="mb-4">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-0">
                                    <i class="bi bi-telephone text-primary"></i>
                                </span>
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror border-0" name="phone" value="{{ old('phone') }}" required autofocus placeholder="Cellphone Number">
                                
                                @error('phone')
                                    <span class="invalid-feedback d-block mt-2" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                {{ __('Submit') }}
                            </button>
                        </div>
                    </form>

                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="text-decoration-none text-primary">
                            <i class="bi bi-arrow-left-circle"></i> {{ __('Back to Login') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
