<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Vfile;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Prop;

class ShopController extends Controller {

    public function index() {
		
		$products = Vfile::orderBy('id', 'desc')->get();
		
		$return = compact('products');
		
        return view('front/shop/list', $return);
		
    }
	
	public function order(Request $request) {
		
		$rec = Vfile::find($request->id);
		if (!$rec) {
			return response()->json(['status' => 'error']);
		}
		
		if (!file_exists($rec->vit_file)) {
			return false;
		}
		
		$vit_source = $vit_replace = file_get_contents($rec->vit_file);
		$vit_prepared = storage_path('/app/vfiles_temp/');
		$vit_prepared .= Str::random(20).'.vit';
		
		/* Замена данных */
		$props = [];
		if (is_array($request->prop)) {
			foreach ($request->prop as $prop_id => $prop_value) {
			
				$this_prop = Prop::find($prop_id);
				if (!$this_prop) {
					continue;
				}
				
				$props[] = [
				
					'key' => $this_prop->prop_key,
					'value' => $prop_value,
				
				];
			
			}
		}
		
		foreach ($props as $p) {
		
			$pattern = '#name="'.$p['key'].'" value="(.*)"#Ui';
			$replace = 'name="'.$p['key'].'" value="'.$p['value'].'"';
			$vit_replace = preg_replace($pattern, $replace, $vit_replace);
		
		}
		
		/* */
		$vit_prepared_handle = fopen($vit_prepared, 'w');
		fwrite($vit_prepared_handle, $vit_replace);
		fclose($vit_prepared_handle);
		
		/* */
		$data = [
		
			'vfile_id' => $request->id,
			'vit_original' => $rec->vit_file,
			'vit_prepared' => $vit_prepared,
		
		];
		
		$order = new Order();
		$order->fill($data);
		$order->save();
		
		/* PDF */
		//$gen_name = 'order_'.$order->id;
		//$gen_destination = storage_path('app/vfiles_temp/');
		//VFile::generatePDF($gen_name, $gen_destination, $vit_prepared, $rec->val_file);

		/* Платёж */
		$payment_url = Payment::createPayment($order->id, $rec->title, $rec->price);
		
		$order->payment_url = $payment_url;
		$order->save();
		
		return response()->json(['status' => 'ok', 'payment' => $payment_url]);
		
	}
	
}
