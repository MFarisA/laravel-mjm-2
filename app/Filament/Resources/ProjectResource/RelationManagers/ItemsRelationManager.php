<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Exports\ItemExport;
use App\Models\Item;
use App\Models\Project;
use App\Models\Useritem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ItemResource;
use Maatwebsite\Excel\Facades\Excel;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Item Name'),
                //    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description'),
                //    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
            ])
            ->actions([
                Tables\Actions\Action::make('Export')
                    ->label('Print')
                    ->action(function (Item $record) {
                        return Excel::download(new ItemExport([$record->id]), 'Item-' . $record->id .'-'. $record->name . '.xlsx');
                    })
                    ->icon('heroicon-o-arrow-down-on-square'),
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->url(fn ($record) => ItemResource::getUrl('view', ['record' => $record->id]))
                    ->icon('heroicon-o-eye'),
                Tables\Actions\Action::make('edit')
                    ->label('Edit')
                    ->url(fn ($record) => ItemResource::getUrl('edit', ['record' => $record->id]))
                    ->icon('heroicon-o-pencil'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
            ]);
    }
}
