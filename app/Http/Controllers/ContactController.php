<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Imports\ContactsImport;
use Auth;
use Session;
use Validator;
use Input;
use Redirect;
use Excel;
use Http;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::where('user_id', Auth::id())->paginate(10);

        return view('contact.index', [
            'contacts' => $contacts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contact.form', [
            'isNew' => true
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'first_name'       => 'required',
            'email'      => 'required|email|unique:contacts',
            'phone' => 'required|numeric'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('contact/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $contact = new Contact;
            $contact->first_name       = Input::get('first_name');
            $contact->email      = Input::get('email');
            $contact->phone = Input::get('phone');
            $contact->user_id = Auth::id();
            $contact->save();
            // syncing to syncToKlaviyo
            $contact->syncToKlaviyo();
            // redirect
            Session::flash('message', 'Successfully added contact and synced info to Klaviyo!');
            return redirect()->route('contact.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contact = Contact::where('id', $id)->first();

        return view('contact.form', [
            'contact' => $contact,
            'isNew' => false
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'first_name'       => 'required',
            'email'      => 'required|unique:contacts,email,'.$id,
            'phone' => 'required|numeric'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('contact/'. $id . '/edit')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $contact = Contact::find($id);
            $contact->first_name       = Input::get('first_name');
            $contact->email      = Input::get('email');
            $contact->phone = Input::get('phone');
            $contact->user_id = Auth::id();
            $contact->save();

            // syncing to syncToKlaviyo
            $contact->syncToKlaviyo();
            // redirect
            Session::flash('message', 'Successfully updated contact and synced info to Klaviyo!');
            return redirect()->route('contact.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete
        $contact = Contact::find($id);
        $contact->delete();

        // redirect
        Session::flash('message', 'Successfully deleted the contact!');
        return redirect()->route('contact.index');
    }

    /**
     * import contacts from csv
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function fileImport(Request $request) 
    {
        if(!$request->file('file')) {
            Session::flash('message', 'No File was Selected!');
            return redirect()->route('contact.index');
        }

        Excel::import(new ContactsImport, $request->file('file')->store('temp'));

        Session::flash('message', 'Successfully imported contact and synced info to Klaviyo!');
        return redirect()->route('contact.index');
    }

    /**
     * track button click on klaviyo
     * @return [type] [description]
     */
    public function trackToKlaviyo() {
        $response = Http::post("https://a.klaviyo.com/api/track", [
            "token" => "pk_63803bf1a962b088f649b4329dd1bf3bcb",
            "event" => "Track Button was pressed clicked",
            "customer_properties" => [
                 "\$email" => "abraham.lincoln@klaviyo.com"
            ],
            "time"  => (string) time(),
        ]);

        Session::flash('message', 'Button Click was tracked!');
        return redirect()->route('contact.index');
    }
}
