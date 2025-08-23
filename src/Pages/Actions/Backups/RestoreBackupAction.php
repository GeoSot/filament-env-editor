<?php

namespace GeoSot\FilamentEnvEditor\Pages\Actions\Backups;

use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use Filament\Support\Colors\Color;
use GeoSot\EnvEditor\Facades\EnvEditor;
use GeoSot\FilamentEnvEditor\Pages\ViewEnv;

class RestoreBackupAction extends Action
{
    private string $file;

    public static function getDefaultName(): ?string
    {
        return 'restore';
    }

    public function setEntry(string $file): static
    {
        $this->file = $file;

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-s-bars-arrow-up');
        $this->hiddenLabel();
        $this->outlined();
        $this->color(Color::Teal);

        $this->size(Size::Small);
        $this->tooltip(fn (): string => __('filament-env-editor::filament-env-editor.actions.restore-backup.tooltip',
            ['name' => $this->file]));
        $this->modalIcon('heroicon-s-bars-arrow-up');
        $this->modalHeading(fn (
        ): string => __('filament-env-editor::filament-env-editor.actions.restore-backup.confirm.title',
            ['name' => $this->file]));

        $this->action(function (ViewEnv $page) {
            EnvEditor::restoreBackUp($this->file);
            $page->refresh();
        });

        $this->requiresConfirmation();
        $this->modalSubmitActionLabel(fn (
        ) => __('filament-env-editor::filament-env-editor.actions.restore-backup.modalSubmit'));
    }
}
