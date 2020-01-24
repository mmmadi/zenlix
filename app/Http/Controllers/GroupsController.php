<?php

namespace zenlix\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Validator;
use zenlix\FeedComments;
use zenlix\GroupFeed;
use zenlix\Groups;
use zenlix\Http\Controllers\Controller;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

//ПОКАЗАТЬ ТОЛЬКО МОИ ГРУППЫ!

        $user = Auth::user();

        $groups = $user->groups()->wherePivot('priviliges', 'user')->orderBy('id', 'DESC')->paginate(5);

        //$groups = Groups::orderBy('id', 'DESC')->paginate(5);
        $groups->setPath('groups');

        $data = ['groups' => $groups];

        return view('user.group.list')->with($data);
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

//storePost
    public function storePost(Request $request, $id)
    {
        //
        $user = Auth::user();
        $group = Groups::where('group_urlhash', $id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'text' => 'required|min:5',
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

            ($request->mark == 'true') ? $mark = 'true' : $mark = 'false';
            ($request->comment_status == 'true') ? $comments = 'true' : $comments = 'false';

            GroupFeed::create([
                'text' => $request->text,
                'target' => 'group',
                'comments_flag' => $comments,
                'author_id' => $user->id,
                'group_id' => $group->id,
                'mark' => $mark,
                'feed_urlhash' => str_random(20),

            ]);

            return back();

        }

    }

//showPost
    public function showPost(Request $request, $id, $postID)
    {

        $group = Groups::where('group_urlhash', $id)->firstOrFail();
        $post = GroupFeed::where('feed_urlhash', $postID)
            ->where('group_id', $group->id)
            ->firstOrFail();

        $data = [
            'feed' => $post,
            'group' => $group,
        ];

        return view('user.group.post')->with($data);
    }

    public function editPost(Request $request, $id, $postID)
    {
        return $postID;
    }

    public function updatePost(Request $request, $id, $postID)
    {
        return $postID;
    }

    public function destroyPost(Request $request, $id, $postID)
    {

        $user = Auth::user();
        $group = Groups::where('group_urlhash', $id)->firstOrFail();
        $post = GroupFeed::where('feed_urlhash', $postID)
            ->where('group_id', $group->id)
            ->firstOrFail();

        if (($user->id == $post->author->id) || ($user->roles->role == 'admin') || ($user->GroupAdminSet($group->id)->count() != 0)) {
            $post->comments()->delete();
            $post->delete();
        }
    }

//storeComment
    public function storeComment(Request $request, $id)
    {

        $user = Auth::user();
        $s = 'text' . $id;

        $request->$s = clean($request->$s);

        $FeedReq = array_map('trim', $request->all());

        $validator = Validator::make([
            $s => clean($request->$s),
        ],
            [
                $s => 'required|min:2',
            ]);

        if ($validator->fails()) {

            return back()->withErrors($validator)->withInput();
        } else {

            $FeedID = GroupFeed::where('feed_urlhash', $id)->firstOrFail();

            $post_urlhash = str_random(40);

            FeedComments::create([
                'text' => $request->$s,
                'feed_id' => $FeedID->id,
                'author_id' => $user->id,
                'comment_urlhash' => $post_urlhash,
            ]);

            return back();

        }

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

        $group = Groups::where('group_urlhash', $id)->firstOrFail();

        $feeds = GroupFeed::where('group_id', $group->id)
            ->where('target', 'group')
            ->orderBy('id', 'desc')->paginate(10);
        //$feeds->setPath('group/');

        $data = [
            'group' => $group,
            'feeds' => $feeds,
        ];

        return view('user.group.page')->with($data);
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

        $group = Groups::where('group_urlhash', $id)->firstOrFail();
        $user = Auth::user();
        if ($user->GroupAdminSet($group->id)->count() != 0) {
            $data = [
                'group' => $group,
            ];

            return view('user.group.edit')->with($data);

        }

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

        $group = Groups::where('group_urlhash', $id)->firstOrFail();

        $user = Auth::user();
        if ($user->GroupAdminSet($group->id)->count() != 0) {

            $group->update([

                'name' => $request->name,
                'description' => $request->description,
                'description_full' => $request->description_full,
                'slogan' => $request->slogan,
                'address' => $request->address,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,

            ]);

            $request->session()->flash('alert-success', trans('handler.groupOkSave'));
        }
        return back();

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
