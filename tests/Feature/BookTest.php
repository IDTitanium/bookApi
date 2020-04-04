<?php

namespace Tests\Feature;

use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test to get all books.
     *
     * @return void
     */
    public function testCanGetAllBooks()
    {
        $this->withoutExceptionHandling();

        Artisan::call('db:seed --class=BookSeeder');
        $response = $this->get('/api/books');
        $response->assertStatus(201);
        $response->assertSeeText('All Book Data Generated Successfully');
        $responseBody =  $response->decodeResponseJson();
        $this->assertNotEmpty($responseBody['data']);
    }

    public function testCanGetSingleBook()
    {
        $this->withoutExceptionHandling();

        Artisan::call('db:seed --class=BookSeeder');
        $book = Book::first();
        $response = $this->get('/api/books/' . $book->id);
        $response->assertStatus(200);
        $responseBody =  $response->decodeResponseJson();
        $this->assertNotEmpty($responseBody['data']);
        $response->assertJson([

            'message' => 'Book Data Generated Successfully',
            'data' => [
                'name' => $responseBody['data']['name'],
                'author' => $responseBody['data']['author'],
                'description' => $responseBody['data']['description']
            ]

        ]);
    }

    public function testCanSaveBookInformation()
    {
        $this->withoutExceptionHandling();

        $attributes = [
            'name' => 'Beauty and the beast',
            'author' => 'Gabrielle-Suzanne de Villeneuve',
            'description' => 'A widower merchant lives in a mansion with his twelve children (six sons and six daughters). All his daughters are very beautiful, but the youngest, was named “little beauty” for she was the most gorgeous. She continued to be named “Beauty” ‘till she was a young adult.',
            'published_at' => '1740-1-1'
        ];

        $response = $this->post('/api/books', $attributes);
        $response->assertStatus(201);
        $responseBody =  $response->decodeResponseJson();
        $this->assertNotNull($responseBody['data']);
        $this->assertDatabaseHas('books', [
            'name' => $attributes['name'],
            'author' => $attributes['author'],
            'description' => $attributes['description'],
            'published_at' => $attributes['published_at']
        ]);
    }
}
