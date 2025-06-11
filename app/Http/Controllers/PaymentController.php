<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Vfile;

class PaymentController extends Controller {
	
	public function test(Request $request) {
		
		//
		$url = 'http://31.128.38.42:37085/make';
		
		$post = [
		
			'name' => 'n',
			'destination' => '/var/www/html',
			'format' => 1,
			'page' => 4,
			'vit' => '~/merki.vit',
			'val' => '~/comb.val',
		
		];
		
		$url .= '/?'.http_build_query($post);
		
		$ci = curl_init();
		
		curl_setopt($ci, CURLOPT_URL, $url);
		curl_setopt($ci, CURLOPT_POST, 1);
		curl_setopt($ci, CURLOPT_POSTFIELDS, $post);
		
		$ce = curl_exec($ci);
		curl_close($ci);
		
		echo $ce;
		exit;
		
		//
		
		$url = 'https://beriisheu.payform.ru';
		
		$post = [
			
			'order_id' => 12345,
			'do' => 'link',
			'products' => [
				[
				
					'name' => 'Выкройка',
					'price' => 250,
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
		

		
		return $exec;
		
	}
	
	public function transaction(Request $request) {
		
		file_put_contents('/var/www/html/public/vf/test.txt', print_r($request->all(), true));
		
	}
	
	public function result_page($status, Request $request) {
		
		if ($status == 'done') {
			
			$order_id = $request->_payform_order_id;
			$order = Order::find($order_id);
			if (!$order) {
				abort(404);
			}
			
			$vfile = Vfile::find($order->vfile_id);
			if (!$vfile) {
				abort(404);
			}
			
			$order->status = 'paid';
			$order->save();
			
			$return = compact('order', 'vfile');
			
			return view('front/payment/success', $return);
			
		}
		
	}
	
}
