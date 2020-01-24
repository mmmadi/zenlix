<?php

namespace zenlix\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Auth;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {



    if($e instanceof NotFoundHttpException)
    {
        if (Auth::check())
    {
        return redirect('404');
    }
    else {
        return redirect('dashboard');
        //response()->view('errors.guest.404', [], 404);
    }



    }


    else if ($e instanceof \Illuminate\Session\TokenMismatchException) {
            $request->session()->flash('alert-danger', trans('handler.sessionExpired'));
            return back()->withInput();

        }
    else if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
    return redirect('dashboard');
    //response()->view('errors.404', array(), 404);
}

        return parent::render($request, $e);
    }
}
