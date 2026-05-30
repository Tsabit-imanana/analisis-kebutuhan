<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111; }
        .page { width: 100%; }
        .top-row { display: flex; justify-content: space-between; align-items: flex-start; }
        .brand { border: 2px solid #111; padding: 8px 12px; width: 58%; }
        .brand h1 { font-size: 16px; margin: 0; line-height: 1.2; }
        .brand p { margin: 2px 0 0 0; font-size: 10px; }
        .date-block { text-align: right; width: 38%; font-size: 12px; }
        .title { text-align: center; font-size: 18px; font-weight: 700; margin: 14px 0 8px; }
        .no-box { border: 1px solid #111; display: inline-block; padding: 6px 10px; font-weight: 700; }
        .recipient { width: 38%; margin-left: auto; font-size: 12px; line-height: 1.4; }
        .recipient strong { display: inline-block; margin-bottom: 6px; }
        .section-label { margin-top: 12px; font-weight: 700; }
        .table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .table th, .table td { border: 1px solid #111; padding: 8px; vertical-align: top; }
        .table th { text-align: center; font-weight: 700; }
        .table td { line-height: 1.4; }
        .muted-line { margin-top: 8px; font-weight: 700; }
        .contracts { margin-top: 8px; }
        .contracts div { margin-bottom: 4px; }
        .footer { margin-top: 28px; display: flex; flex-direction: column; gap: 24px; align-items: center; }
        .signature { width: 60%; text-align: center; }
        .signature-line { margin-top: 60px; border-top: 1px solid #111; display: inline-block; padding-top: 6px; min-width: 200px; }
    </style>
</head>
<body>
    <div class="page">
        <div class="top-row">
            <div class="brand">
                <h1>PT. MULTI UTAMA<br>MANDIRI SENTOSA</h1>
                <p>Jl. Coklat No. 8, Surabaya</p>
                <p>Telp. (031) 5032101, Email: mu.mums@gmail.com</p>
            </div>
            <div class="date-block">
                {{ $document->kota_surat }}, {{ optional($document->tanggal_surat)->format('d F Y') }}
            </div>
        </div>

        <div class="title">SURAT JALAN</div>
        <div style="text-align:center;">
            <span class="no-box">No : {{ $document->nomor_surat }}</span>
        </div>

        <div class="recipient">
            <strong>KEPADA :</strong><br>
            {!! nl2br(e($document->kepada)) !!}<br>
            @if (! empty($document->up_kepada))
                <strong>UP :</strong> {{ $document->up_kepada }}<br>
            @endif
            {!! nl2br(e($document->alamat_kepada)) !!}
        </div>

        @if ($document->faktur_menyusul)
            <div class="section-label">Faktur Menyusul</div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 8%;">No.</th>
                    <th style="width: 16%;">Volume</th>
                    <th>Nama Barang</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align:center;">1</td>
                    <td style="text-align:center;">{{ $document->volume }}</td>
                    <td>
                        <div><strong>{!! nl2br(e($document->nama_barang)) !!}</strong></div>
                        @if (! empty($document->nomer_seri))
                            <div style="margin-top:8px;"><strong>Nomer Seri :</strong><br>{!! nl2br(e($document->nomer_seri)) !!}</div>
                        @endif
                        <div class="muted-line">==============================================</div>
                        <div class="contracts">
                            @if (! empty($document->kontrak_khs_no))
                                <div><strong>Kontrak KHS No.</strong> : {{ $document->kontrak_khs_no }}</div>
                            @endif
                            @if (! empty($document->kontrak_khs_tanggal))
                                <div><strong>Tanggal</strong> : {{ optional($document->kontrak_khs_tanggal)->format('d F Y') }}</div>
                            @endif
                            @if (! empty($document->kontrak_rinci_no))
                                <div><strong>Kontrak Rinci No.</strong> : {{ $document->kontrak_rinci_no }}</div>
                            @endif
                            @if (! empty($document->kontrak_rinci_tanggal))
                                <div><strong>Tanggal</strong> : {{ optional($document->kontrak_rinci_tanggal)->format('d F Y') }}</div>
                            @endif
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <div class="signature">
                <div>Penerima</div>
                <div class="signature-line">{{ $document->penerima }}</div>
            </div>
            <div class="signature">
                <div>Pengirim</div>
                <div class="signature-line">{{ $document->pengirim }}</div>
            </div>
        </div>
    </div>
</body>
</html>
