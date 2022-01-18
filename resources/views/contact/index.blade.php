@extends('layouts.app')

@section('content')
<div class="container">
    @if (Session::has('message'))
        <div class="alert alert-info">
            {{ Session::get('message') }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-2">
        </div>
        <div class="col-md-4">
            <a class="btn btn-primary"  href="{{ route('contact.create') }}"> Add</a>
            <a class="btn btn-primary"  href="{{ route('track') }}"> Track to klaviyo</a>
        </div>
        <div class="col-md-4">
            <form action="{{ route('contact.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;"> -->
                    <!-- <div class="custom-file text-left"> -->
                    <div>
                        <button type="submit" class="btn btn-primary">Import data</button>
                        <input type="file" name="file" class="custom-file-input" id="customFile">
                    </div>
                    <!-- </div> -->
                <!-- </div> -->
                
            </form>
        </div>
    </div>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <table class="table table-striped">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">First Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contacts as $contact)
                    <tr>
                        <td>{{ $contact->first_name }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ $contact->phone }}</td>
                        <td> 
                            <a href="{{ route('contact.edit', ['contact' => $contact->id]) }}" class="btn btn-primary pull-left"> Edit</a>
                            {{ Form::open(array('url' => 'contact/' . $contact->id, 'class' => 'pull-left')) }}
                                {{ Form::hidden('_method', 'DELETE') }}
                                {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                            {{ Form::close() }}
                            
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $contacts->links() }}
        </div>
    </div>
</div>


@endsection
