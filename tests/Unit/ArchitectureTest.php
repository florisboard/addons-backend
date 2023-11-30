<?php

uses()->group('Architecture');

test('dd and dump should not be used')
    ->expect(['dd', 'dump'])
    ->not->toBeUsed();

test('models should extend eloquent model and should be classes')
    ->expect('App\Models')
    ->toBeClasses()
    ->toExtend('Illuminate\Database\Eloquent\Model')
    ->ignoring('App\Models\Scopes');

test('scopes should extend scope')
    ->expect('App\Models\Scopes')
    ->toHaveSuffix('Scope')
    ->toImplement('Illuminate\Database\Eloquent\Scope');

test('controllers should have a suffix controller')
    ->expect('App\Http\Controllers')
    ->toHaveSuffix('Controller');
