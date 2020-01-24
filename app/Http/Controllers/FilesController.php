<?php

namespace zenlix\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Image;
use Storage;
use Validator;
use zenlix\Files;
use zenlix\Http\Controllers\Controller;
use zenlix\TicketForms;
use zenlix\User;
use zenlix\UserTicketConf;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function storeCommentFile(Request $request, $id)
    {
        //

        $user = Auth::user();

        $UserTicketConf = UserTicketConf::firstOrCreate(['user_id' => $user->id]);

        if ($UserTicketConf->conf_params == "group") {
            $TicketForm = TicketForms::findOrFail($UserTicketConf->groupTicket->ticket_form_id);
        } else if ($UserTicketConf->conf_params == "user") {
            $TicketForm = TicketForms::findOrFail($UserTicketConf->ticket_form_id);
        }

        $fileTypes = $TicketForm->upload_files_types;
        $fileCount = $TicketForm->upload_files_count;
        $fileSize = $TicketForm->upload_files_size;

        $file = $request->file('commentfile');
        $validator = Validator::make(array('commentfile' => $file), [
            'commentfile' => 'mimes:' . $fileTypes . '|max:' . $fileSize . '',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()->first(),
                'code' => 500,
            ]);

        } else {

            $count_tmpFiles = Files::whereUserId(Auth::user()->id)->whereStatus('tmp')->where('target_type', 'ticketComment')->count();
            if ($count_tmpFiles >= $fileCount) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('handler.maxFilesCount') . $fileCount,
                    'code' => 500,
                ]);
            }

            $fileHash = str_random(30);

            $extension = $file->getClientOriginalExtension();
            $mime = $file->getClientMimeType();
            $originalName = $file->getClientOriginalName();

            $isimage = 'false';
            if (substr($mime, 0, 5) == 'image') {
                $isimage = 'true';
            }

            $storage = Storage::disk('users');
            $file_name = $fileHash . '.' . strtolower($extension);

            if (!$storage->exists($user->id)) {
                $storage->makeDirectory($user->id);
            }

            $storage->put($user->id . '/' . $file_name,
                file_get_contents($request->file('commentfile')->getRealPath()));

/*if ($isimage == 'true') {
$img=Image::make($imgPath)->fit(150, 150, function ($constraint) {
$constraint->aspectRatio();
$constraint->upsize();
});
$img->save('files/users/img/' . $string . '.' . $extension);
}*/

            Files::create([
                'user_id' => $user->id,
                'target_id' => null,
                'target_type' => 'ticketComment',
                'name' => $originalName,
                'hash' => $fileHash,
                'mime' => $mime,
                'extension' => strtolower($extension),
                'status' => 'tmp',
                'image' => $isimage,

            ]);

            return response()->json([
                'status' => 'success',
                'hash' => $fileHash,
                'message' => '',
                'code' => 500,
            ]);

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
        $file = Files::whereHash($id)->firstOrFail();
        $imgPath = storage_path('users/' . $file->user_id . '/' . $file->hash . '.' . $file->extension);
        $headers = array('Content-Type' => $file->mime);
        return response()->download($imgPath, $file->name, $headers);

    }

//FilesController@showSmall

    public function showFull($id)
    {
        //
        $fileHash = explode('.', $id);
        $fileHash = $fileHash[0];

        $file = Files::whereHash($fileHash)->firstOrFail();

        $imgPath = storage_path('users/' . $file->user_id . '/' . $file->hash . '.' . $file->extension);

        $img = Image::cache(function ($image) use ($imgPath) {
            //global $imgPath;
            $image->make($imgPath)->fit(1600, 1400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }, 10, true);

        return $img->response();
    }

    public function showSmall($id)
    {
        //
        $fileHash = explode('.', $id);
        $fileHash = $fileHash[0];

        $file = Files::whereHash($fileHash)->firstOrFail();

        $imgPath = storage_path('users/' . $file->user_id . '/' . $file->hash . '.' . $file->extension);

        $img = Image::cache(function ($image) use ($imgPath) {
            //global $imgPath;
            $image->make($imgPath)->fit(100, 65, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }, 10, true);

        return $img->response();
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
}
