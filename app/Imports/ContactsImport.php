<?php

namespace App\Imports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Auth;

class ContactsImport implements ToModel, WithHeadingRow, WithUpserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $contact = new Contact([
            'first_name'     => $row['first_name'],
            'email'    => $row['email'],
            'phone' => $row['phone'],
            'user_id' => Auth::id()
        ]);

        $contact->syncToKlaviyo();

        return $contact;
    }

    public function uniqueBy()
    {
        return 'email';
    }
}
