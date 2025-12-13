<?php

namespace GeoSot\FilamentEnvEditor\Pages\Actions\Backups;

use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Size;
use GeoSot\EnvEditor\Dto\BackupObj;
use GeoSot\EnvEditor\Facades\EnvEditor;
use GeoSot\FilamentEnvEditor\Pages\ViewEnv;

class DeleteBackupAction extends Action
{
    private BackupObj $entry;

    public static function getDefaultName(): ?string
    {
        return 'delete';
    }

    public function setEntry(BackupObj $obj): static
    {
        $this->entry = $obj;

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-o-trash');
        $this->hiddenLabel();
        $this->outlined();
        $this->color(Color::Rose);

        $this->size(Size::Small);
        $this->tooltip(fn (): string => __('filament-env-editor::filament-env-editor.actions.delete-backup.tooltip', ['name' => $this->entry->name]));
        $this->modalIcon('heroicon-o-trash');
        $this->modalHeading(fn (): string => __('filament-env-editor::filament-env-editor.actions.delete-backup.confirm.title', ['name' => $this->entry->name]));

        $this->action(function (ViewEnv $page) {
            EnvEditor::deleteBackup($this->entry->name);
            $page->refresh();
        });

        $this->requiresConfirmation();
    }
}
