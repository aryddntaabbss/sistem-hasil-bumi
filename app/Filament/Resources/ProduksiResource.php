<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProduksiResource\Pages;
use App\Models\Produksi;
use App\Models\Petani;
use App\Models\Komoditas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ProduksiResource extends Resource
{
    protected static ?string $model = Produksi::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Data Produksi';
    protected static ?string $modelLabel = 'Produksi';
    protected static ?string $slug = 'produksi';
    protected static ?string $pluralModelLabel = 'Produksi';
    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        return Auth::check() && in_array(Auth::user()->role, ['admin', 'petani']);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::check() && Auth::user()->role === 'petani') {
            $petani = Petani::where('user_id', Auth::id())->first();
            if ($petani) {
                $query->where('petani_id', $petani->id);
            } else {
                $query->whereRaw('0 = 1');
            }
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        // Kalau petani login, otomatis terkunci ke dirinya
        $isPetani    = Auth::check() && Auth::user()->role === 'petani';
        $petaniLogin = $isPetani ? Petani::where('user_id', Auth::id())->first() : null;

        return $form->schema([
            Forms\Components\Section::make('Informasi Produksi')
                ->schema([
                    Forms\Components\Select::make('petani_id')
                        ->label('Petani')
                        ->options(
                            $isPetani && $petaniLogin
                                ? [$petaniLogin->id => $petaniLogin->nama]
                                : Petani::all()->pluck('nama', 'id')
                        )
                        ->default($petaniLogin?->id)
                        ->disabled($isPetani)
                        ->dehydrated()
                        ->searchable()
                        ->required(),

                    Forms\Components\Select::make('komoditas_id')
                        ->label('Komoditas')
                        ->options(Komoditas::all()->pluck('nama_komoditas', 'id'))
                        ->searchable()
                        ->required(),

                    Forms\Components\DatePicker::make('tanggal_panen')
                        ->label('Tanggal Panen')
                        ->required()
                        ->default(now()),
                ])->columns(3),

            Forms\Components\Section::make('Detail Produksi')
                ->schema([
                    Forms\Components\TextInput::make('hasil_panen_kg')
                        ->label('Hasil Panen (Kg)')
                        ->numeric()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            $harga      = (float) ($get('harga_per_kg') ?? 0);
                            $biaya      = (float) ($get('biaya_produksi') ?? 0);
                            $pendapatan = (float) $state * $harga;
                            $set('pendapatan', number_format($pendapatan, 0, ',', '.'));
                            $set('keuntungan', number_format($pendapatan - $biaya, 0, ',', '.'));
                        }),

                    Forms\Components\TextInput::make('harga_per_kg')
                        ->label('Harga per Kg (Rp)')
                        ->prefix('Rp')
                        ->numeric()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            $hasil      = (float) ($get('hasil_panen_kg') ?? 0);
                            $biaya      = (float) ($get('biaya_produksi') ?? 0);
                            $pendapatan = $hasil * (float) $state;
                            $set('pendapatan', number_format($pendapatan, 0, ',', '.'));
                            $set('keuntungan', number_format($pendapatan - $biaya, 0, ',', '.'));
                        }),

                    Forms\Components\TextInput::make('biaya_produksi')
                        ->label('Biaya Produksi (Rp)')
                        ->prefix('Rp')
                        ->numeric()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            $hasil      = (float) ($get('hasil_panen_kg') ?? 0);
                            $harga      = (float) ($get('harga_per_kg') ?? 0);
                            $pendapatan = $hasil * $harga;
                            $set('pendapatan', number_format($pendapatan, 0, ',', '.'));
                            $set('keuntungan', number_format($pendapatan - (float) $state, 0, ',', '.'));
                        }),
                ])->columns(3),

            Forms\Components\Section::make('Kalkulasi Otomatis')
                ->description('Dihitung otomatis berdasarkan input di atas')
                ->schema([
                    Forms\Components\TextInput::make('pendapatan')
                        ->label('Pendapatan')
                        ->prefix('Rp')
                        ->disabled()
                        ->dehydrated()
                        ->formatStateUsing(fn($state) => number_format((float) $state, 0, ',', '.')),

                    Forms\Components\TextInput::make('keuntungan')
                        ->label('Keuntungan')
                        ->prefix('Rp')
                        ->disabled()
                        ->dehydrated()
                        ->formatStateUsing(fn($state) => number_format((float) $state, 0, ',', '.')),
                ])->columns(2),

            Forms\Components\Section::make('Catatan')
                ->schema([
                    Forms\Components\Textarea::make('catatan')
                        ->label('Catatan Tambahan')
                        ->rows(3)
                        ->nullable()
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('harga_per_kg')
                    ->label('Harga/Kg')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('biaya_produksi')
                    ->label('Biaya (Rp)')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('pendapatan')
                    ->label('Pendapatan')
                    ->money('IDR')
                    ->getStateUsing(fn($record) => $record->hasil_panen_kg * $record->harga_per_kg)
                    ->sortable(),

                Tables\Columns\TextColumn::make('keuntungan')
                    ->label('Keuntungan')
                    ->money('IDR')
                    ->getStateUsing(fn($record) => ($record->hasil_panen_kg * $record->harga_per_kg) - $record->biaya_produksi)
                    ->color(fn($state) => $state >= 0 ? 'success' : 'danger')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('petani_id')
                    ->label('Filter Petani')
                    ->relationship('petani', 'nama'),

                Tables\Filters\SelectFilter::make('komoditas_id')
                    ->label('Filter Komoditas')
                    ->relationship('komoditas', 'nama_komoditas'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProduksi::route('/'),
            'create' => Pages\CreateProduksi::route('/create'),
            'edit'   => Pages\EditProduksi::route('/{record}/edit'),
        ];
    }
}
