<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $term = $request->term;
        if ($term) {
            $query = Book::with(['category' => function ($query) use ($term) {
                $query->orWhere('name', 'like', "%$term%");
            }])->where(function ($query) use ($term) {
                $query->where('name', 'like', "%$term%")
                ->orWhere('author', 'like', "%$term%")
                ->orWhere('published_date', 'like', "%$term%");
            });
        } else {
            $query = Book::with('category');
        }

        return response()->json($query->paginate(15));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'author' => 'required',
            'published_date' => 'required',
            'category' => 'required'
        ]);

        $book = new Book($request->only('name', 'author', 'user'));
        $book->category = $request->category['value'];
        $book->published_date = (new \DateTime($request->published_date))->format('Y-m-d');
        if ($book->save()) {
            return response()->json($book->load('category'));
        } else {
            return response()->json([
                'status' => false,
                'message' => 'can´t save book'
            ], 500);
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
        //
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
        $this->validate($request, [
            'name' => 'required',
            'author' => 'required',
            'published_date' => 'required',
            'category' => 'required'
        ]);

        $book = Book::find($id);
        if (!$book) {
            return response()->json([
                'status' => false,
                'message' => 'Book not found'
            ], 404);
        }
        $book->name = $request->name;
        $book->author = $request->author;
        $book->published_date = (new \DateTime($request->published_date))->format('Y-m-d');
        $book->category = $request->category['value'];

        if ($book->save()) {
            return response()->json($book->load('category'));
        } else {
            return response()->json([
                'status' => false,
                'message' => 'can´t save book'
            ], 500);
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
        $book = Book::find($id);
        if (!$book) {
            return response()->json([
                'status' => false,
                'message' => 'book not found'
            ], 404);
        }

        if ($book->delete()) {
            return response()->json([
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'can´t delete book'
            ]);
        }
    }

    public function toggle(Request $request, $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json([
                'status' => false,
                'message' => 'book not found'
            ], 404);
        }

        if ($request->available) {
            $book->user = '';
        } else {
            $book->user = $request->user;
        }
        

        if ($book->save()) {
            return response()->json($book->load('category'));
        } else {
            return response()->json([
                'status' => false,
                'message' => 'can´t save book'
            ], 500);
        }
    }
}
