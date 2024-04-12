<?php

namespace GeoSot\FilamentEnvEditor\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Concerns\InteractsWithHeaderActions;
use Filament\Pages\Page;
use GeoSot\EnvEditor\Facades\EnvEditor;
use GeoSot\EnvEditor\Helpers\EntryObj;
use GeoSot\FilamentEnvEditor\FilamentEnvEditorPlugin;
use GeoSot\FilamentEnvEditor\Pages\Actions\CreateAction as CreateActionAlias;
use GeoSot\FilamentEnvEditor\Pages\Actions\DeleteAction;
use GeoSot\FilamentEnvEditor\Pages\Actions\EditAction;
use GeoSot\FilamentEnvEditor\Pages\Actions\OptimizeClearAction;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class ViewEnv extends Page
{
    use HasUnsavedDataChangesAlert;
    use InteractsWithFormActions;
    use InteractsWithHeaderActions;

    protected static string $view = 'filament-env-editor::view-editor';

    protected function getHeaderActions(): array
    {
        return [
            CreateActionAlias::make('Add'),

            OptimizeClearAction::make('optimize-clear'),
        ];
    }

    public function form(Form $form): Form
    {
        $envData = EnvEditor::getEnvFileContent()
            ->filter(fn (EntryObj $obj) => !$obj->isSeparator())
            ->groupBy('group')
            ->map(function (Collection $group) {
                $fields = $group->map(function (EntryObj $obj) {
                    return Forms\Components\Group::make([
                        Forms\Components\Actions::make([
                            EditAction::make("edit_{$obj->key}")->setEntry($obj),
                            DeleteAction::make("delete_{$obj->key}")->setEntry($obj),
                        ])->alignEnd(),
                        Forms\Components\Placeholder::make($obj->key)
                            ->label('')
                            ->content(new HtmlString("<code>{$obj->getAsEnvLine()}</code>"))
                            ->columnSpan(4),
                    ])->columns(5);
                });

                return Forms\Components\Section::make()->schema($fields->all())->columns(1);
            })->all();

        return $form
            ->schema($envData);
    }

    public function refresh(): void
    {
    }

    public static function getNavigationGroup(): ?string
    {
        return FilamentEnvEditorPlugin::get()->getNavigationGroup();
    }

    public static function getNavigationSort(): ?int
    {
        return FilamentEnvEditorPlugin::get()->getNavigationSort();
    }

    public static function getNavigationIcon(): string
    {
        return FilamentEnvEditorPlugin::get()->getNavigationIcon();
    }

    public static function getNavigationLabel(): string
    {
        return FilamentEnvEditorPlugin::get()->getNavigationLabel();
    }

    public static function getSlug(): string
    {
        return FilamentEnvEditorPlugin::get()->getSlug();
    }

    public function getTitle(): string
    {
        return __('filament-env-editor::filament-env-editor.page.title');
    }

    public static function canAccess(): bool
    {
        return FilamentEnvEditorPlugin::get()->isAuthorized();
    }
}
