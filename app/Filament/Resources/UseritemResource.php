<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UseritemResource\Pages;
use App\Filament\Resources\UseritemResource\RelationManagers;
use App\Models\Useritem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\item;
use App\Models\User;


class UseritemResource extends Resource
{
    protected static ?string $model = Useritem::class;

    protected static ?string $navigationIcon = 'iconsax-bol-receipt-item';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //Forms\Components\Select::make('items')
                //    ->label('items')
                //    ->multiple()
                //    ->relationship('items', 'name')
                //    ->searchable(),
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->required()
                    ->relationship('user', 'name')
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $user = User::find($state);
                        if ($user) {
                            $set('operator_name', $user->name);
                        }
                    }),
                Forms\Components\TextInput::make('operator_name')
                    ->required(),
                Forms\Components\TextInput::make('type_work')
                    ->required(),
                Forms\Components\TextInput::make('machine_no')
                    ->required(),
                Forms\Components\TextInput::make('job_desk')
                    ->required(),
                Forms\Components\TextInput::make('ref')
                    ->required(),
                Forms\Components\TextInput::make('qty')
                    ->label('Quantity')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Forms\Components\FileUpload::make('picture')
                    ->required()
                    ->imageEditor()
                    ->imageCropAspectRatio('1:1')
                    ->minSize(10)
                    ->maxSize(100000)
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('operator_name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('items.name')
                    ->label('items')
                    ->sortable(),
                // Tables\Columns\TextColumn::make('user.name')
                //     ->label('User')
                //     ->sortable(),
                Tables\Columns\TextColumn::make('type_work')
                    ->searchable(),
                Tables\Columns\TextColumn::make('machine_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('job_desk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ref')
                    ->searchable(),
                Tables\Columns\TextColumn::make('qty')
                    ->label('Quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUseritems::route('/'),
            'create' => Pages\CreateUseritem::route('/create'),
            'edit' => Pages\EditUseritem::route('/{record}/edit'),
            'view' => Pages\ViewUseritem::route('/{record}'),
        ];
    }
}
