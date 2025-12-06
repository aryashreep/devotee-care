@extends('layouts.app')

@section('title', 'View Language')

@section('content')
<h1 class="text-2xl font-bold mb-4">View Language</h1>
<div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
        <p class="text-gray-700 text-base">{{ $language->name }}</p>
    </div>
    <div class="flex items-center justify-between">
        <a href="{{ route('languages.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
            Back to List
        </a>
    </div>
</div>
@endsection
