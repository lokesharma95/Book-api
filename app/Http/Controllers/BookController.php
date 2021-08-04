<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Rules\Lowercase;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *      path="/api/books",
     *      operationId="getBooksList",
     *      tags={"Books"},
     *      summary="Get list of Books",
     *      description="Returns list of books",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="404 Not Found"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error"
     *      )
     *     )
     */
    public function index()
    {
        try {

            // get all records
            $books = Book::all();

            // check if records are empty
            if ($books->isEmpty()) {
                return response()->api(BookResource::collection($books), false, 'No record found')
                    ->setStatusCode(Response::HTTP_OK);
            }

            return response()->api(BookResource::collection($books), true, 'Records found')
                ->setStatusCode(Response::HTTP_OK);

        } catch (\Exception $exception) {
            Log::channel('logError')->error("get-books : " . $exception);
            return response()->json(
                ['success' => false, 'error' => 'Internal server Error'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Post(
     *      path="/api/books",
     *      operationId="storeBook",
     *      tags={"Books"},
     *      summary="Store new book",
     *      description="Returns book data",
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","authorName"},
     *              @OA\Property(property="name", type="string", example="life of pi"),
     *              @OA\Property(property="authorName", type="string", example="love sharma"),
     *          ),
     *
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="404 Not found",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error"
     *      )
     * )
     */
    public function store(Request $request)
    {
        try {

            // Validation
            $rules = array(
                'name' => ['required', 'max:255', 'regex:/^[a-zA-Z0-9\s]+$/', new Lowercase],
                'authorName' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(
                    ['success' => false, 'error' => $validator->errors()],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            // create record if validation passes
            $book = Book::create([
                'name' => strtolower(trim($request->name, " ")),
                'authorName' => trim($request->authorName, " "),
            ]);

            // check if record created successfully
            if (!$book) {
                return response()->json(
                    ['success' => false, 'error', 'Could not Create New Record'],
                    Response::HTTP_NOT_FOUND
                );
            }
            return response()->api(new BookResource($book), true, 'Record created successfully')
                ->setStatusCode(Response::HTTP_CREATED);

        } catch (\Exception $exception) {
            Log::channel('logError')->error("post-book : " . $exception);
            return response()->json(
                ['success' => false, 'error' => 'Internal server error'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *      path="/api/books/{uuid}",
     *      operationId="getBookById",
     *      tags={"Books"},
     *      summary="Get book information",
     *      description="Returns book data",
     *      @OA\Parameter(
     *          name="uuid",
     *          description="Book uuid",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="404 Not found",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error"
     *      )
     * )
     */
    public function show($uuid)
    {
        try {

            // check if record exist or provided uuid is valid or not
            $book = Book::find($uuid);

            // check if record exist, if exist then return response
            if (!$book) {
                return response()->json(
                    ['success' => false, 'error' => 'Invalid uuid'],
                    Response::HTTP_NOT_FOUND
                );
            }

            return response()->api(new BookResource($book), true, 'Record found')
                ->setStatusCode(Response::HTTP_OK);

        } catch (\Exception $exception) {
            Log::channel('logError')->error("show-book : " . $exception);
            return response()->json(
                ['success' => false, 'error' => 'Internal server error'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Put(
     *      path="/api/books/{uuid}",
     *      operationId="updateBook",
     *      tags={"Books"},
     *      summary="Update existing book",
     *      description="Returns updated book data",
     *      @OA\Parameter(
     *          name="uuid",
     *          description="Book uuid",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","authorName"},
     *              @OA\Property(property="name", type="string", example="life of pi part 2"),
     *              @OA\Property(property="authorName", type="string", example="love sharma"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="404 Not found"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error"
     *      )
     * )
     */
    public function update(Request $request, $uuid)
    {
        try {

            // check if record exist or provided uuid is valid or not
            $book = Book::find($uuid);
            if (!$book) {
                return response()->json(
                    ['success' => false, 'error' => 'Invalid uuid'],
                    Response::HTTP_NOT_FOUND
                );
            }

            // validation
            $rules = array(
                'name' => ['required', 'max:255', 'regex:/^[a-zA-Z0-9\s]+$/', new Lowercase],
                'authorName' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(
                    ['success' => false, 'error' => $validator->errors()],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            // update record if validation passed
            $book->update([
                'name' => strtolower(trim($request->name, " ")),
                'authorName' => trim($request->authorName, " "),
            ]);

            // check if updation successfull
            if (!$book) {
                return response()->json(
                    ['success' => false, 'error' => 'Update Failed'],
                    Response::HTTP_NOT_FOUND
                );
            }

            return response()->api(new BookResource($book), true, 'Record updated successfully')
                ->setStatusCode(Response::HTTP_OK);

        } catch (\Exception $exception) {
            Log::channel('logError')->error("update-book : " . $exception);
            return response()->json(
                ['success' => false, 'error' => 'Internal server error'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Delete(
     *      path="/api/books/{uuid}",
     *      operationId="deleteBook",
     *      tags={"Books"},
     *      summary="Delete existing book",
     *      description="Deletes a record and returns success message",
     *      @OA\Parameter(
     *          name="uuid",
     *          description="book uuid",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="404 Not found",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error"
     *      )
     * )
     */
    public function destroy($uuid)
    {
        try {

            // check if record exist or provided uuid is valid or not
            $book = Book::find($uuid);
            if (!$book) {
                return response()->json(
                    ['success' => false, 'error' => 'Invalid uuid'],
                    Response::HTTP_NOT_FOUND
                );
            }

            // delete record if id is valid
            $deleteBook = $book->destroy($uuid);
            if (!$deleteBook) {
                return response()->json(
                    ['success' => false, 'error' => 'Delete Failed'],
                    Response::HTTP_NOT_FOUND
                );
            }

            return response(
                ['success' => true, 'message' => 'Deleted successfully'],
                Response::HTTP_OK
            );

        } catch (\Exception $exception) {
            Log::channel('logError')->error("delete-book : " . $exception);
            return response()->json(
                ['success' => false, 'error' => 'Internal server error'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
