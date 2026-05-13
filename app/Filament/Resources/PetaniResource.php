<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PetaniResource\Pages;
use App\Models\Petani;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PetaniResource extends Resource
{
    protected static ?string $model = Petani::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Data Petani';
    protected static ?string $modelLabel = 'Petani';
    protected static ?string $pluralModelLabel = 'Petani';
    protected static ?string $slug = 'petani';
    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Petani')
                ->schema([
                    Forms\Components\TextInput::make('nama')
                        ->label('Nama Petani')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('no_hp')
                        ->label('No. HP')
                        ->tel()
                        ->required()
                        ->maxLength(15),

                    Forms\Components\Textarea::make('alamat')
                        ->label('Alamat')
                        ->required()
                        ->rows(3),
                ])->columns(2),

            Forms\Components\Section::make('Akun Login Petani')
                ->schema([
                    Forms\Components\TextInput::make('user.email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->unique(
                            table: 'users',
                            column: 'email',
                            ignorable: fn($record) => $record?->user
                        ),

                    Forms\Components\TextInput::make('user.password')
                        ->label('Password')
                        ->password()
                        ->minLength(8)
                        ->dehydrated(fn($state) => filled($state))
                        ->required(fn(string $operation) => $operation === 'create')
                        ->hint(fn(string $operation) => $operation === 'edit' ? 'Kosongkan jika tidak ingin mengubah password' : ''),
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

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Petani')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(40),

                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No. HP'),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email Login')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('user.role')
                    ->label('Role')
                    ->colors(['success' => 'petani']),

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
            'index'  => Pages\ListPetani::route('/'),
            'create' => Pages\CreatePetani::route('/create'),
            'edit'   => Pages\EditPetani::route('/{record}/edit'),
        ];
    }
}
