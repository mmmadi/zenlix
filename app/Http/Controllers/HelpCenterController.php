<?php

namespace zenlix\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Storage;
use Transliteration;
use Validator;
use zenlix\Files;
use zenlix\Help;
use zenlix\HelpAccess;
use zenlix\HelpCategory;
use zenlix\Http\Controllers\Controller;
use zenlix\TicketForms;
use zenlix\UserTicketConf;

class HelpCenterController extends Controller
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
        $myGroups = [];
        foreach ($user->groups as $value) {
            # code...
            array_push($myGroups, $value->id);
        }

        if ($user->role == 'admin'){
            $HelpCategory = HelpCategory::orderBy('sort_id', 'asc')->get();
        } else {
            $HelpCategory = HelpCategory::whereIn('group_id', $myGroups)->get();
        }

        if ($user->role == 'admin') {
            $help = Help::orderBy('updated_at', 'DESC')->paginate(5);
        } else {

            $helps = HelpAccess::whereIn('group_id', $myGroups)->get();

            $m = [];

            foreach ($helps as $value) {
                # code...

//dd($value->help[0]);

                array_push($m, $value->help[0]->id);
            }

            $help = Help::whereIn('id', $m)
                ->orWhere('access_all', 'true')

                ->orderBy('updated_at', 'DESC')->paginate(5);

//$help=$helps->help;

        }

        $help->setPath('help');

        $data = [

            'categories' => $HelpCategory,
            'helps' => $help,

        ];

        return view('user.help.list')->with($data);
    }

//createCategory

/*updateCategory
destroyCategory
editCategoryName
 */

    public function editCategoryName($id)
    {
        $cat = HelpCategory::findOrFail($id);

        $data = [
            'category' => $cat,
        ];
        return view('user.help.categoryEdit')->with($data);
    }

    public function updateCategory(Request $request, $id)
    {
        //

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

            $cat = HelpCategory::findOrFail($id);

            $cat->update([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
            ]);

            $request->session()->flash('alert-success', trans('handler.catSaved'));
            return redirect('/help/edit/category');

        }
    }

    public function destroyCategory($id)
    {
        //

        $cat = HelpCategory::findOrFail($id);
        $cat->delete();
        HelpCategory::where('parent_id', $id)->update(['parent_id' => '0']);

        Help::where('category_id', $id)->delete();
    }

    public function createCategory()
    {
        //

        return view('user.help.categoryCreate');
    }

//storeCategory
    public function storeCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

            $user = Auth::user();
            $myGroups = [];
            foreach ($user->groups as $value) {
                # code...
                array_push($myGroups, $value->id);
            }

            $group_id = end($myGroups);
            //$string_group_id = implode("",$group_id);

            HelpCategory::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'group_id' => $group_id
            ]);

            $request->session()->flash('alert-success', trans('handler.catChanged'));
            return redirect('/help/edit/category');
        }

        //return view('user.help.categoryCreate');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $files = Files::where('status', 'tmp')->where('target_type', 'help')->where('user_id', Auth::user()->id)->get();

        $storage = Storage::disk('users');

        foreach ($files as $file) {
            # code...
            $fileName = $file->hash . '.' . $file->extension;
            $storage->delete(Auth::user()->id . '/' . $fileName);
            $file->delete();
        }

        //
        $groups = Auth::user()->groups;

        $groupsArr = [];
        foreach ($groups as $group) {
            # code...
            $groupsArr[$group->id] = $group->name;
        }

        $user = Auth::user();
        $myGroups = [];
        foreach ($user->groups as $value) {
            # code...
            array_push($myGroups, $value->id);
        }

        $categories = HelpCategory::whereIn('group_id', $myGroups)->get();

        $categoriesArr = [];
        foreach ($categories as $category) {
            # code...
            $categoriesArr[$category->id] = $category->name;
        }

        $data = [

            'groups' => $groupsArr,
            'categories' => $categoriesArr,

        ];

        return view('user.help.create')->with($data);
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
        //dd($request->groups);
        $slug = str_slug(Transliteration::clean_filename($request->name), '-');

        $validator = Validator::make(array_merge($request->all(), [
            'slug' => $slug,
        ]), [
            'name' => 'required',
            'description' => 'required|min:5',
            'text' => 'required|min:10',
            'groups' => 'required_if:AcessAll,false',
            'slug' => 'unique:help,slug',
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

            $help = Help::create([
                'user_id' => Auth::user()->id,
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'text' => $request->text,
                'access_all' => $request->AcessAll,
                'slug' => $slug,
            ]);

//server_file
            $DBfiles = Files::where('status', 'tmp')->where('target_type', 'help')->where('user_id', Auth::user()->id)->get();

            foreach ($DBfiles as $file) {
                $file->update(['status' => 'success', 'target_id' => $help->id]);
            }

            if (!empty($request->groups)) {

                $help->groups()->attach($request->groups);
            }

            $request->session()->flash('alert-success', trans('handler.materialCreated'));
            return redirect('/help/' . $help->slug);

        }

    }

