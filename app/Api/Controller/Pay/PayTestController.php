<?php declare(strict_types=1);
namespace App\Api\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
// use Zend\Diactoros\Response\XmlResponse;
use Illuminate\Http\Response;

class PayTestController implements RequestHandlerInterface {
	
	
	public function handle(ServerRequestInterface $request): ResponseInterface {
		
		$body = "<xml></xml>";
		
		if (true) {
			phpinfo();
			exit;
		}
		
		return response($body);
		
	}

	
}

