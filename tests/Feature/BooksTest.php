<?php

namespace Tests\Feature;

use App\Traits\RefreshDatabaseTransactionLess;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksTest extends TestCase
{
    use RefreshDatabase, RefreshDatabaseTransactionless {
        RefreshDatabaseTransactionless::beginDatabaseTransaction insteadof RefreshDatabase;
    }
    
    // store a book test
    /** @test */
    public function add_book(){
        $formData = [
            'name' => 'book name',
            'authorName' => 'demo author name',
        ];

        $response = $this->postJson(route('books.store'),$formData)
            ->assertStatus(201);
        
        return $response->decodeResponseJson()['data']['uuid'];
    }

    // get list of all books
    /** @test */
    public function get_all_books(){
        $this->get(route('books.index'))
            ->assertJsonStructure(['data' => ['*' => ['uuid', 'name', 'releaseDate', 'authorName']]])
            ->assertStatus(200);
    }

    // get a book by uuid
    /** 
     * @test
     * @depends add_book
    */
    public function get_a_book($id){
        // error_log($id);
        $this->get(route('books.show', $id))
            ->assertStatus(200);
    }

    // update a book
    /** 
     * @test
     * @depends add_book
    */
    public function update_a_book($id){
        // error_log($id);
        $updatedFormData = [
            'name' => 'updated book name',
            'authorName' => 'updated demo author name',
        ];

        $this->put(route('books.update', $id),$updatedFormData)
            ->assertStatus(200);
    }

    // delete a book
    /** 
     * @test
     * @depends add_book
    */
    public function delete_a_book($id){
        // error_log($id);
        $this->delete(route('books.destroy', $id))
            ->assertStatus(200);
    }
}
