<?php

namespace App\Http\Controllers;

use App\Book;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class BookController extends Controller
{
    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Return the list of books
     * @return JsonResponse
     */
    public function index(){
        $books = Book::all();
        return $this->successResponse($books);
    }

    /**
     * Create a new book
     * @param Request $request
     * @throws ValidationException
     * @return JsonResponse
     */
    public function store(Request $request){
        $rules = [
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'price' => 'required|min:1',
            'author_id'=> 'required|min:1'
        ];

        $this->validate($request, $rules);

        $book = Book::create($request->all());

        return $this->successResponse($book, Response::HTTP_CREATED);
    }

    /**
     * Get one book
     * @param $book
     * @return JsonResponse
     */
    public function show($book){
        $book = Book::findOrFail($book);
        return $this->successResponse($book);
    }

    /**
     * Update specific book
     * @param Request $request
     * @param $book
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $book){
        $rules = [
            'title' => 'max:255',
            'description' => 'max:255',
            'price' => 'min:1',
            'author_id'=> 'min:1'
        ];

        $this->validate($request, $rules);

        $book = Book::findOrFail($book);

        $book->fill($request->all());

        if($book->isClean()){
            return $this->errorResponse('Nothing to update', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $book->save();

        return $this->successResponse($book);
    }

    /**
     * @param $book
     * @return JsonResponse
     */
    public function destroy($book){
        $book = Book::findOrFail($book);

        $book->delete();

        return $this->successResponse($book);
    }
}
