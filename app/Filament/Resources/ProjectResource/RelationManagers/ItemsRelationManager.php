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
                Forms\Components\Hidden::make('project_id') 
                    ->default($this->ownerRecord->id),
                Forms\Components\Select::make('user_id')  
                    ->label('User')
                    ->required()
                    ->relationship('user', 'name')  
                    ->searchable()
                    ->reactive() 
                    ->afterStateUpdated(function ($state, callable $set) {
                        $user = \App\Models\User::find($state);
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
            ->recordTitleAttribute('operator_name')
            ->columns([
                // Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('operator_name')
                    ->label('Name'),
                Tables\Columns\TextColumn::make('type_work'),
                Tables\Columns\TextColumn::make('machine_no'),
                Tables\Columns\TextColumn::make('job_desk'),
                Tables\Columns\TextColumn::make('ref'),
                Tables\Columns\ImageColumn::make('picture'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
