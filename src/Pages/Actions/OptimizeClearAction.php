<?php

namespace GeoSot\FilamentEnvEditor\Pages\Actions;

use Filament\Actions\Action;
use Illuminate\Foundation\Console\OptimizeClearCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class OptimizeClearAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'optimize-clear';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->outlined();
        $this->label(fn (): string => __('filament-env-editor::filament-env-editor.actions.clear-cache.title'));
        $this->tooltip(fn (): string => __('filament-env-editor::filament-env-editor.actions.clear-cache.tooltip'));
        $this->action(function () {
            $result = Artisan::call(OptimizeClearCommand::class);

            $output = Str::replace('..................................................', '.', Artisan::output());

            $this->successNotificationTitle(new HtmlString("<pre>$output</div>"));
            $this->failureNotificationTitle(new HtmlString("<pre>$output</div>"));
            0 === $result ? $this->success() : $this->failure();
        });
    }
}
