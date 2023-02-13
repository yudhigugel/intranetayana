<?php

namespace App\Exceptions;
use Throwable;
use Log;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (\Exception $e) {
            if ($e->getPrevious() instanceof \Illuminate\Session\TokenMismatchException) {
                return redirect('/login');
            };
        });
    }

    /**
    * Render an exception into an HTTP response.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Exception  $exception
    * @return \Illuminate\Http\Response
    */

    public function render($request, Throwable $exception)
    {
       if ($exception){
        // Create Log
        // Menempatkan file log di /storage/logs/exception.log
        $log = new Logger('/App/Exception/Handler');
        $log->pushHandler(new StreamHandler(storage_path().'/logs/exception.log', Logger::WARNING));
        $log->error(json_encode([
            'error' => $exception->getMessage(),
            'on_file' => $exception->getFile(),
            'on_line' => $exception->getLine(),
            'stacktrace' => $exception->getTraceAsString()
        ]));

        // Jika Dalam mode Debug jangan tampilkan halaman, tapi tampilkan error JSON
        if($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface){
            $code = $exception->getStatusCode();
            if($code == 404)
                return response()->view('pages.exception.404', [], $code);
            else if($code == 500)
                return response()->view('pages.exception.500', [], $code);
            else if($code == 403)
                return response()->view('pages.exception.403', [], $code);
        }
        else if ($exception instanceof \Illuminate\Session\TokenMismatchException && $request->getRequestUri() === '/auth/logout') {
            return redirect('/login');
        }
       }
       return parent::render($request, $exception);
    }
}
