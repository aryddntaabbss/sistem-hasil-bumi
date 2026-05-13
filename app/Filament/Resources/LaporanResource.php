<?php

namespace App\Filament\Resources;

use App\Exports\ProduksiExport;
use App\Filament\Resources\LaporanResource\Pages;
use App\Models\Komoditas;
use App\Models\Petani;
use App\Models\Produksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class LaporanResource extends Resource
{
    protected static ?string $model = Produksi::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?string $modelLabel = 'Laporan';
    protected static ?string $pluralModelLabel = 'Laporan';
    protected static ?string $slug = 'laporan';
    protected static ?int $navigationSort = 4;

    public static function canAccess(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Produksi::with(['petani', 'komoditas']))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('petani.nama')
                    ->label('Petani')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('komoditas.nama_komoditas')
                    ->label('Komoditas')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_panen')
                    ->label('Tgl Panen')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('hasil_panen_kg')
                    ->label('Hasil (Kg)')
                    ->numeric(),

                Tables\Columns\TextColumn::make('harga_per_kg')
                    ->label('Harga/Kg')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('biaya_produksi')
                    ->label('Biaya (Rp)')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('pendapatan')
                    ->label('Pendapatan')
                    ->money('IDR')
                    ->getStateUsing(fn($record) => $record->hasil_panen_kg * $record->harga_per_kg),

                Tables\Columns\TextColumn::make('keuntungan')
                    ->label('Keuntungan')
                    ->money('IDR')
                    ->getStateUsing(fn($record) => ($record->hasil_panen_kg * $record->harga_per_kg) - $record->biaya_produksi),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('petani_id')
                    ->label('Petani')
                    ->options(Petani::pluck('nama', 'id')),

                Tables\Filters\SelectFilter::make('komoditas_id')
                    ->label('Komoditas')
                    ->options(Komoditas::pluck('nama_komoditas', 'id')),

                Tables\Filters\Filter::make('tanggal_panen')
                    ->form([
                        Forms\Components\DatePicker::make('dari')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai')->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['dari'], fn($q) => $q->whereDate('tanggal_panen', '>=', $data['dari']))
                            ->when($data['sampai'], fn($q) => $q->whereDate('tanggal_panen', '<=', $data['sampai']));
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('exportExcel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-table-cells')
                    ->color('success')
                    ->action(function () {
                        return Excel::download(new ProduksiExport(), 'laporan-produksi-' . now()->format('d-m-Y') . '.xlsx');
                    }),

                Tables\Actions\Action::make('exportPdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->action(function () {
                        $data     = Produksi::with(['petani', 'komoditas'])->get();
                        $totalPendapatan = $data->sum(fn($r) => $r->hasil_panen_kg * $r->harga_per_kg);
                        $totalKeuntungan = $data->sum(fn($r) => ($r->hasil_panen_kg * $r->harga_per_kg) - $r->biaya_produksi);

                        $pdf = Pdf::loadView('laporan.produksi-pdf', compact('data', 'totalPendapatan', 'totalKeuntungan'))
                            ->setPaper('a4', 'landscape');

                        return response()->streamDownload(
                            fn() => print($pdf->output()),
                            'laporan-produksi-' . now()->format('d-m-Y') . '.pdf'
                        );
                    }),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLaporan::route('/'),
        ];
    }
}
