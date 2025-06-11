<?php

namespace App\Helpers;

use App\Models\Prop;

class VFile {

	static function parseVIT($vitPath) {
		
		if (!file_exists($vitPath)) {
			return false;
		}
		
		$source = file_get_contents($vitPath);
		
		preg_match_all('#full_name="(.*)" name="(.*)" value="(.*)"#Ui', $source, $info);
		
		$data = [];
		$props = [];
		
		foreach ($info[1] as $key => $prop_name) {
			
			$data[] = [
			
				'title' => trim($prop_name),
				'key' => trim($info[2][$key]),
				'default' => trim($info[3][$key]),
			
			];
			
			$props[] = trim($prop_name);
			
		}
		
		$data = [
			
			'result' => 'ok',
			'data' => $data,
			'props' => $props,
		
		];
		
		return $data;
		
	}
	
	static function buildProps($data) {

		$data = json_decode($data);
		
		$props = [];
		
		foreach ($data->data as $prop) {

			$this_prop = Prop::getByKey($prop->key);
			if (!$this_prop) {
				continue;
			}
			
			$props[$this_prop->id] = [
			
				'id' => $this_prop->id,
				'key' => $prop->key,
				'label' => $this_prop->prop_title,
				'hint' => $this_prop->prop_hint,
				'default' => $prop->default,
			
			];
			
		}
		
		return $props;
		
	}

}

?>