<?php

namespace Tests\App\Http\Controllers;

use TestCase;

class NotesControllerTest extends TestCase
{
    /** @test **/
    public function index_status_code_should_be_200(){
        $this->get('/notes')->seeStatusCode(200);
    }

    /** @test **/
    public function index_should_return_a_collection_of_records(){
        $this->get('/notes')
        ->seeJson([
            'title' => 'Nota1'
        ])
        ->seeJson([
            'title' => 'Nota2'
        ]);

    }

    /** @test **/
    public function show_should_return_a_valid_note(){

        $this
            ->get('/notes/1')
            ->seeStatusCode(200)
            ->seeJson([
                'id' => 1,
                'title' => 'Nota1',
                'description' => 'Esta es mi primera Nota',
                'author' => 'Carlos Ceron'
            ]);

            $data = json_decode($this->response->getContent(), true);
            $this->assertArrayHasKey('created_at', $data);
            $this->assertArrayHasKey('updated_at', $data);
    }

    /** @test **/
    public function show_should_fail_when_the_note_id_does_not_exist(){
        
        $this
        ->get('/notes/99999')
        ->seeStatusCode(404)
        ->seeJson([
            'error' => [
                'message' => 'Nota no encontrada'
            ]
        ]);

    }

    /** @test **/
    public function show_route_should_not_match_an_invalid_route(){
        
        $this->get('/notes/esta-ruta-es-valida');

        $this->assertNotRegExp(
            '/notes not found/',
            $this->response->getContent(),
            'NotesController@show route matching when it should not.'
        );

    }

    /** @test **/
    public function store_should_save_new_note_in_the_database(){

        $this->post('/notes', [
            'title' => 'Nota3',
            'description' => 'Esta es la nota 3',
            'author' => 'Carlos Ceron'
        ]);

        $this->seeJson(['created' => true])
        ->seeInDatabase('notes', ['title' => 'Nota3']);
    }

    /** @test **/
    public function store_should_respond_with_a_201_and_location_header_when_successful(){
        
        $this->post('/notes', [
            'title' => 'Nota4',
            'description' => 'Esta es la nota 4',
            'author' => 'Carlos Ceron'
        ]);

        $this->seeStatusCode(201)
        ->seeHeaderWithRegExp('Location', '#/notes/[\d]+$#');

    }

    /** @test **/
    public function update_should_only_change_fillable_fields(){
        
        $this->notSeeInDatabase('notes', [
            'title' => 'Nota5'
        ]);

        $this->put('/notes/1', [
            'id' => 5,
            'title' => 'Nota5',
            'description' => 'Esta es mi Segunda Nota9',
            'author' => 'Carlos Ceron'
        ]);

        $this->seeStatusCode(200)
        ->seeJson([
            'id' => 1,
            'title' => 'Nota5',
            'description' => 'Esta es mi Segunda Nota9',
            'author' => 'Carlos Ceron'
        ])
        ->seeInDatabase('notes', [
            'title' => 'Nota5'
        ]);

    }

    /** @test **/
    public function update_should_fail_with_an_invalid_id(){
        $this
        ->put('/notes/9999')
        ->seeStatusCode(404)
        ->seeJsonEquals([
            'error' => [
                'message' => 'Note no found'
            ]
        ]);
    }

    /** @test **/
    public function update_should_not_match_an_invalid_route(){
        $this->put('/notes/esta-es-errada')
            ->seeStatusCode(404);
    }

     /** @test **/
    public function destroy_should_remove_a_valid_note(){
        
        $this
        ->delete('/notes/1')
        ->seeStatusCode(204)
        ->isEmpty();

        $this->notSeeInDatabase('notes', ['id' => 1]);
    }

    /** @test **/
    public function destroy_should_return_a_404_with_an_invalid_id(){
        $this
        ->delete('/notes/99999')
        ->seeStatusCode(404)
        ->seeJsonEquals([
            'error' => [
                'message' => 'Note no encontrada'
            ]
        ]);
    }

    public function destroy_should_not_match_an_invalid_route(){
        $this->delete('/notes/esta-ruta-no-existe')
        ->seeStatusCode(404);
    }

}

?>