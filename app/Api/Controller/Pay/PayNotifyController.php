<?php 
namespace App\Api\Controller\Pay;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\XmlResponse;

class PayNotifyController implements RequestHandlerInterface {
	
	
	public function handle(ServerRequestInterface $request): ResponseInterface {
		
		$body = "<xml></xml>";
		
		return new XmlResponse($body);
		
	}

	
}

