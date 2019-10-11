<?php declare(strict_types=1);
namespace App\Api\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\XmlResponse;

class PayOrderController implements RequestHandlerInterface {
	
	
	public function handle(ServerRequestInterface $request): ResponseInterface {
		
		$body = "<xml></xml>";
		
		return new XmlResponse($body);
		
	}

	
}

