<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NotesController{

    public function index(){
        
        return Note::all();
    }

    public function show($id){

        try{
            return Note::findOrFail($id);
        }catch(ModelNotFoundException $e){

            return response()->json([
                'error' => [
                    'message' => 'Nota no encontrada'
                ]
            ], 404);

        }
    }

    public function store(Request $request){

        $note = Note::create($request->all());

        return response()->json(['created' => true], 201, [
            'Location' => route('notes.show', ['id' => $note->id])
        ]);
    }

    public function update(Request $request, $id){

        try{
            $note = Note::findOrFail($id);
        }catch(ModelNotFoundException $e){
            return response()->json([
                'error' => [
                    'message' => 'Note no found'
                ]
            ], 404);
        }

        $note->fill($request->all());
        $note->save();

        return $note;
    }

    public function destroy($id){
        
        try{
            $note = Note::findOrFail($id);    
        }catch(ModelNotFoundException $e){
            return response()->json([
                'error' => [
                    'message' => 'Note no encontrada'
                ]
            ], 404);
        }

            $note->delete();

            return response(null, 204);
    }
}


?>