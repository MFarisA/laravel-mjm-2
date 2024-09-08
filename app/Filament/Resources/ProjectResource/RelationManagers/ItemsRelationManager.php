<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('operator_name'),
                Tables\Columns\TextColumn::make('type_work'),
                Tables\Columns\TextColumn::make('machine_no'),
                Tables\Columns\TextColumn::make('job_desk'),
                Tables\Columns\TextColumn::make('ref'),
                Tables\Columns\ImageColumn::make('picture')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
