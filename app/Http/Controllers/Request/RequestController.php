<?php

namespace App\Http\Controllers\Request;

use App\Http\Controllers\Controller;
use App\Models\Request\Request;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Http\Request as BaseRequest;

class RequestController extends Controller
{


    public function create() {
        return view('requests.create')->withShortcodes();
    }
    public function index() {
        $requests = Request::myCustomer()->get();
        return view('requests.index', [
            'requests' => $requests
        ])->withShortcodes();
    }
    public function show($id) {
        $request = Request::myCustomer()->where('id', $id)->first();
        if ($request) {
            return view('requests.show', [
                'request' => $request
            ])->withShortcodes();
        }
        return abort(404);
    }

    public function store(BaseRequest $request)
    {
        $fileConf = $this->saveFiles($request);

        $entity = new Request();
        $entity->request_file = $fileConf;
        $entity->request_comment = $request->input('comment');
        //dd($entity);
        $entity->save();

        return "Успех";
    }

    public function saveFiles(BaseRequest $request)
    {
        $confs = [];
        foreach ($request->file() as $file) {
            foreach ($file as $f) {
                $origName = $f->getClientOriginalName();
                $fileName = time() . '_' . $origName;
                /*dd([
                    'path' => storage_path('requests\\CustomerRequests'),
                    'file' => $fileName
                ]);*/
                $f->move(storage_path('app\\public\\requests\\CustomerRequests'), $fileName);

                array_push($confs, [
                    'download_link' => 'requests\\CustomerRequests\\' . $fileName,
                    'original_name' => $origName
                ]);
            }
        }
        return json_encode($confs);
    }
}
