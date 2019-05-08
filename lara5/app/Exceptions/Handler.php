<?php namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
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

$message = "It appears that an error occurred because that Google Adwords Account is already linked to Clickmonitor. This may be due to another Adwords service provider you use (and who is linked to your Adwords Account) is already a member of Clickmonitor. Please email us at Support@clickmonitor.co.uk so we can get this taken care of quickly.";

if($e->getMessage() == "[ManagedCustomerServiceError.ALREADY_MANAGED_BY_THIS_MANAGER @ operations[0]]"){echo "<body style='background:#bbb;'><div style='margin:100px auto;width:400px;'><img src='http://www.clickmonitor.co.uk/wp-content/uploads/2016/03/logo-1.png'><div style='padding:20px;background:#fff;border:2px solid #aaa;font-family:Arial;border-radius:6px;'>".$message."<br><br><a href='http://clickmonitor.co.uk' style='color:#428bca;'>Click Here to Continue to the Website</a></div></div></body>";exit();}

//var_dump($e->getMessage());
//var_dump($request->route()->getAction()["controller"]);

	if($request->route()->getAction()["controller"] == "App\Http\Controllers\Admin\AdminController@getDeleteUser"){
		return 404;
    }
	//	if ($e instanceof ForbiddenException) {
        //return redirect()->route('home')->withErrors(['error' => $e->getMessage()]);
   // }


		return parent::render($request, $e);
	}

}
