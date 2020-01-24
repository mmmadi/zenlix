<?php

namespace zenlix\Http\Controllers;

use Auth;
use Event;
use Illuminate\Http\Request;
use Storage;
use Validator;
use zenlix\Classes\Zen;
use zenlix\Events\MessageNotify;
use zenlix\Files;
use zenlix\Http\Controllers\Controller;
use zenlix\Messages;
use zenlix\TicketForms;
use zenlix\User;
use zenlix\UserTicketConf;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $user = Auth::user();

        $messages = Messages::where('to_user_id', $user->id)
            ->where('draft_flag', 'false')
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [

            'messages' => $messages,

        ];

        return view('user.message.list')->with($data);

    }

//showDataBody

//showNew
    /**
     * @param Request $request
     */
    public function showNew(Request $request)
    {

        $user = Auth::user();
        $receiveCounter = $request->counter;

        $messagesCount = Messages::where('to_user_id', $user->id)
            ->where('read_flag', 'true')
            ->count();

        if ($receiveCounter != $messagesCount) {
            $status = 'true';
        } else {
            $status = 'false';
        }

        $data = [
            [
                'status' => $status,
                'counter' => $messagesCount,
            ],
        ];
        return response()->json($data);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function indexUsers(Request $request)
    {

        $groups = Auth::user()->GroupUser();

        $groupsArr = [];
        foreach ($groups as $group) {
            # code...
            array_push($groupsArr, $group->id);
            //$groupsArr[$group->id] = $group->name;
        }

        $UserRes = User::where('name', 'LIKE', '%' . $request->q . '%')
            ->where(function ($query) use ($groupsArr) {
                return $query
                    ->whereHas('groups', function ($q) use ($groupsArr) {
                        $q->whereIn('id', $groupsArr);
                    })
                    ->orHas('groups', '=', 0);
            })

            ->get();
        $items = [];
        foreach ($UserRes as $user) {
            # code...
            array_push($items, ['id' => $user->id,
                'img' => Zen::showUserImgSmall($user->profile->user_img),
                'name' => $user->name,
                'position' => $user->profile->position,
                'value' => $user->id]);
        }

        $data = ['items' => $items];

        return response()->json($data);

    }

    public function indexSent()
    {
        //

        $user = Auth::user();

        $messages = Messages::where('from_user_id', $user->id)
            ->where('draft_flag', 'false')
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [

            'messages' => $messages,

        ];

        return view('user.message.listSent')->with($data);

    }

    public function indexDraft()
    {
        //

        $user = Auth::user();

        $messages = Messages::where('from_user_id', $user->id)
            ->where('draft_flag', 'true')
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [

            'messages' => $messages,

        ];

        return view('user.message.listSent')->with($data);

    }

    public function indexTrash()
    {
        //

        $user = Auth::user();

        $messages = Messages::where('to_user_id', $user->id)
            ->onlyTrashed()
            ->orWhere('from_user_id', $user->id)

            ->orderBy('created_at', 'desc')
            ->onlyTrashed()
            ->get();

        $data = [

            'messages' => $messages,

        ];

        return view('user.message.list')->with($data);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        $files = Files::where('status', 'tmp')
            ->where('target_type', 'message')
            ->where('user_id', Auth::user()->id)
            ->get();

        $storage = Storage::disk('users');

        foreach ($files as $file) {
            # code...
            $fileName = $file->hash . '.' . $file->extension;
            $storage->delete(Auth::user()->id . '/' . $fileName);
            $file->delete();
        }

        return view('user.message.create');

    }

    /**
     * @param $hash
     * @return mixed
     */
    public function showReply($hash)
    {
        //

        $files = Files::where('status', 'tmp')
            ->where('target_type', 'message')
            ->where('user_id', Auth::user()->id)
            ->get();

        $storage = Storage::disk('users');

        foreach ($files as $file) {
            # code...
            $fileName = $file->hash . '.' . $file->extension;
            $storage->delete(Auth::user()->id . '/' . $fileName);
            $file->delete();
        }

        $user = Auth::user();

        $message = Messages::where('message_urlhash', $hash)
            ->withTrashed()
            ->where(function ($query) use ($user) {
                return $query
                    ->where('to_user_id', $user->id)
                    ->orWhere('from_user_id', $user->id)
                ;
            })
            ->withTrashed()
            ->firstOrFail();

        $message->text = '<br><hr><strong>' . $message->fromUser->name . ' ' . trans('handler.wrote') . ' :</strong><br>' . $message->text;

        $data = [
            'message' => $message,
        ];

        return view('user.message.reply')->with($data);

    }

    /**
     * @param Request $request
     */
    public function storeFiles(Request $request)
    {

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

        $file = $request->file('messagefile');
        $validator = Validator::make(array('messagefile' => $file), [
            'messagefile' => 'mimes:' . $fileTypes . '|max:' . $fileSize . '',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()->first(),
                'code' => 500,
            ]);

        } else {

            $count_tmpFiles = Files::whereUserId(Auth::user()->id)->whereStatus('tmp')->where('target_type', 'help')->count();
            if ($count_tmpFiles >= $fileCount) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('handler.maxFilesC') . $fileCount,
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
                file_get_contents($request->file('messagefile')->getRealPath()));

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
                'target_type' => 'message',
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
     * @param $id
     */
    public function destroyFiles($id)
    {

        $user = Auth::user();

        $file = Files::where('hash', '=', $id)->where('user_id', $user->id)->firstOrFail();

        $storage = Storage::disk('users');

        $fileAuthor = $file->user_id;
        $fileName = $file->hash . '.' . $file->extension;

        $storage->delete($fileAuthor . '/' . $fileName);

        $file->delete();

    }

    /**
     * @param $id
     */
    public function showFiles($id)
    {

        $file = Files::whereHash($id)->firstOrFail();
        $imgPath = storage_path('users/' . $file->user_id . '/' . $file->hash . '.' . $file->extension);
        $headers = array('Content-Type' => $file->mime);
        return response()->download($imgPath, $file->name, $headers);

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
        //dd($request->all());

        if ($request->action == "save") {
            $validator = Validator::make($request->all(), [
                //'to'=>'required',
                'subject' => 'required|min:5',
                'text' => 'required|min:10',
            ]);

            if ($validator->fails()) {

                $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
                return back()->withErrors($validator)->withInput();

            } else {
                $message = Messages::create([

                    'subject' => $request->subject,
                    'text' => $request->text,
                    'draft_flag' => 'true',
//'read_flag',
                    //'star_flag',
                    'from_user_id' => Auth::user()->id,
                    'to_user_id' => $request->to,
                    'message_urlhash' => str_random(20),

                ]);

                $DBfiles = Files::where('status', 'tmp')->where('target_type', 'message')->where('user_id', Auth::user()->id)->get();

                foreach ($DBfiles as $file) {
                    $file->update(['status' => 'success', 'target_id' => $message->id]);
                }

                $request->session()->flash('alert-success', trans('handler.messagesSaved'));
                return redirect('/message/inbox');

            }
        } else {
            $validator = Validator::make($request->all(), [
                'to' => 'required',
                'subject' => 'required|min:5',
                'text' => 'required|min:10',
            ]);

            if ($validator->fails()) {

                $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
                return back()->withErrors($validator)->withInput();

            } else {

                $message = Messages::create([

                    'subject' => $request->subject,
                    'text' => $request->text,
                    'draft_flag' => 'false',
//'read_flag',
                    //'star_flag',
                    'from_user_id' => Auth::user()->id,
                    'to_user_id' => $request->to,
                    'message_urlhash' => str_random(20),

                ]);

                Event::fire(new MessageNotify($message->id));

                $DBfiles = Files::where('status', 'tmp')->where('target_type', 'message')->where('user_id', Auth::user()->id)->get();

                foreach ($DBfiles as $file) {
                    $file->update(['status' => 'success', 'target_id' => $message->id]);
                }

                $request->session()->flash('alert-success', trans('handler.messageSended'));
                return redirect('/message/inbox');

            }
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($hash)
    {
        //

        $user = Auth::user();

        $message = Messages::where('message_urlhash', $hash)
            ->withTrashed()
            ->where(function ($query) use ($user) {
                return $query
                    ->where('to_user_id', $user->id)
                    ->orWhere('from_user_id', $user->id)
                ;
            })
            ->withTrashed()
            ->firstOrFail();

//set as read

        if ($message->to_user_id == $user->id) {
            $message->update([
                'read_flag' => 'false',
            ]);
        }

        $data = [
            'message' => $message,
        ];

        return view('user.message.page')->with($data);

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
    public function destroy($hash)
    {
        //

        $user = Auth::user();

        $message = Messages::where('message_urlhash', $hash)
            ->withTrashed()
            ->where(function ($query) use ($user) {
                return $query
                    ->where('to_user_id', $user->id)
                    ->orWhere('from_user_id', $user->id)
                ;
            })
            ->withTrashed()
            ->firstOrFail();
        if ($message->deleted_at == null) {
            $message->delete();
        } else {

            foreach ($message->files as $file) {
                # code...
                $fileName = $file->hash . '.' . $file->extension;
                $storage->delete($file->user_id . '/' . $fileName);
                $file->delete();
            }

            $message->forceDelete();

        }

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function destroyMany(Request $request)
    {
        //

        $user = Auth::user();

        $elements = $request->elements;

        dd($elements);

        foreach ($elements as $hash) {
            # code...

            $message = Messages::where('message_urlhash', $hash)
                ->withTrashed()
                ->where(function ($query) use ($user) {
                    return $query
                        ->where('to_user_id', $user->id)
                        ->orWhere('from_user_id', $user->id)
                    ;
                })
                ->withTrashed()
                ->firstOrFail();
            if ($message->deleted_at == null) {
                $message->delete();
            } else {

                foreach ($message->files as $file) {
                    # code...
                    $fileName = $file->hash . '.' . $file->extension;
                    $storage->delete($file->user_id . '/' . $fileName);
                    $file->delete();
                }

                $message->forceDelete();

            }

        }

    }

}
