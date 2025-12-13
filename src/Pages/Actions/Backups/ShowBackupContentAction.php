<?php

namespace GeoSot\FilamentEnvEditor\Pages\Actions\Backups;

use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Size;
use GeoSot\EnvEditor\Dto\BackupObj;
use Illuminate\Support\HtmlString;

class ShowBackupContentAction extends Action
{
    private BackupObj $entry;

    public static function getDefaultName(): ?string
    {
        return 'show_raw_content_';
    }

    public function setEntry(BackupObj $obj): static
    {
        $this->entry = $obj;

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->modalHeading(fn (): string => __('filament-env-editor::filament-env-editor.actions.show-content.modalHeading', ['name' => $this->entry->name]));
        $this->tooltip(fn (): string => __('filament-env-editor::filament-env-editor.actions.show-content.tooltip'));
        $this->hiddenLabel();
        $this->outlined();
        $this->modalSubmitAction(false);
        $this->icon('heroicon-o-newspaper');
        $this->size(Size::Small);

        $this->schema(fn () => [
            TextEntry::make('')->state(new HtmlString("<pre>{$this->entry->rawContent}</pre>")),
        ]);

        $this->color(Color::Zinc);
    }
}
