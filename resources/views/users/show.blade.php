@extends('layouts.app')

@section('title', 'Show User')

@section('content')
<h1 class="text-2xl font-bold mb-4">Show User</h1>
<div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
        <p class="text-gray-700">{{ $user->name }}</p>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Mobile Number</label>
        <p class="text-gray-700">{{ $user->mobile_number }}</p>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
        <p class="text-gray-700">{{ $user->email }}</p>
    </div>
    <a href="{{ route('users.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
        Back to Users
    </a>
</div>
@endsection
