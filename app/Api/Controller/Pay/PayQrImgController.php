<?php declare(strict_types = 1);
namespace App\Api\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Discuz\Http\FileResponse;
use Symfony\Component\Process\ExecutableFinder;


class PayQrImgController implements RequestHandlerInterface {
	
	private $imgPath = '';
	
	public function handle(ServerRequestInterface $request): ResponseInterface {
		
		$params = $request->getQueryParams();
		$imgPath = base64_decode(urldecode($params['f']));
		
		$this->imgPath = $imgPath;
		
		return new FileResponse($imgPath);
		
	}
	
	/**
	 * 删除二维码文件
	 */
	public function __destruct() {
// 		unlink($this->imgPath);
	}

	
}


