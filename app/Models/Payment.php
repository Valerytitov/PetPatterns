<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
	
	public static function createPayment($order_id, $name, $price) {

		$url = 'https://beriisheu.payform.ru';
		
		$post = [
			
			'order_id' => $order_id,
			'do' => 'link',
			'products' => [
				[
				
					'name' => $name,
					'price' => $price,
					'quantity' => 1,
					'sku' => 1,
				
				],
			],
		
		];
		
		$post = http_build_query($post);
		
		$url = $url.'?'.$post;

		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$exec = curl_exec($curl);
		curl_close($curl);
		
		if (!stripos($exec, 'payform')) {
			return false;
		}
		
		return $exec;
		
	}
	
}
