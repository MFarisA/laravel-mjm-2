<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectAssignResource\Pages;
use App\Filament\Resources\ProjectAssignResource\RelationManagers;
use App\Models\ProjectAssign;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class ProjectAssignResource extends Resource
{
    protected static ?string $model = ProjectAssign::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public ProjectAssign $record;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->state([
                'user' => ProjectAssign::all()
            ])
            ->schema([
                TextEntry::make('project.nama_perusahaan'),
                // TextEntry::make('project.users'),
                TextEntry::make('project.quantity'),
                TextEntry::make('project.deskripsi'),
                TextEntry::make('project.jenis_pekerjaan'),
                TextEntry::make('project.deadline'),
                TextEntry::make('project.status'),
                TextEntry::make('user')
                    ->bulleted()
                    // ->schema(fn ($record) => dd($record))
                    // ->formatStateUsing(fn ($record): string => $record->user)
                    ->listWithLineBreaks(),
                ImageEntry::make('project.picture')
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        // ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', auth()->id()))
        ->modifyQueryUsing(function (Builder $query) {
            $user = auth()->id();
            $project = ProjectAssign::where('user_id', $user)->first();

            if ($project) {
                $query->where('project_id', $project->id);
            }

            return $query;
        })
        ->columns([
            Tables\Columns\TextColumn::make('project.nama_perusahaan')
                ->label('Nama Perusahaan'),
            Tables\Columns\TextColumn::make('project.quantity')
                ->label('Quantity'),
            Tables\Columns\TextColumn::make('project.jenis_pekerjaan')
                ->label('Jenis Pekerjaan'),
            Tables\Columns\TextColumn::make('project.deadline')
                ->label('Deadline'),
            Tables\Columns\TextColumn::make('user.name')
                ->label('User Name')
                ->listWithLineBreaks(),
        ])
        ->filters([
            // Tambahkan filter jika diperlukan
        ])
        ->actions([
            // Tables\Actions\EditAction::make(), // Uncomment jika ingin menambahkan aksi edit
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
            'index' => Pages\ListProjectAssigns::route('/'),
            // 'create' => Pages\CreateProjectAssign::route('/create'),
            'view' => Pages\ViewProjectAssign::route('/{record}'),
            // 'edit' => Pages\EditProjectAssign::route('/{record}/edit'),
        ];
    }
}
