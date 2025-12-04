@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <h1 class="text-2xl font-bold mb-4">My Profile</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-gray-700 font-bold">Name:</p>
            <p>{{ $user->name }}</p>
        </div>
        <div>
            <p class="text-gray-700 font-bold">Mobile Number:</p>
            <p>{{ $user->mobile_number }}</p>
        </div>
        <div>
            <p class="text-gray-700 font-bold">Email:</p>
            <p>{{ $user->email }}</p>
        </div>
        <div>
            <p class="text-gray-700 font-bold">Bhakti Sadan:</p>
            <p>{{ $user->bhaktiSadan->name ?? 'N/A' }}</p>
        </div>
    </div>
</div>
@endsection
