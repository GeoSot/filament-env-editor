<?php

namespace GeoSot\FilamentEnvEditor\Pages\Actions\Backups;

use Filament\Forms\Components\Actions\Action;
use Filament\Support\Colors\Color;
use GeoSot\EnvEditor\EnvEditor;
use GeoSot\EnvEditor\Exceptions\EnvException;

class DownloadEnvFileAction extends Action
{
    private string $file = '';

    public static function getDefaultName(): ?string
    {
        return 'download';
    }

    public function setEntry(string $file): static
    {
        $this->file = $file;

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(fn (): string => __('filament-env-editor::filament-env-editor.actions.download.title'));
        $this->tooltip(fn (): string => __('filament-env-editor::filament-env-editor.actions.download.tooltip', ['name' => $this->file]));

        $this->outlined();
        $this->icon('heroicon-c-inbox-arrow-down');

        $this->action(function () {
            $result = false;
            try {
                $result = app(EnvEditor::class)->getFilePath($this->file);
            } catch (EnvException $exception) {
                $this->failureNotificationTitle($exception->getMessage());
                $this->failure();
                $this->halt();
            }

            return response()->download($result);
        });

        $this->color(Color::Sky);
    }
}
