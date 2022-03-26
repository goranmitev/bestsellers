<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\NYT\BestsellersHistory;
use Illuminate\Support\Facades\Storage;


class ApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_bestsellers_history()
    {
        $headers = [];

        Http::fake([
            config('app.nyt_api_url') . '/lists/best-sellers/*' => Http::response(
                json_decode(file_get_contents('tests/responses/nyt_response_200.json'), true)
            , 200, $headers),
        ]);

        $response = $this->getJson('/api/1/nyt/best-sellers');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'OK'
            ]);

        $response->assertJsonStructure([
            'status',
            'num_results',
            'results' => [
                '*' => [
                        'title',
                        'description',
                        'author',
                        'ranks_history' => [
                            '*' => [
                                'rank',
                                'weeks_on_list'
                            ]
                        ]
                ]
            ]
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_bestsellers_history_with_title()
    {
        $headers = [];

        Http::fake([
            config('app.nyt_api_url') . '/lists/best-sellers/*' => Http::response(
                json_decode(file_get_contents('tests/responses/nyt_response_title_200.json'), true)
            , 200, $headers),
        ]);

        $response = $this->json(
            'GET', 
            '/api/1/nyt/best-sellers', 
            [
                'title' => '10 lb Penalty'
            ], 
            $headers
        );

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'title' => '10 lb Penalty'
            ]);
    }

    public function test_bestsellers_history_with_isbn()
    {
        $headers = [];

        Http::fake([
            config('app.nyt_api_url') . '/lists/best-sellers/*' => Http::response(
                json_decode(file_get_contents('tests/responses/nyt_response_isbn_200.json'), true)
            , 200, $headers),
        ]);

        $response = $this->json(
            'GET', 
            '/api/1/nyt/best-sellers', 
            [
                'isbn' => '9781683691136'
            ], 
            $headers
        );

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                "primary_isbn10" => "168369113X",
                "primary_isbn13" => "9781683691136",
            ]);
    }

    public function test_bestsellers_history_with_invalid_isbn()
    {
        $headers = [];

        Http::fake([
            config('app.nyt_api_url') . '/lists/best-sellers/*' => Http::response(
                json_decode(file_get_contents('tests/responses/nyt_response_invalid_isbn_422.json'), true)
            , 422, $headers),
        ]);

        $response = $this->json(
            'GET', 
            '/api/1/nyt/best-sellers', 
            [
                'isbn' => '9781683691136R'
            ], 
            $headers
        );

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors" => [
                    'isbn'
                ]
            ]);
    }

    public function test_bestsellers_history_with_invalid_offset()
    {
        $headers = [];

        Http::fake([
            config('app.nyt_api_url') . '/lists/best-sellers/*' => Http::response(
                json_decode(file_get_contents('tests/responses/nyt_response_invalid_offset_422.json'), true)
            , 422, $headers),
        ]);

        $response = $this->json(
            'GET', 
            '/api/1/nyt/best-sellers', 
            [
                'offset' => '333'
            ], 
            $headers
        );

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors" => [
                    'offset'
                ]
            ]);
    }

    public function test_bestsellers_history_with_author()
    {
        $headers = [];

        Http::fake([
            config('app.nyt_api_url') . '/lists/best-sellers/*' => Http::response(
                json_decode(file_get_contents('tests/responses/nyt_response_author_200.json'), true)
            , 200, $headers),
        ]);

        $response = $this->json(
            'GET', 
            '/api/1/nyt/best-sellers', 
            [
                'author' => 'Fred Rogers'
            ], 
            $headers
        );

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'author' => 'Fred Rogers'
            ]);
    }

    public function test_bestsellers_history_with_nonexistent_author()
    {
        $headers = [];

        Http::fake([
            config('app.nyt_api_url') . '/lists/best-sellers/*' => Http::response(
                json_decode(file_get_contents('tests/responses/nyt_response_no_results_200.json'), true)
            , 200, $headers),
        ]);

        $response = $this->json(
            'GET', 
            '/api/1/nyt/best-sellers', 
            [
                'author' => 'Bla Bla Bla'
            ], 
            $headers
        );

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                "num_results" => 0,
            ]);
    }

    public function test_bestsellers_history_nonexistent_title()
    {
        $headers = [];

        Http::fake([
            config('app.nyt_api_url') . '/lists/best-sellers/*' => Http::response(
                json_decode(file_get_contents('tests/responses/nyt_response_no_results_200.json'), true)
            , 200, $headers),
        ]);

        $response = $this->json(
            'GET', 
            '/api/1/nyt/best-sellers', 
            [
                'title' => 'Bla Bla Bla'
            ], 
            $headers
        );

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                "num_results" => 0,
            ]);
    }
}
