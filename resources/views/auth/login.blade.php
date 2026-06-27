<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-4">
        <h1 class="h4 mb-1">Masuk</h1>
        <p class="text-muted mb-0">Gunakan akun inventory yang sudah terdaftar.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">ID / Username</label>
            <input id="name" class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="username" placeholder="Masukkan ID (e.g. Admin)">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-4">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label class="form-check-label" for="remember_me">Ingat saya</label>
        </div>

        <div class="d-flex flex-column gap-2">
            <div class="d-flex align-items-center justify-content-between gap-3">
                @if (Route::has('password.request'))
                    <a class="small text-decoration-none text-primary" href="{{ route('password.request') }}">Lupa password?</a>
                @endif

                <button type="submit" class="btn btn-primary px-4">Masuk</button>
            </div>

            @if (Route::has('password.request'))
                <div class="mt-3 p-3 bg-light rounded-3 border border-dashed border-secondary border-opacity-25">
                    <span class="d-block small text-muted mb-2"><i class="bi bi-info-circle me-1 text-primary"></i> <strong>Data Akun Tersimpan:</strong></span>
                    <div class="d-flex flex-column gap-1 text-secondary" style="font-size: 0.825rem;">
                        <div class="d-flex justify-content-between">
                            <span>Admin ID: <code class="bg-white px-2 py-0.5 rounded border">Admin</code></span>
                            <span>Pass: <code class="bg-white px-2 py-0.5 rounded border">password</code></span>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <span>Dosen ID: <code class="bg-white px-2 py-0.5 rounded border">Dosen</code></span>
                            <span>Pass: <code class="bg-white px-2 py-0.5 rounded border">password</code></span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </form>
</x-guest-layout>
