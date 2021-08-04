<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Rules\Lowercase;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $books = Book::all();
            if($books->isEmpty()){
                return response()->api(BookResource::collection($books),false,'No record found')->setStatusCode(Response::HTTP_OK);
            }
            return response()->api(BookResource::collection($books),true,'Records found')->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $exception) {
            Log::channel('logError')->error("get-books : ". $exception);
            return response()->json(['error'=>'Internal server Error','success'=>false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $rules = array(
                'name' => ['required','max:255','regex:/^[a-zA-Z0-9\s]+$/',new Lowercase],
                'authorName' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            );
            $validator = Validator::make($request->all(),$rules);
            if($validator->fails()){
                return response()->json(['error'=>$validator->errors(),'success'=>false],Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $book = Book::create([
                'name' => strtolower(trim($request->name, " ")),
                'authorName' => trim($request->authorName, " "),
            ]);
            if(!$book) {       
                return response()->json(['error','Could not Create New Record','success'=>false], Response::HTTP_NOT_FOUND);
            }
            return response()->api(new BookResource($book),true,'Record created successfully')->setStatusCode(Response::HTTP_CREATED);

        } catch (\Exception $exception) {
            Log::channel('logError')->error("post-book : ". $exception);
            return response()->json(['error'=>'Internal server error','success'=>false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $book = Book::find($id);
            if(!$book) {
                return response()->json(['error'=>'Invalid uuid','success'=>false], Response::HTTP_NOT_FOUND);
            }
            return response()->api(new BookResource($book),true,'Record found')->setStatusCode(Response::HTTP_OK);

        } catch (\Exception $exception){
            Log::channel('logError')->error("show-book : ". $exception);
            return response()->json(['error'=>'Internal server error','success'=>false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // return new BookResource(Book::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $book = Book::find($id);
            if(!$book) {
                return response()->json(['error'=>'Invalid uuid','success'=>false], Response::HTTP_NOT_FOUND);
            }

            $rules = array(
                'name' => ['required','max:255','regex:/^[a-zA-Z0-9\s]+$/',new Lowercase],
                'authorName' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            );
            $validator = Validator::make($request->all(),$rules);
            if($validator->fails()){
                return response()->json(['error'=>$validator->errors(),'success'=>false],Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $book->update([
                'name' => strtolower(trim($request->name, " ")),
                'authorName' => trim($request->authorName, " "),
            ]);
            if(!$book) {
                return response()->json(['error'=>'Update Failed','success'=>false], Response::HTTP_NOT_FOUND);
            }
            return response()->api(new BookResource($book),true,'Record updated successfully')->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $exception) {
            Log::channel('logError')->error("update-book : ". $exception);
            return response()->json(['error'=>'Internal server error','success'=>false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $book = Book::find($id);
            if(!$book) {
                return response()->json(['error'=>'Invalid uuid','success'=>false], Response::HTTP_NOT_FOUND);
            }
            $deleteBook = $book->destroy($id);
            if(!$deleteBook) {
                return response()->json(['error'=>'Delete Failed','success'=>false], Response::HTTP_NOT_FOUND);
            }
            return response(['message' => 'Deleted successfully','success'=>true], Response::HTTP_OK);
        } catch (\Exception $exception) {
            Log::channel('logError')->error("delete-book : ". $exception);
            return response()->json(['error'=>'Internal server error','success'=>false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}