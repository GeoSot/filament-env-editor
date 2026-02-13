<?php

namespace GeoSot\FilamentEnvEditor\Pages\Actions;

use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Size;
use GeoSot\EnvEditor\Dto\EntryObj;
use GeoSot\EnvEditor\Facades\EnvEditor;
use GeoSot\FilamentEnvEditor\Pages\ViewEnv;

class DeleteAction extends Action
{
    use CanCustomizeProcess;

    private string $entryKey;

    public static function getDefaultName(): ?string
    {
        return 'delete';
    }

    public function setEntry(EntryObj $obj): static
    {
        $this->entryKey = $obj->key;

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-o-trash');
        $this->hiddenLabel();
        $this->color(Color::Rose);
        $this->size(Size::Small);
        $this->modalIcon('heroicon-o-trash');
        $this->outlined();
        $this->requiresConfirmation();

        $this->tooltip(fn (): string => __(
            'filament-env-editor::filament-env-editor.actions.delete.tooltip',
            ['name' => $this->entryKey]
        ));

        $this->modalHeading(fn (): string => __(
            'filament-env-editor::filament-env-editor.actions.delete.confirm.title',
            ['name' => $this->entryKey]
        ));

        $this->action(function (ViewEnv $page) {
            $result = EnvEditor::deleteKey($this->entryKey);

            $result ? $this->success() : $this->failure();

            $page->triggerRefresh();
        });
    }
}
