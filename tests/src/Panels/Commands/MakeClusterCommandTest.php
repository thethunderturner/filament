<?php

use Filament\Tests\TestCase;
use Illuminate\Support\Arr;

use function PHPUnit\Framework\assertFileExists;

uses(TestCase::class);

beforeEach(function () {
    $this->withoutMockingConsoleOutput();
})
    ->skip((bool) Arr::get($_SERVER, 'PARATEST'), 'File generation tests cannot be run in parallel as they would share a filesystem and have the potential to conflict with each other.');

it('can generate a cluster class', function () {
    $this->artisan('make:filament-cluster', [
        'name' => 'Blog',
        '--panel' => 'admin',
        '--no-interaction' => true,
    ]);

    assertFileExists($path = app_path('Filament/Clusters/Blog/BlogCluster.php'));
    expect(file_get_contents($path))
        ->toMatchSnapshot();
});

it('can generate a cluster class in a nested directory', function () {
    $this->artisan('make:filament-cluster', [
        'name' => 'Website/Blog',
        '--panel' => 'admin',
        '--no-interaction' => true,
    ]);

    assertFileExists($path = app_path('Filament/Clusters/Website/Blog/BlogCluster.php'));
    expect(file_get_contents($path))
        ->toMatchSnapshot();
});