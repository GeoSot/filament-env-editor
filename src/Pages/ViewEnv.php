<?php

namespace GeoSot\FilamentEnvEditor\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Concerns\InteractsWithHeaderActions;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Size;
use GeoSot\EnvEditor\Dto\BackupObj;
use GeoSot\EnvEditor\Dto\EntryObj;
use GeoSot\EnvEditor\Facades\EnvEditor;
use GeoSot\FilamentEnvEditor\FilamentEnvEditorPlugin;
use GeoSot\FilamentEnvEditor\Pages\Actions\Backups\DeleteBackupAction;
use GeoSot\FilamentEnvEditor\Pages\Actions\Backups\DownloadEnvFileAction;
use GeoSot\FilamentEnvEditor\Pages\Actions\Backups\MakeBackupAction;
use GeoSot\FilamentEnvEditor\Pages\Actions\Backups\RestoreBackupAction;
use GeoSot\FilamentEnvEditor\Pages\Actions\Backups\ShowBackupContentAction;
use GeoSot\FilamentEnvEditor\Pages\Actions\Backups\UploadBackupAction;
use GeoSot\FilamentEnvEditor\Pages\Actions\CreateAction;
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

    protected string $view = 'filament-env-editor::view-editor';

    /**
     * @var list<mixed>
     */
    public array $data = [];

    protected function getHeaderActions(): array
    {
        return [
            OptimizeClearAction::make('optimize-clear'),
        ];
    }

    public function form(Schema $schema): Schema
    {
        $tabs = Tabs::make('Tabs')
            ->tabs([
                Tab::make(__('filament-env-editor::filament-env-editor.tabs.current-env.title'))
                    ->schema(fn () => $this->getFirstTab()),
                Tab::make(__('filament-env-editor::filament-env-editor.tabs.backups.title'))
                    ->schema(fn () => $this->getSecondTab()),
            ]);

        return $schema
            ->components([$tabs]);
    }

    public function triggerRefresh(): void
    {
        $this->dispatch('$refresh');
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

    public static function getSlug(?Panel $panel = null): string
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

    /**
     * @return list<Component>
     */
    private function getFirstTab(): array
    {
        $envData = EnvEditor::getEnvFileContent()
            ->filter(fn (EntryObj $obj) => !$obj->isSeparator())
            ->groupBy('group')
            ->map(function (Collection $group) {
                $fields = $group
                    ->reject(fn (EntryObj $obj) => $this->shouldHideEnvVariable($obj->key))
                    ->map(function (EntryObj $obj) {
                        return Group::make([
                            Actions::make([
                                EditAction::make("edit_{$obj->key}")->setEntry($obj),
                                DeleteAction::make("delete_{$obj->key}")->setEntry($obj),
                            ])->alignEnd(),
                            TextEntry::make($obj->key)
                                ->hiddenLabel()
                                ->state(new HtmlString("<code>{$obj->getAsEnvLine()}</code>"))
                                ->columnSpan(4),
                        ])->columns(5);
                    })
                    ->values();

                if ($fields->isEmpty()) {
                    return null;
                }

                return Section::make()
                    ->schema($fields->all())
                    ->columns(1);
            })
            ->filter()
            ->values()
            ->all();

        $header = Group::make([
            Actions::make([
                CreateAction::make('Add'),
            ])->alignEnd(),
        ]);

        return [$header, ...$envData];
    }

    private function shouldHideEnvVariable(string $key): bool
    {
        return in_array($key, FilamentEnvEditorPlugin::get()->getHiddenKeys());
    }

    /**
     * @return list<Component>
     */
    private function getSecondTab(): array
    {
        $data = EnvEditor::getAllBackUps()
            ->map(function (BackupObj $obj) {
                return Group::make([
                    Actions::make([
                        DeleteBackupAction::make("delete_{$obj->name}")->setEntry($obj),
                        DownloadEnvFileAction::make("download_{$obj->name}")->setEntry($obj->name)->hiddenLabel()->size(Size::Small),
                        RestoreBackupAction::make("restore_{$obj->name}")->setEntry($obj->name),
                        ShowBackupContentAction::make("show_raw_content_{$obj->name}")->setEntry($obj),
                    ])->alignEnd(),
                    TextEntry::make('name')
                        ->label('')
                        ->state(new HtmlString("<strong>{$obj->name}</strong>"))
                        ->columnSpan(2),
                    TextEntry::make('created_at')
                        ->label('')
                        ->state($obj->createdAt->format('Y-m-d H:i:s'))
                        ->columnSpan(2),
                ])->columns(5);
            })->all();

        $header = Group::make([
            Actions::make([
                DownloadEnvFileAction::make('download_current')->tooltip('')->outlined(false),
                UploadBackupAction::make('upload'),
                MakeBackupAction::make('backup'),
            ])->alignEnd(),
        ]);

        return [$header, ...$data];
    }
}
