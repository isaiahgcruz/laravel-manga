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


        if ($request->has('favorited')) {
            $query->where('favorited', $request->input('favorited'));
        }

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
            if ($request->has('limit')) {
                $query->limit($request->input('limit'));
            }
            $query = $query->get();
        }

        return response($query);
    }


    /**
     * Toggle favorited attribute of a manga
     * 
     * @param  App\Manga  $manga
     * 
     * @return Illuminate\Http\Response
     */
    public function toggleFavorite(Manga $manga)
    {
        $manga->update(['favorited' => !$manga->favorited]);
        return $manga;
    }

}
