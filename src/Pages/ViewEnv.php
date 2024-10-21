<?php

namespace GeoSot\FilamentEnvEditor\Pages;

use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Concerns\InteractsWithHeaderActions;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Table;
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

    protected static string $view = 'filament-env-editor::view-editor';

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

    public function form(Form $form): Form
    {
        $tabs = Forms\Components\Tabs::make('Tabs')
            ->tabs([
                Forms\Components\Tabs\Tab::make(__('filament-env-editor::filament-env-editor.tabs.current-env.title'))
                    ->schema($this->getFirstTab()),
                Forms\Components\Tabs\Tab::make(__('filament-env-editor::filament-env-editor.tabs.backups.title'))
                    ->schema($this->getSecondTab()),
            ]);

        return $form
            ->schema([$tabs]);
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
            })
            ->filter(fn (Forms\Components\Section $s) => $s->hasChildComponentContainer(true))
            ->all();

        $header = Forms\Components\Group::make([
            Forms\Components\Actions::make([
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
                return Forms\Components\Group::make([
                    Forms\Components\Actions::make([
                        DeleteBackupAction::make("delete_{$obj->name}")->setEntry($obj),
                        DownloadEnvFileAction::make("download_{$obj->name}")->setEntry($obj->name)->hiddenLabel()->size(ActionSize::Small),
                        RestoreBackupAction::make("restore_{$obj->name}")->setEntry($obj->name),
                        ShowBackupContentAction::make("show_raw_content_{$obj->name}")->setEntry($obj),
                    ])->alignEnd(),
                    Forms\Components\Placeholder::make('name')
                        ->label('')
                        ->content(new HtmlString("<strong>{$obj->name}</strong>"))
                        ->columnSpan(2),
                    Forms\Components\Placeholder::make('created_at')
                        ->label('')
                        ->content($obj->createdAt->format(Table::$defaultDateTimeDisplayFormat))
                        ->columnSpan(2),
                ])->columns(5);
            })->all();

        $header = Forms\Components\Group::make([
            Forms\Components\Actions::make([
                DownloadEnvFileAction::make('download_current}')->tooltip('')->outlined(false),
                UploadBackupAction::make('upload'),
                MakeBackupAction::make('backup'),
            ])->alignEnd(),
        ]);

        return [$header, ...$data];
    }
}
