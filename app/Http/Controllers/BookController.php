<?php

namespace App\Http\Controllers;

use App\Book;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function get($id)
    {
        $data = Book::where('id', $id)->first();
        return $this->formatApiResponse('Book Data Generated Successfully', 200, $data);
    }

    public function getAll()
    {
        $data = Book::all();
        return $this->formatApiResponse('All Book Data Generated Successfully', 200, $data);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required | string',
                'author' => 'required | string',
                'description' => 'required | string',
                'published_at' => 'required | date'
            ]);
            if ($validator->fails()) {
                return $this->formatApiResponse('Validation error occured', 422, null, $validator->errors());
            }
            $data = Book::create([
                'name' => $request->name,
                'author' => $request->author,
                'description' => $request->description,
                'published_at' => $request->published_at
            ]);
            return $this->formatApiResponse('Book Successfully Created', 201, $data);
        } catch (Exception $e) {
            return $this->formatApiResponse('Error while creating book', 500, null, $e);
        }
    }

    public function formatApiResponse($message, $status, $data = null, $errors = null)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'errors' => $errors,
        ], $status);
    }
}
