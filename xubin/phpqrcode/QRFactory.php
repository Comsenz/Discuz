<?php
namespace xubin\phpqrcode;


/*
 * PHP QR Code encoder
 *
 * Exemplatory usage
 *
 * PHP QR Code is distributed under LGPL 3
 * Copyright (C) 2010 Dominik Dzienia <deltalab at poczta dot fm>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
    
class QRFactory {
	
	
	/**
	 * 生成二维码图片
	 * @param string $data 二维码的内容
	 * @param number $size 生成图片的矩阵点大小: 1-10
	 * @param string $level 容错级别。由低到高：L < M < Q < H
	 * @param boolean $show	是否将生成的最终图片以图片格式输出给浏览器
	 * @return string 返回最终图片的路径
	 */
	public static function createImg($data='', $size='', $level='', $show=true)
	{
		
		include dirname(__FILE__) . DIRECTORY_SEPARATOR . "qrlib.php";

		// set it to writable location, a place for temp generated PNG files
		$png_temp_dir = dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
		
		//ofcourse we need rights to create temp dir
		if (!file_exists($png_temp_dir)) {
			mkdir($png_temp_dir);
		}
			
		//processing input
		//remember to sanitize user input in real-life solution !!!
		$errorCorrectionLevel = 'L';
		if (isset ( $level ) && in_array ( $level, array ( 'L', 'M', 'Q', 'H') )) {
			$errorCorrectionLevel = $level;
		}
		
		$matrixPointSize = 4;
		if (isset($size)) {
			$matrixPointSize = min(max((int)$size, 1), 10);
		}
		
		if ($data) {
			// user data
			$filename = $png_temp_dir.'tst'.md5($data.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
			QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2, $show);
				
		} else {
			//default data
			$filename = $png_temp_dir.'tst.png';
			QRcode::png('Empty data have being given for Qrcode!', $filename, $errorCorrectionLevel, $matrixPointSize, 2, $show);
			
		}
		
		return $filename;
		
	}
	
	
	/**
	 * 生成一个带logo的二维码
	 * @param string $logoPath logo图片路径
	 * @param number $scale logo的缩放值：logo的新高度为二维码的1/$scale，大小为 >2 的数字，数字越小生成的带logo的二维码识别失败的概率越高。
	 * @param string $data	二维码内容
	 * @param string $level	容错级别
	 * @param number	$size 生成图片大小
	 * @param bool		$print	是否将生成的最终图片以图片格式输出给浏览器
	 * @return string 返回最终图片的路径
	 */
	public static function createImgWithLogo($logoPath='', $scale=5, $data='', $level='H', $size=3, $print=true)
	{
		$errorCorrectionLevel = $level;  //容错级别
		$matrixPointSize = $size;      //生成图片大小
		
		//生成二维码图片
		$QrImgPath = self::createImg($data, $matrixPointSize, $errorCorrectionLevel, false);;      //已经生成的原始二维码图
		
		$logoPath = $logoPath ? realpath($logoPath) : dirname(__FILE__).'/temp/logo.jpg'; //logo图片
		
		if (file_exists($logoPath)) {
			$QrSrc = imagecreatefromstring(file_get_contents($QrImgPath));  //目标图象连接资源。
			$QR_width = imagesx($QrSrc);      //二维码图片宽度
			$QR_height = imagesy($QrSrc);     //二维码图片高度
			
			$logoSrc = imagecreatefromstring(file_get_contents($logoPath));  //源图象连接资源。
			$logo_width = imagesx($logoSrc);    //logo图片宽度
			$logo_height = imagesy($logoSrc);   //logo图片高度
			
			// 组合后
			$logo_qr_width = $QR_width / $scale;   //logo的新宽度：二维码的1/$scale
			$logo_scale = $logo_width / $logo_qr_width;  //logo的宽度缩放比(本身宽度/组合后的宽度)
			$logo_qr_height = $logo_height / $logo_scale; //logo的新高度
			
			$logo_pos_x = ($QR_width - $logo_qr_width) / 2;  //组合之后logo左上角所在坐标点
			$logo_pos_y = ($QR_height - $logo_qr_height) / 2 ;
			
			//重新组合图片并调整大小
			// imagecopyresampled() 将一幅图像(源图象)中的一块正方形区域拷贝到另一个图像中
			imagecopyresampled($QrSrc, $logoSrc, $logo_pos_x, $logo_pos_y, 0, 0, $logo_qr_width,$logo_qr_height, $logo_width, $logo_height);
			imagedestroy($logoSrc);
			
			//输出图片到文件
			$QrImgWithLogoPath = $QrImgPath;
			
			imagepng($QrSrc, $QrImgWithLogoPath);
			imagedestroy($QrSrc);
			
			// 输出图片到浏览器
			header("Content-Type: image/png");
			readfile($QrImgWithLogoPath);
			
			return $QrImgWithLogoPath;
		} else {
			// 输出未添加logo的图片到浏览器
			header("Content-Type: image/png");
			readfile($QrImgPath);
			
			return $QrImgPath;
		}
		
	}
	
	
}

// QRFactory::createImg();
// echo QRFactory::createImgWithLogo('temp/logo.png', 2.3, 'https://pay.weixin.qq.com/static/applyment_guide/applyment_index.shtml', 'H', 6);

