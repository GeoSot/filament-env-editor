<?php

namespace GeoSot\FilamentEnvEditor\Pages\Actions\Backups;

use Filament\Forms\Components\Actions\Action;
use Filament\Support\Colors\Color;
use GeoSot\EnvEditor\Exceptions\EnvException;
use GeoSot\EnvEditor\Facades\EnvEditor;
use GeoSot\FilamentEnvEditor\Pages\ViewEnv;

class MakeBackupAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(fn (): string => __('filament-env-editor::filament-env-editor.actions.backup.title'));

        $this->action(function (array $data, ViewEnv $page) {
            $result = false;
            try {
                $result = EnvEditor::backUpCurrent();
                $page->refresh();
                $this->successNotificationTitle(fn (
                ): string => __('filament-env-editor::filament-env-editor.actions.backup.success.title'));
            } catch (EnvException $exception) {
                $this->failureNotificationTitle($exception->getMessage());
                $this->failure();
                $this->halt();
            }

            $result ? $this->success() : $this->failure();
        });

        $this->color(Color::Teal);
    }
}
