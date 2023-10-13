<?php

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use LaravelLiberu\Users\Models\User;
use Tests\TestCase;

class LogsTest extends TestCase
{
    use RefreshDatabase;

    private $faker;
    private $log;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
        $this->log = 'laravel.log';

        $this->seed()
            ->actingAs(User::first());
    }

    public function tearDown(): void
    {
        $this->cleanUp();

        parent::tearDown();
    }

    /** @test */
    public function can_access_logs_index()
    {
        Log::info($this->faker->word);

        $this->get(route('system.logs.index', [], false))
            ->assertStatus(200)
            ->assertJsonFragment(['name' => 'laravel.log']);
    }

    /** @test */
    public function can_view_log()
    {
        Log::info($this->faker->word);

        $this->get(route('system.logs.show', $this->log, false))
            ->assertStatus(200)
            ->assertJsonFragment(['name' => 'laravel.log']);
    }

    /** @test */
    public function cant_view_if_file_exceeds_limit()
    {
        Log::info($this->faker->words(30000));

        $this->get(route('system.logs.show', $this->log, false))
            ->assertJsonStructure(['message'])
            ->assertStatus(488);
    }

    /** @test */
    public function can_download_log_file()
    {
        Log::info($this->faker->word);

        $response = $this->get(route('system.logs.download', $this->log, false))
            ->assertStatus(200)
            ->assertHeader(
                'content-disposition',
                'attachment; filename='.$this->log
            );

        $this->assertEquals(
            storage_path('logs'.DIRECTORY_SEPARATOR.$this->log),
            $response->getFile()->getRealPath()
        );
    }

    /** @test */
    public function empty()
    {
        Log::info($this->faker->word);

        $this->delete(route('system.logs.destroy', $this->log, false))
            ->assertStatus(200)
            ->assertJsonStructure(['log', 'message']);

        $this->assertEquals('', File::get($this->logPath()));
    }

    private function cleanUp()
    {
        File::put($this->logPath(), '');
    }

    private function logPath()
    {
        return storage_path('logs').DIRECTORY_SEPARATOR.$this->log;
    }
}
