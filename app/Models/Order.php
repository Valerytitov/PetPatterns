<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;
	
	protected $fillable = [
		
		'vfile_id',
		'payment_url',
		'vit_original',
		'vit_prepared',
		'result_files',
		'status',
		'email',
		'sum',
		'pattern_details',
	];
	
	protected $casts = [
		'pattern_details' => 'array',
	];
	
	public function vfile() {
		return $this->belongsTo(Vfile::class);
	}
	
}
