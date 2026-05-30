@extends('layout.sidebar')

@section('title', 'Buat Dokumen - Surat Jalan')

@section('content')
    @vite(['resources/css/dashboard.css', 'resources/css/documents.css'])

    <div class="dashboard-container documents-page">
        <div class="dashboard-header documents-header">
            <div>
                <h1>Buat Dokumen Surat Jalan</h1>
                <p>Isi template surat jalan untuk diajukan ke approval.</p>
            </div>
            <a href="{{ route('documents.index') }}" class="doc-btn doc-btn--ghost">Kembali</a>
        </div>

        <div class="doc-card">
            <form method="POST" action="{{ route('documents.store') }}" class="doc-form">
                @csrf
                <div class="doc-section">
                    <div class="doc-section-title">Informasi Surat</div>
                    <div class="doc-grid">
                        <div class="doc-field">
                            <label>Nomor Surat</label>
                            <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}" required>
                        </div>
                        <div class="doc-field">
                            <label>Kota Surat</label>
                            <input type="text" name="kota_surat" value="{{ old('kota_surat') }}" required>
                        </div>
                        <div class="doc-field">
                            <label>Tanggal Surat</label>
                            <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat') }}" required>
                        </div>
                    </div>
                </div>

                <div class="doc-section">
                    <div class="doc-section-title">Tujuan Pengiriman</div>
                    <div class="doc-grid">
                        <div class="doc-field">
                            <label>Kepada</label>
                            <textarea name="kepada" required>{{ old('kepada') }}</textarea>
                        </div>
                        <div class="doc-field">
                            <label>UP</label>
                            <input type="text" name="up_kepada" value="{{ old('up_kepada') }}">
                        </div>
                        <div class="doc-field doc-field--full">
                            <label>Alamat Kepada</label>
                            <textarea name="alamat_kepada" required>{{ old('alamat_kepada') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="doc-section">
                    <div class="doc-section-title">Rincian Barang</div>
                    <div class="doc-grid">
                        <div class="doc-field">
                            <label>Volume</label>
                            <input type="text" name="volume" value="{{ old('volume') }}" required>
                        </div>
                        <div class="doc-field doc-field--full">
                            <label>Nama Barang</label>
                            <textarea name="nama_barang" required>{{ old('nama_barang') }}</textarea>
                        </div>
                        <div class="doc-field doc-field--full">
                            <label>Nomer Seri</label>
                            <textarea name="nomer_seri">{{ old('nomer_seri') }}</textarea>
                        </div>
                        <div class="doc-field doc-field--full">
                            <label class="doc-checkbox">
                                <input type="checkbox" name="faktur_menyusul" value="1" {{ old('faktur_menyusul', true) ? 'checked' : '' }}>
                                Faktur Menyusul
                            </label>
                        </div>
                    </div>
                </div>

                <div class="doc-section">
                    <div class="doc-section-title">Informasi Kontrak</div>
                    <div class="doc-grid">
                        <div class="doc-field">
                            <label>Kontrak KHS No</label>
                            <input type="text" name="kontrak_khs_no" value="{{ old('kontrak_khs_no') }}">
                        </div>
                        <div class="doc-field">
                            <label>Tanggal KHS</label>
                            <input type="date" name="kontrak_khs_tanggal" value="{{ old('kontrak_khs_tanggal') }}">
                        </div>
                        <div class="doc-field">
                            <label>Kontrak Rinci No</label>
                            <input type="text" name="kontrak_rinci_no" value="{{ old('kontrak_rinci_no') }}">
                        </div>
                        <div class="doc-field">
                            <label>Tanggal Kontrak Rinci</label>
                            <input type="date" name="kontrak_rinci_tanggal" value="{{ old('kontrak_rinci_tanggal') }}">
                        </div>
                    </div>
                </div>

                <div class="doc-section">
                    <div class="doc-section-title">Tanda Tangan</div>
                    <div class="doc-grid">
                        <div class="doc-field">
                            <label>Penerima</label>
                            <input type="text" name="penerima" value="{{ old('penerima') }}" required>
                        </div>
                        <div class="doc-field">
                            <label>Pengirim</label>
                            <input type="text" name="pengirim" value="{{ old('pengirim') }}" required>
                        </div>
                    </div>
                </div>

                <div class="doc-form-actions">
                    <button type="submit" class="doc-btn doc-btn--primary">Simpan Draft</button>
                    <a href="{{ route('documents.index') }}" class="doc-btn doc-btn--secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
