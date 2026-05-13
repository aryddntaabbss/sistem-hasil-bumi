<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KomoditasResource\Pages;
use App\Models\Komoditas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class KomoditasResource extends Resource
{
    protected static ?string $model = Komoditas::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Data Komoditas';
    protected static ?string $modelLabel = 'Komoditas';
    protected static ?string $pluralModelLabel = 'Komoditas';
    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Komoditas')
                ->schema([
                    Forms\Components\TextInput::make('nama_komoditas')
                        ->label('Nama Komoditas')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Select::make('jenis')
                        ->label('Jenis')
                        ->options([
                            'Pertanian'    => 'Pertanian',
                            'Perkebunan'   => 'Perkebunan',
                            'Hortikultura' => 'Hortikultura',
                        ])
                        ->required(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('No')
                    ->rowIndex(),

                Tables\Columns\TextColumn::make('nama_komoditas')
                    ->label('Nama Komoditas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('jenis')
                    ->label('Jenis')
                    ->colors([
                        'success' => 'Pertanian',
                        'warning' => 'Perkebunan',
                        'info'    => 'Hortikultura',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
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
            'index'  => Pages\ListKomoditas::route('/'),
            'create' => Pages\CreateKomoditas::route('/create'),
            'edit'   => Pages\EditKomoditas::route('/{record}/edit'),
        ];
    }
}
