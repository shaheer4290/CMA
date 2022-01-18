<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Contact extends Model
{
     protected $table = 'contacts';

    use HasFactory;

     protected $fillable = [
        'first_name',
        'email',
        'phone',
        'user_id'
    ];

    /**
     * sync contacts to Klaviyo
     * @param  [type] $contact [description]
     * @return [type]          [description]
     */
    public function syncToKlaviyo() {
        $response = Http::post('https://a.klaviyo.com/api/identify', [
            'token' => 'pk_63803bf1a962b088f649b4329dd1bf3bcb',
            'properties' => [
                "\$email" => $this->email,
                "\$first_name" => $this->first_name,
                "\$phone_number" => $this->phone,
            ]
        ]);
    }
}
