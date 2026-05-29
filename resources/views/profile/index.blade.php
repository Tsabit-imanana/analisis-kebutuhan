@extends('layout.sidebar')

@section('title', 'My Profile - MUMS')

@section('content')
    @vite('resources/css/profile/index.css')

    <div class="profile-container">
        <div class="header">
            <div>
                <h1>Profiles</h1>
                <p class="muted">Kelola akun anda.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="message success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="message error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="message error">
                <strong>Terjadi kesalahan:</strong>
                <ul class="error-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card profile-card">
            <div class="profile-header-row">
                <div class="profile-identity">
                    <img
                        src="{{ auth()->user()->photo ? asset(auth()->user()->photo) : asset('images/default-avatar.png') }}"
                        alt="Profile Photo"
                        class="profile-avatar">
                    <h2 class="profile-name">{{ auth()->user()->name }}</h2>
                </div>

                <button type="button" class="btn btn-primary" onclick="openEditProfileModal()">Edit Profile</button>
            </div>

            <div class="profile-details-grid">
                <div class="field">
                    <label>Nama Lengkap</label>
                    <input type="text" value="{{ auth()->user()->name }}" class="readonly-input" readonly>
                </div>

                <div class="field">
                    <label>Email</label>
                    <input type="email" value="{{ auth()->user()->email }}" class="readonly-input" readonly>
                </div>

                <div class="field">
                    <label>No. Telepon</label>
                    <input type="text" value="{{ auth()->user()->no_telepon ?? '-' }}" class="readonly-input" readonly>
                </div>

                <div class="field">
                    <label>Alamat</label>
                    <input type="text" value="{{ auth()->user()->alamat ?? '-' }}" class="readonly-input" readonly>
                </div>
            </div>
        </div>
    </div>

    <div id="editProfileModal" class="modal">
        <div class="modal-content modal-md">
            <h2>Edit Profile</h2>
            <form id="editProfileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="field">
                    <label>Foto Profil</label>
                    <input type="file" name="photo" accept="image/*">
                    <small class="muted" style="font-size: 12px; margin-top:-8px; margin-bottom:12px;">Biarkan kosong jika tidak ingin mengubah foto.</small>
                </div>

                <div class="field">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" required>
                </div>

                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ auth()->user()->email }}" required>
                </div>

                <div class="field">
                    <label>No. Telepon</label>
                    <input type="text" name="no_telepon" value="{{ auth()->user()->no_telepon }}">
                </div>

                <div class="field">
                    <label>Alamat</label>
                    <textarea name="alamat" rows="3">{{ auth()->user()->alamat }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeEditProfileModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditProfileModal() {
            document.getElementById('editProfileModal').style.display = 'block';
        }

        function closeEditProfileModal() {
            document.getElementById('editProfileModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('editProfileModal')) {
                closeEditProfileModal();
            }
        };
    </script>
@endsection
