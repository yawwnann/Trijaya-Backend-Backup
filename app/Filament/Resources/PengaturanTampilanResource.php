<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengaturanTampilanResource\Pages;
use App\Filament\Resources\PengaturanTampilanResource\RelationManagers;
use App\Models\PengaturanTampilan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengaturanTampilanResource extends Resource
{
    protected static ?string $model = PengaturanTampilan::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('gambar_banner')
                    ->label('Gambar Banner')
                    ->helperText('Gambar akan diupload otomatis ke Gumlet saat simpan.')
                    ->image()
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1920')
                    ->imageResizeTargetHeight('1080'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar_banner')
                    ->label('Gambar Banner')
                    ->height(120)
                    ->width(200),
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
            'index' => Pages\ListPengaturanTampilans::route('/'),
            'create' => Pages\CreatePengaturanTampilan::route('/create'),
            'edit' => Pages\EditPengaturanTampilan::route('/{record}/edit'),
        ];
    }
}
