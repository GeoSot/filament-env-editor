<?php

namespace GeoSot\FilamentEnvEditor\Pages\Actions;

use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use GeoSot\EnvEditor\Dto\EntryObj;
use GeoSot\EnvEditor\Exceptions\EnvException;
use GeoSot\EnvEditor\Facades\EnvEditor;
use GeoSot\FilamentEnvEditor\Pages\ViewEnv;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CreateAction extends Action
{
    use CanCustomizeProcess;

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(fn (): string => __('filament-env-editor::filament-env-editor.actions.add.title'));
        $this->modalHeading(fn (): string => __('filament-env-editor::filament-env-editor.actions.add.modalHeading'));

        $this->color(Color::Teal);
        $this->modalWidth(Width::Large);

        $this->schema($this->getFormFields());

        $this->action(function (array $data, ViewEnv $page) {
            try {
                $options = Arr::get($data, 'index')
                    ? ['index' => Arr::get($data, 'index')]
                    : [];

                $result = EnvEditor::addKey(
                    $data['key'],
                    $data['value'],
                    $options
                );

                $this->successNotificationTitle(
                    __(
                        'filament-env-editor::filament-env-editor.actions.add.success.title',
                        ['name' => $data['key']]
                    )
                );

                $result ? $this->success() : $this->failure();

                $page->triggerRefresh();
            } catch (EnvException $exception) {
                $this->failureNotificationTitle($exception->getMessage());
                $this->failure();
                $this->halt();
            }
        });
    }

    /**
     * @return list<Field>
     */
    private function getFormFields(): array
    {
        return [
            TextInput::make('key')
                ->label(__('filament-env-editor::filament-env-editor.actions.add.form.fields.key'))
                ->required(),
            TextInput::make('value')
                ->label(__('filament-env-editor::filament-env-editor.actions.add.form.fields.value')),
            Select::make('index')
                ->label(__('filament-env-editor::filament-env-editor.actions.add.form.fields.index'))
                ->helperText(__('filament-env-editor::filament-env-editor.actions.add.form.helpText.index'))
                ->options(fn () => $this->getExistingKeys())
                ->searchable(),
        ];
    }

    /**
     * @return array<string,array<string,string>>
     */
    private function getExistingKeys(): array
    {
        return EnvEditor::getEnvFileContent()
            ->filter(fn (EntryObj $obj) => !$obj->isSeparator())
            ->groupBy('group')
            ->keyBy(
                fn (Collection $c): string => Str::of($c->first()->key)->before('_')->remove('#')->lower()->prepend(
                    '--- '
                )
            )
            ->map(fn (Collection $c): Collection => $c->mapWithKeys(fn (EntryObj $obj) => [$obj->index => $obj->key]))
            ->toArray();
    }
}
