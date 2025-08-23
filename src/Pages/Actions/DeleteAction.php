<?php

namespace GeoSot\FilamentEnvEditor\Pages\Actions;

use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use Filament\Support\Colors\Color;
use GeoSot\EnvEditor\Dto\EntryObj;
use GeoSot\EnvEditor\Facades\EnvEditor;
use GeoSot\FilamentEnvEditor\Pages\ViewEnv;

class DeleteAction extends Action
{
    private EntryObj $entry;

    public static function getDefaultName(): ?string
    {
        return 'delete';
    }

    public function setEntry(EntryObj $obj): static
    {
        $this->entry = $obj;

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-o-trash');
        $this->hiddenLabel();
        $this->color(Color::Rose);
        $this->action(function (ViewEnv $page) {
            EnvEditor::deleteKey($this->entry->key);
            $page->refresh();
        });

        $this->size(Size::Small);
        $this->tooltip(fn (): string => __('filament-env-editor::filament-env-editor.actions.delete.tooltip', ['name' => $this->entry->key]));
        $this->modalIcon('heroicon-o-trash');
        $this->modalHeading(fn (): string => __('filament-env-editor::filament-env-editor.actions.delete.confirm.title', ['name' => $this->entry->key]));
        $this->outlined();
        $this->requiresConfirmation();
    }
}
