<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prop extends Model
{
    use HasFactory;
	
	public static function getByKey($key) {
		
		$rec = Self::where(['prop_key' => $key])->first();
		if (!$rec) {
			return false;
		}
		
		return $rec;
		
	}
	
}
