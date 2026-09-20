<?php

namespace Tests\Feature;

use Tests\TestCase;

class TutorialHighlightTest extends TestCase
{
    public function test_tutorial_highlight_only_runs_when_tutorial_is_open(): void
    {
        $layout = file_get_contents(resource_path('views/layouts/auth2.blade.php'));

        $this->assertStringContainsString('if (!this.tutorialOpen) return;', $layout);
    }
}
