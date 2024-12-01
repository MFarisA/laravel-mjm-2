<?php

namespace App\Filament\Resources;

use App\Exports\ProjectExport;
use App\Filament\Exports\ProjectExporter;
use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Filament\Resources\ProjectResource\RelationManagers\ItemsRelationManager;

use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use phpDocumentor\Reflection\PseudoTypes\Numeric_;
use Filament\Forms\Components\Textarea;

use Filament\Notifications\Notification;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use function Pest\Laravel\session;

use Filament\Actions\DeleteAction;
use Maatwebsite\Excel\Facades\Excel;


class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-c-view-columns';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('order'),
                TextEntry::make('perusahaan'),
                TextEntry::make('deskripsi'),
                TextEntry::make('supervisor'),
                TextEntry::make('quantity'),
                TextEntry::make('deadline'),
                TextEntry::make('status'),
                TextEntry::make('voc')
                    ->label('VOC / PO NO'),
                ImageEntry::make('picture')
                
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order')
                    ->required(),
                Forms\Components\TextInput::make('perusahaan')
                    ->required(),
                Forms\Components\Textarea::make('deskripsi')
                    ->rows(3)
                    ->required(),
                Forms\Components\TextInput::make('supervisor')
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('voc')
                    ->label('VOC / PO NO'),
                Forms\Components\DatePicker::make('deadline')
                    ->required()
                    ->label('Deadline'),
                Forms\Components\Select::make('status')
                    ->native(false)
                    ->required()
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'ongoing' => 'Ongoing',
                        'finished' => 'Finished',
                    ])
                    ->default('pending'),
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
        ->query(Project::query())
        ->columns([
            Tables\Columns\TextColumn::make('perusahaan')
                ->searchable(),
            Tables\Columns\TextColumn::make('quantity')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('deadline')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->searchable(),
            Tables\Columns\TextColumn::make('voc')
            ->label('VOC / PO NO'),
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
            ->headerActions([])
            ->actions([
                Action::make('Export')
                    ->label('Print')
                    ->action(function (Project $record) {
                        // Export the single project, not selected records
                        return Excel::download(new ProjectExport([$record->id]), 'Project-' . $record->id . '-' .$record->perusahaan. '.xlsx');
                    })
                    ->icon('heroicon-o-arrow-down-on-square'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(function (Project $record) {
                        $record->items()->update(['project_id' => null]);
                        $record->delete();
                        Notification::make()
                        ->title('Project and associated items successfully unlinked and deleted.')
                        ->success()
                        ->send();
                    }),
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
        'items' => RelationManagers\ItemsRelationManager::class,
    ];
}
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
            'view' => Pages\ViewProject::route('/{record}'),
        ];
    }
}
