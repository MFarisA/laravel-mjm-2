<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Project;  
use App\Models\User; 


class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')  
                    ->label('Project')
                    // ->required()
                    ->multiple()
                    // ->columns(2) 
                    ->relationship('project', 'perusahaan')
                    ->searchable(),
                Forms\Components\Select::make('user_id')  
                    ->label('User')
                    ->required()
                    ->relationship('user', 'name')  
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('project.perusahaan')  
                    ->label('Project')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')  
                    ->label('User')
                    ->sortable(),
                Tables\Columns\TextColumn::make('operator_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type_work')
                    ->searchable(),
                Tables\Columns\TextColumn::make('machine_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('job_desk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ref')
                    ->searchable(),
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
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListItems::route('/'),   
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
