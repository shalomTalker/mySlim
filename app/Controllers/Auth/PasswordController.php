<?php  

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;


class PasswordController extends Controller 
{

	public function getChangePassword($request, $response)
	{
		return $this->view->render($response, 'auth/password/change.twig');
	}
	public function postChangePassword($request, $response)
	{
		$validation = $this->validator->validate($request, [
			'password_old' => v::noWhitespace()->notEmpty()->MatchesPassword($this->auth->user()->password),
			'password' => v::noWhitespace()->notEmpty(),
		]);
		
		if ($validation->failed())
		{
			$this->flash->addMessage('error', 'could not change your password with those details.');
			return $response->withRedirect($this->router->pathFor('auth.password.change'));
		}
		
		$this->auth->user()->setPassword($request->getParam('password'));

		$this->flash->addMessage('info', 'your password was changed.');
		return $response->withRedirect($this->router->pathFor('home'));
	}
}
 