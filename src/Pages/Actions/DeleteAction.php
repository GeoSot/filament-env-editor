<?php

namespace GeoSot\FilamentEnvEditor\Pages\Actions;

use Filament\Forms\Components\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\ActionSize;
use GeoSot\EnvEditor\Facades\EnvEditor;
use GeoSot\EnvEditor\Helpers\EntryObj;
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

        $this->size(ActionSize::Small);
        $this->tooltip(fn (): string => __('filament-env-editor::filament-env-editor.page.actions.delete.tooltip', ['name' => $this->entry->key]));
        $this->modalIcon('heroicon-o-trash');
        $this->modalHeading(fn (): string => __('filament-env-editor::filament-env-editor.page.actions.delete.confirm.title', ['name' => $this->entry->key]));

        $this->requiresConfirmation();
    }
}
