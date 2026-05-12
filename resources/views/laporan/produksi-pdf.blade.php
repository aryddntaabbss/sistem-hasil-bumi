<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Produksi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2, h4 { text-align: center; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #16a34a; color: white; padding: 8px; text-align: center; }
        td { padding: 6px 8px; border: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f3f4f6; }
        .total-row { font-weight: bold; background-color: #dcfce7; }
        .footer { margin-top: 20px; text-align: right; font-size: 11px; }
    </style>
</head>
<body>
    <h2>LAPORAN DATA PRODUKSI HASIL BUMI</h2>
    <h4>Kecamatan Gane Barat, Halmahera Selatan</h4>
    <hr>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Petani</th>
                <th>Komoditas</th>
                <th>Tgl Panen</th>
                <th>Hasil (Kg)</th>
                <th>Harga/Kg</th>
                <th>Biaya (Rp)</th>
                <th>Pendapatan (Rp)</th>
                <th>Keuntungan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $row)
            @php
                $pendapatan = $row->hasil_panen_kg * $row->harga_per_kg;
                $keuntungan = $pendapatan - $row->biaya_produksi;
            @endphp
            <tr>
                <td align="center">{{ $i + 1 }}</td>
                <td>{{ $row->petani->nama }}</td>
                <td>{{ $row->komoditas->nama_komoditas }}</td>
                <td align="center">{{ \Carbon\Carbon::parse($row->tanggal_panen)->format('d/m/Y') }}</td>
                <td align="right">{{ number_format($row->hasil_panen_kg, 2) }}</td>
                <td align="right">{{ number_format($row->harga_per_kg, 0, ',', '.') }}</td>
                <td align="right">{{ number_format($row->biaya_produksi, 0, ',', '.') }}</td>
                <td align="right">{{ number_format($pendapatan, 0, ',', '.') }}</td>
                <td align="right">{{ number_format($keuntungan, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="7" align="right">TOTAL</td>
                <td align="right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                <td align="right">Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>