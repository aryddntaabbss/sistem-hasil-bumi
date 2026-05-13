<x-filament-widgets::widget>
    <x-filament::section>
        <div style="padding: 0.5rem 0;">

            <h3 style="font-size: 1.1rem; font-weight: 700; color: #16a34a; margin-bottom: 1rem;">
            - Panduan Pengisian Data Produksi
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">

                {{-- Langkah 1 --}}
                <div style="background: #f0fdf4; padding: 1rem; border-radius: 0.5rem;">
                    <div style="font-weight: 700; color: #15803d; margin-bottom: 0.5rem;">
                    1. Pilih Menu Data Produksi
                    </div>
                    <p style="font-size: 0.875rem; color: #374151; margin: 0;">
                        Klik menu <strong>Data Produksi</strong> di sidebar kiri, lalu klik tombol
                        <strong>Tambah Produksi</strong> untuk mulai mengisi data.
                    </p>
                </div>

                {{-- Langkah 2 --}}
                <div style="background: #eff6ff; padding: 1rem; border-radius: 0.5rem;">
                    <div style="font-weight: 700; color: #1d4ed8; margin-bottom: 0.5rem;">
                        2. Isi Informasi Produksi
                    </div>
                    <p style="font-size: 0.875rem; color: #374151; margin: 0;">
                        Nama petani sudah terisi otomatis. Pilih <strong>jenis komoditas</strong>
                        (Kelapa, Pala, atau Cengkeh) dan masukkan <strong>tanggal panen</strong>.
                    </p>
                </div>

                {{-- Langkah 3 --}}
                <div style="background: #fffbeb; padding: 1rem; border-radius: 0.5rem;">
                    <div style="font-weight: 700; color: #b45309; margin-bottom: 0.5rem;">
                        3. Isi Detail Produksi
                    </div>
                    <p style="font-size: 0.875rem; color: #374151; margin: 0;">
                        Masukkan <strong>Hasil Panen (Kg)</strong>, <strong>Harga per Kg (Rp)</strong>,
                        dan <strong>Biaya Produksi (Rp)</strong>. Pendapatan dan keuntungan akan
                        dihitung otomatis.
                    </p>
                </div>

                {{-- Langkah 4 --}}
                <div style="background: #fdf4ff; padding: 1rem; border-radius: 0.5rem;">
                    <div style="font-weight: 700; color: #6d28d9; margin-bottom: 0.5rem;">
                        4. Simpan Data
                    </div>
                    <p style="font-size: 0.875rem; color: #374151; margin: 0;">
                        Tambahkan <strong>catatan</strong> jika diperlukan, lalu klik tombol
                        <strong>Simpan</strong> untuk menyimpan data produksi.
                    </p>
                </div>

            </div>

            {{-- Catatan penting --}}
            <div style="margin-top: 1rem; background: #fef2f2; border: 1px solid #fca5a5;
                        padding: 0.75rem 1rem; border-radius: 0.5rem; display: flex; gap: 0.5rem;">
                <span style="font-size: 1rem;">⚠️</span>
                <p style="font-size: 0.8rem; color: #991b1b; margin: 0;">
                    <strong>Penting:</strong> Pastikan data yang diisi sudah benar sebelum disimpan.
                    Hubungi admin jika ada kesalahan data yang perlu diperbaiki.
                </p>
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>