//storeFile
    public function storeFile(Request $request)
    {

        $user = Auth::user();
        //dd($request->file('fileToUpload'));
        $fileToUpload = $request->file('fileToUpload');

        $validator = Validator::make(array('fileToUpload' => $fileToUpload), [
            'fileToUpload' => 'image|max:2048',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()->first(),
                'code' => 500,
            ]);

        } else {

            $extension = $fileToUpload->getClientOriginalExtension();
            $mime = $fileToUpload->getClientMimeType();
            $originalName = $fileToUpload->getClientOriginalName();

            $fileHash = str_random(30);
            $storage = Storage::disk('users');
            $file_name = $fileHash . '.' . strtolower($extension);

            if (!$storage->exists($user->id)) {
                $storage->makeDirectory($user->id);
            }

            if (!$storage->exists($user->id)) {
                $storage->makeDirectory($user->id);
            }

            $storage->put($user->id . '/' . $file_name,
                file_get_contents($request->file('fileToUpload')->getRealPath()));

            Files::create([
                'user_id' => $user->id,
                'target_id' => null,
                'target_type' => 'helpImage',
                'name' => $originalName,
                'hash' => $fileHash,
                'mime' => $mime,
                'extension' => strtolower($extension),
                'status' => 'success',
                'image' => 'true',

            ]);

            $url = url('/files/view/') . '/' . $fileHash . '.' . strtolower($extension);
            $res = [
                'success' => true,
                'file' => $url,
                'message' => 'uploadOk',
            ];

        }

        return response()->json($res);

    }

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

        $file = $request->file('helpfile');
        $validator = Validator::make(array('helpfile' => $file), [
            'helpfile' => 'mimes:' . $fileTypes . '|max:' . $fileSize . '',
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
                file_get_contents($request->file('helpfile')->getRealPath()));

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
                'target_type' => 'help',
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

    public function showFiles($id)
    {

        $file = Files::whereHash($id)->firstOrFail();
        $imgPath = storage_path('users/' . $file->user_id . '/' . $file->hash . '.' . $file->extension);
        $headers = array('Content-Type' => $file->mime);
        return response()->download($imgPath, $file->name, $headers);

    }

//showFind
    public function showFind(Request $request)
    {

//dd($request->text);

        $s = $request->text;
        $user = Auth::user();
        $myGroups = [];
        foreach ($user->groups as $value) {
            # code...
            array_push($myGroups, $value->id);
        }

        $HelpCategory = HelpCategory::orderBy('sort_id', 'asc')->get();

        if ($user->role == 'admin') {
            $help = Help::where(function ($query) use ($s) {
                return $query
                    ->where('name', 'LIKE', '%' . $s . '%')
                    ->orWhere('description', 'LIKE', '%' . $s . '%');
            })
                ->orderBy('updated_at', 'DESC')->paginate(5);
        } else {

            $helps = HelpAccess::whereIn('group_id', $myGroups)->get();

            $m = [];

            foreach ($helps as $value) {
                # code...

//dd($value->help[0]);

                array_push($m, $value->help[0]->id);
            }

            $help = Help::where(function ($query) use ($s) {
                return $query
                    ->where('name', 'LIKE', '%' . $s . '%')
                    ->orWhere('description', 'LIKE', '%' . $s . '%');
            })

                ->where(function ($query) use ($m) {
                    return $query
                        ->whereIn('id', $m)
                        ->orWhere('access_all', 'true')
                    ;})

                ->orderBy('updated_at', 'DESC')->paginate(5);

//$help=$helps->help;

        }

        $help->setPath('help');

        $data = [

            'categories' => $HelpCategory,
            'helps' => $help,

        ];

        return view('user.help.list')->with($data);

    }

//showCat
    public function showCat($id)
    {

        $cat = HelpCategory::findOrFail($id);
        $user = Auth::user();
        $myGroups = [];
        foreach ($user->groups as $value) {
            # code...
            array_push($myGroups, $value->id);
        }

        $HelpCategory = HelpCategory::orderBy('sort_id', 'asc')->get();

        if ($user->role == 'admin') {
            $help = Help::where('category_id', $id)->orderBy('updated_at', 'DESC')->paginate(5);
        } else {

            $helps = HelpAccess::whereIn('group_id', $myGroups)->get();

            $m = [];

            foreach ($helps as $value) {
                # code...

//dd($value->help[0]);

                array_push($m, $value->help[0]->id);
            }

            $help = Help::where(function ($query) use ($m) {
                return $query
                    ->whereIn('id', $m)
                    ->orWhere('access_all', 'true');
            })

                ->where('category_id', $id)
                ->orderBy('updated_at', 'DESC')->paginate(5);

//$help=$helps->help;

        }

        //$help->setPath('cat/'.$id);

        $data = [

            'categories' => $HelpCategory,
            'helps' => $help,
            'cat' => $cat,

        ];

        return view('user.help.listCategory')->with($data);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        //
        $help = Help::where('slug', $slug)->firstOrFail();

        $data = [

            'help' => $help,

        ];

        return view('user.help.page')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

//editCategory
    public function editCategory()
    {
        $user = Auth::user();
        $myGroups = [];
        foreach ($user->groups as $value) {
            # code...
            array_push($myGroups, $value->id);
        }

        $HelpCategory = HelpCategory::whereIn('group_id', $myGroups)->get();

        $data = [

            'categories' => $HelpCategory,

        ];

        return view('user.help.category')->with($data);

    }

    public function edit($slug)
    {
        //

        $help = Help::where('slug', $slug)->firstOrFail();
        $groups = Auth::user()->groups;

        $groupsArr = [];
        foreach ($groups as $group) {
            # code...
            $groupsArr[$group->id] = $group->name;
        }
        $groupsSel = [];
        foreach ($help->groups as $g) {
            # code...
            array_push($groupsSel, $g->id);
        }

        $categories = HelpCategory::all();
        $categoriesArr = [];
        foreach ($categories as $category) {
            # code...
            $categoriesArr[$category->id] = $category->name;
        }

        $data = [

            'help' => $help,
            'groups' => $groupsArr,
            'groupsSel' => $groupsSel,
            'categories' => $categoriesArr,

        ];

        return view('user.help.edit')->with($data);

    }

//updateCategorySort

    public function updateCategorySort(Request $request)
    {
        //
        $orderlist = explode('&', $request->list);
        $n = 0;
        foreach ($orderlist as $order) {
            $a = explode("=", $order);
            $b = explode("[", $a['0']);
            $с = substr($b[1], 0, -1);
            $rest = substr($b[1], 0, -1);
            if ($a[1] == "null") {
                $a[1] = 0;
            }

            $HelpCategory = HelpCategory::findOrFail($rest);
            $HelpCategory->update([
                'parent_id' => $a[1],
                'sort_id' => $n,
            ]);

            $n++;
        }

        return $request->list;
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

        $help = Help::findOrFail($id);

        $slug = str_slug(Transliteration::clean_filename($request->name), '-');

        $validator = Validator::make(array_merge($request->all(), [
            'slug' => $slug,
        ]), [
            'name' => 'required',
            'description' => 'required|min:5',
            'text' => 'required|min:10',
            'groups' => 'required_if:AcessAll,false',
            'slug' => 'unique:help,slug,' . $help->id,
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

            $help->update([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'text' => $request->text,
                'access_all' => $request->AcessAll,
                'slug' => $slug,
            ]);

            $DBfiles = Files::where('status', 'tmp')->where('target_type', 'help')->where('user_id', Auth::user()->id)->get();

            foreach ($DBfiles as $file) {
                $file->update(['status' => 'success', 'target_id' => $help->id]);
            }
            $help->groups()->detach();
            if (!empty($request->groups)) {

                $help->groups()->attach($request->groups);
            }

            $request->session()->flash('alert-success', trans('handler.MaterialSaved'));
            return redirect('/help/' . $help->slug);

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
        //

        $help = Help::findOrFail($id);
        $help->delete();
/*        HelpCategory::where('parent_id', $id)->update(['parent_id' => '0']);

Help::where('category_id', $id)->delete();*/

    }
}
