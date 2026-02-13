<?php

namespace GeoSot\FilamentEnvEditor\Pages\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Size;
use GeoSot\EnvEditor\Dto\EntryObj;
use GeoSot\EnvEditor\Facades\EnvEditor;
use GeoSot\FilamentEnvEditor\Pages\ViewEnv;

class EditAction extends Action
{
    private EntryObj $entry;

    public static function getDefaultName(): ?string
    {
        return 'edit';
    }

    public function setEntry(EntryObj $obj): static
    {
        $this->entry = $obj;

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-c-cog-8-tooth');
        $this->hiddenLabel();
        $this->color(Color::Sky);
        $this->size(Size::Small);
        $this->outlined();

        $this->modalIcon('heroicon-c-cog-8-tooth');
        $this->modalHeading(__('filament-env-editor::filament-env-editor.actions.edit.modal.text'));
        $this->tooltip(
            fn (): string => __(
                'filament-env-editor::filament-env-editor.actions.edit.tooltip',
                ['name' => $this->entry->key]
            )
        );

        $this->schema([
            TextInput::make('key')->default(fn () => $this->entry->key)->required()->readOnly(),
            TextInput::make('value')->default(fn () => $this->entry->getValue()),
        ]);

        $this->action(function (array $data, ViewEnv $page) {
            $result = EnvEditor::editKey($data['key'], $data['value']);
            $result ? $this->success() : $this->failure();
            $page->triggerRefresh();
        });
    }
}
