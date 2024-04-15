<?php

namespace GeoSot\FilamentEnvEditor\Pages\Actions\Backups;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Support\Colors\Color;
use GeoSot\EnvEditor\EnvEditor;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UploadBackupAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-s-document-arrow-up');
        $this->label(fn (): string => __('filament-env-editor::filament-env-editor.actions.upload-backup.title'));
        $this->form([
            FileUpload::make('file')->saveUploadedFileUsing(static function (
                BaseFileUpload $component,
                TemporaryUploadedFile $file
            ): ?string {
                $backupsPath = app(EnvEditor::class)->getFilesManager()->getBackupsDir();

                $name = $file->getClientOriginalName().'_uploaded';
                file_put_contents($backupsPath.DIRECTORY_SEPARATOR.$name, $file->getContent());

                return null;
            }),
        ]);

        $this->color(Color::Sky);
    }
}
