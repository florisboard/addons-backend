<?php

use App\Models\Project;
use App\Models\Report;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('ProjectReport');

describe('Create', function () {
    test('users can report a project', function () {
        Sanctum::actingAs(User::factory()->create());
        $project = Project::factory()->create();
        $data = Report::factory()->make()->toArray();

        $this->postJson(route('projects.reports.store', $project), $data)
            ->assertCreated();
    });
});
