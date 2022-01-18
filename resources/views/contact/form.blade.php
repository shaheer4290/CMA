@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2">
        </div>
        <div class="col-md-4">
            <h3> {{ $isNew ? 'Create' : 'Update' }} Contact</h3>
        </div>
    </div>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div style="color:red;">
                {{ Html::ul($errors->all()) }}
            </div>
            @if(!$isNew)
                {{ Form::model($contact, ['route' => ['contact.update', $contact->id], 'method' => 'patch']) }}
            @else
                {{ Form::open(['route' => 'contact.store']) }}
            @endif
                <div class="form-group">
                    {{ Form::label('first_name', 'First Name') }}
                    {{ Form::text('first_name', Input::old('first_name'), ['class' => 'form-control']) }}
                </div>

                <div class="form-group">
                    {{ Form::label('email', 'Email') }}
                    {{ Form::text('email', Input::old('email'), ['class' => 'form-control']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('phone', 'Phone') }}
                    {{ Form::text('phone', Input::old('phone'), ['class' => 'form-control']) }}
                </div>
                <br>
                {{ Form::submit('Save', ['name' => 'submit', 'class' => 'btn btn-primary']) }}

                <a href="{{ route('contact.index') }}" class="btn btn-secondary">Cancel</a>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection