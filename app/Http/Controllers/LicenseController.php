<?php

namespace zenlix\Http\Controllers;

use Artisan;
use File;
use Illuminate\Http\Request;
use LocalizedCarbon;
use Session;
use Validator;
use zenlix\Http\Controllers\Controller;

class LicenseController extends Controller
{

    //const key='mLs5ANj372tmKnIc1PHWV98jyK1xnwpG';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $licenseInfo = Session::get('zenlix.license');

//$time=Carbon::createFromTimestamp($licenseInfo['time']);
        $time = LocalizedCarbon::createFromTimestamp($licenseInfo['time'])->formatLocalized('%d %f %Y, %H:%M');

        $data = [

            'owner' => $licenseInfo['owner'],
            'UID' => $licenseInfo['UID'],
            'msg' => $licenseInfo['msg'],
            'time' => $time,

        ];

        return view('admin.license.page')->with($data);

    }

    public function updateLicense()
    {

        Artisan::call('zenlix:license', []);
        return Artisan::output();

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

/*$data=[
'name'=>'root',
'pass'=>'true'
];

$data=json_encode($data);

$newEncrypter = new \Illuminate\Encryption\Encrypter( $key, config( 'app.cipher' ) );
$encrypted = $newEncrypter->encrypt( $data );
 */

//$decrypted = $newEncrypter->decrypt( $encrypted );

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showError()
    {
        //

        return view('license.error');
    }

//storeLicense
    public function storeLicense(Request $request)
    {
        //

        $validator = Validator::make($request->all(), [
            'licenseCode' => 'required|min:5',
        ]);

        if ($validator->fails()) {

            $request->session()->flash('alert-warning', trans('handler.msgFixErrors'));
            return back()->withErrors($validator)->withInput();

        } else {

            $bytes_written = File::put(storage_path('license'), $request->licenseCode);
            if ($bytes_written === false) {
                $request->session()->flash('alert-warning', trans('handler.cannotStoreLicense'));
                return back();

            }

            return redirect('/');

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showSuccess()
    {
        //

        return view('license.success');
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
}
