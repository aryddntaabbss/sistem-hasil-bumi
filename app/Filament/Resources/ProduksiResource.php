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
        $petani = \App\Models\Petani::where('nama', Auth::user()->name)->first();
        if ($petani) {
            $query->where('petani_id', $petani->id);
        }
    }

    return $query;
}

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Produksi')
                ->schema([
                    Forms\Components\Select::make('petani_id')
                        ->label('Petani')
                        ->options(Petani::all()->pluck('nama', 'id'))
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
                            $harga = $get('harga_per_kg') ?? 0;
                            $biaya = $get('biaya_produksi') ?? 0;
                            $pendapatan = $state * $harga;
                            $set('pendapatan', $pendapatan);
                            $set('keuntungan', $pendapatan - $biaya);
                        }),

                    Forms\Components\TextInput::make('harga_per_kg')
                        ->label('Harga per Kg (Rp)')
                        ->numeric()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            $hasil = $get('hasil_panen_kg') ?? 0;
                            $biaya = $get('biaya_produksi') ?? 0;
                            $pendapatan = $hasil * $state;
                            $set('pendapatan', $pendapatan);
                            $set('keuntungan', $pendapatan - $biaya);
                        }),

                    Forms\Components\TextInput::make('biaya_produksi')
                        ->label('Biaya Produksi (Rp)')
                        ->numeric()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            $hasil = $get('hasil_panen_kg') ?? 0;
                            $harga = $get('harga_per_kg') ?? 0;
                            $pendapatan = $hasil * $harga;
                            $set('keuntungan', $pendapatan - $state);
                        }),
                ])->columns(3),

            Forms\Components\Section::make('Kalkulasi Otomatis')
                ->schema([
                    Forms\Components\TextInput::make('pendapatan')
                        ->label('Pendapatan (Rp)')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(),

                    Forms\Components\TextInput::make('keuntungan')
                        ->label('Keuntungan (Rp)')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(),
                ])->columns(2),

            Forms\Components\Textarea::make('catatan')
                ->label('Catatan')
                ->rows(3)
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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

                Tables\Columns\TextColumn::make('pendapatan')
                    ->label('Pendapatan')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('keuntungan')
                    ->label('Keuntungan')
                    ->money('IDR')
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