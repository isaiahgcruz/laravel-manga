<?php

namespace App\Http\Controllers;

use App\Manga;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

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
            $query->where('title', 'LIKE', '%'.$request->input('q').'%');
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

    /**
     * Update last read chapter of a manga
     *
     * @param  Manga $manga
     * @param  Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function lastReadChapter(Manga $manga, Request $request)
    {
        $manga->update(['last_read_chapter' => $request->input('last_read_chapter')]);
        return $manga;
    }

    public function downloadImages($chapterId, Request $request)
    {
        set_time_limit(0);
        $mangaName = $request->input('manga');
        $chapterNum = $request->input('chapter');
        $json = file_get_contents('http://www.mangaeden.com/api/chapter/'.$chapterId);
        $data = json_decode($json, true);
        $images = $data['images'];
        $imagePaths = [];
        foreach ($images as $image) {
            $imageFileName = $mangaName.'-'.$chapterNum.'-'.$image[0].'.'.explode('.', $image[1])[1];
            if (!Storage::exists($imageFileName)) {
                Storage::put($imageFileName,
                    file_get_contents('https://cdn.mangaeden.com/mangasimg/'.$image[1]));
            }
            array_push($imagePaths,$imageFileName);
        }
        return response($imagePaths);
    }

    public function downloadChapter($chapterId, Request $request)
    {
        $mangaName = $request->input('manga');
        $chapterNum = $request->input('chapter');
        $imageFilenames = $request->input('imageFilenames');
        $zip = new \ZipArchive;
        $publicDir = public_path().'/uploads';
        $zipFileName = $mangaName.'-'.$chapterNum.'.zip';
        $filename = $publicDir.'/'.$mangaName.'-'.$chapterNum.'.zip';
        if ($zip->open($filename, \ZipArchive::CREATE)===TRUE) {
            foreach($imageFilenames as $file) {
                $zip->addFile( storage_path() . '//app/' . $file);
            }
            $zip->close();
        }
        $headers = ['Content-Type' => 'application/octet-stream'];

        return response($zipFileName);
    }
}
