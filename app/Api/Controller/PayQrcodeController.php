<?php declare(strict_types = 1);
namespace App\Api\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Discuz\Api\JsonApiResponse;
use xubin\openpay\OpenPay;

class PayQrcodeController implements RequestHandlerInterface {
	
	public function handle(ServerRequestInterface $request): ResponseInterface {
		
		$params = $request->getQueryParams();
		
		if (empty($params['token'])) {
			$op = new OpenPay();
// 			$imgPath = $op->payQrcode($params['product_type'], $params['scene'], $params['product_type'], $params['product_id']);
			$imgPath = $op->payQrcode(1, 1, 1, 1, false);
			$img_path = base64_encode($imgPath);//base64编码一下
			$img_path = urlencode($img_path); // url 编码一下
			
			return new JsonApiResponse([
					'code' => '0',
					'msg' => 'succ.',
					'data'=> [
							'f' => $img_path,
					]
			]);
		}
		
	}

	
}


