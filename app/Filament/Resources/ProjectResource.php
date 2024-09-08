<?php

namespace App\Filament\Resources;

use App\Filament\Exports\ProjectExporter;
use App\Filament\Resources\ProjectAssignResource\RelationManagers\UserRelationManager as RelationManagersUserRelationManager;
use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Filament\Resources\ProjectResource\RelationManagers\ItemsRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\ProjectAssignRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\UserRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\UsersRelationManager;
use App\Models\Project;
use App\Models\ProjectAssign;
use App\Models\User;
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

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;

use function Pest\Laravel\session;

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
                Forms\Components\DatePicker::make('deadline')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->native(false)
                    ->required()
                    ->options([
                        'pending' => 'Pending',
                        'ongoing' => 'Ongoing',
                        'finished' => 'Finished',
                    ]),
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
        // ->modifyQueryUsing(fn (Builder $query) => $query->where('users.user_id', auth()->id()))
        // ->modifyQueryUsing(fn (Builder $query) => $query->where('users->name', $data['user_id'] = auth()->id()))
        // ->modifyQueryUsing(function (Builder $query) {
        //     $query->whereHas('nama_perusahaan', function (Builder $query) {
        //         return $query->where('user_id', auth()->id())->first;
        //     });
        // })
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
                // Tables\Filters\SelectFilter::make('users.name')
                //     ->options(User::all()->pluck('id', 'name')->toArray())
            ])
            ->headerActions([
                ExportAction::make()->exporter(ProjectExporter::class)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                // ]),
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make()->exporter(ProjectExporter::class)
            ]);
    }

    // public static function getRelations(): array
    // {
    //     return [
    //         ItemsRelationManager::class
    //     ];
    // }


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
