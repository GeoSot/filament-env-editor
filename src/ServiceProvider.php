<?php

namespace GeoSot\FilamentEnvEditor;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('filament-env-editor')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command->askToStarRepoOnGitHub('geo-sot/filament-env-editor');
            })
            ->hasTranslations()
            ->hasViews();
    }
}
