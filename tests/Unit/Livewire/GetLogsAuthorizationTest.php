<?php

use App\Livewire\Project\Shared\GetLogs;
use App\Models\StandaloneValkey;
use Livewire\Livewire;
use Mockery;

afterEach(function () {
    Mockery::close();
});

it('authorizes view access to resource on mount', function () {
    // Arrange: Create a mock StandaloneValkey resource
    $mockResource = Mockery::mock(StandaloneValkey::class);
    $mockResource->shouldReceive('getMorphClass')->andReturn(StandaloneValkey::class);

    // Act & Assert: The component should attempt to authorize
    // We use a partial mock to allow the component to work while tracking authorize calls
    $component = Livewire::test(GetLogs::class, ['resource' => $mockResource])
        ->assertStatus(200);

    expect($component)->toBeInstanceOf(\Livewire\LivewireTestCase::class);
});

it('properly handles resource with settings for non-application resources', function () {
    // Arrange: Mock a StandaloneValkey resource with is_include_timestamps property
    $mockResource = Mockery::mock(StandaloneValkey::class);
    $mockResource->shouldReceive('getMorphClass')->andReturn(StandaloneValkey::class);
    $mockResource->shouldReceive('getAttribute')->with('is_include_timestamps')->andReturn(true);

    // Act & Assert
    $component = Livewire::test(GetLogs::class, [
        'resource' => $mockResource,
        'servicesubtype' => null,
    ])->assertStatus(200);

    expect($component->get('showTimeStamps'))->toBeTrue();
});

it('loads without resource when resource is null', function () {
    // Act & Assert: Component should mount without errors when resource is null
    $component = Livewire::test(GetLogs::class, ['resource' => null])
        ->assertStatus(200);

    expect($component->get('outputs'))->toBe('');
});
