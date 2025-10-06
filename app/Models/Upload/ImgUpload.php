<?php

namespace App\Models\Upload;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class ImgUpload extends Model
{
    /** @use HasFactory<\Database\Factories\Upload\ImgUploadFactory> */
    use HasFactory;

    protected $fillable = [
        'image',
        'user_id'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
