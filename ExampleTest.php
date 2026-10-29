<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the application returns a successful response', function (): void {
    $this->get(route('home'))->assertOk();
});
