<?php

namespace App\Http\Controllers;

use App\Manga;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;

class MangaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Manga::count() == 0) {
            return response('There are no mangas to show');
        }

        $query = (new Manga)->newQuery();

        if ($request->has('q')) {
            $query->search($request->input('q'));
        }

        if ($request->has('sort')) {
            $column = str_replace('-', '', $request->input('sort'));
            $direction = $request->input('sort') != $column ? 'desc' : 'asc';
            if (Schema::hasColumn('mangas', $column)) {
                $query->orderBy($column, $direction);
            }
        }

        if ($request->has('page')) {
            $currentPage = $request->input('page');
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            $perPage = $request->has('per_page') ? $request->input('per_page') : 5;
            $query = $query->paginate($perPage);
        } else {
            $query = $query->get();
        }

        return response($query);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Refreshes manga table
     * 
     * @return Illuminate\Http\Response
     */
    public function refresh()
    {

    }

}
