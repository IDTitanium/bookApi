<?php

use App\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawFile = storage_path('app/books.json');

        $jsonString = file_get_contents($rawFile);
        $data = json_decode($jsonString, true);

        foreach ($data as $datum) {
            Book::firstOrcreate([
                "name" => $datum['name'],
                "author" => $datum['author'],
                "description" => $datum['description'],
                "published_at" => $datum['published_at']
            ]);
        }
    }
}
