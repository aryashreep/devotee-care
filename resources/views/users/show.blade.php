@extends('layouts.app')

@section('title', 'View User')

@section('content')
<div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <div class="flex items-center mb-4">
        <img src="{{ asset('storage/' . $user->photo) }}" alt="User Photo" class="w-24 h-24 rounded-full mr-4">
        <div>
            <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
        </div>
        <div class="ml-auto">
            <button id="edit-button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</button>
            <button id="save-button" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded hidden">Save</button>
        </div>
    </div>

    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="#" class="tab-link border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="my-details">
                My Details
            </a>
            <a href="#" class="tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="contact-details">
                Contact Details
            </a>
            <a href="#" class="tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="additional-details">
                Additional Details
            </a>
            <a href="#" class="tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="spiritual-details">
                Spiritual Details
            </a>
        </nav>
    </div>

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="py-4">
            @include('users.partials._my_details')
            @include('users.partials._contact_details')
            @include('users.partials._additional_details')
            @include('users.partials._spiritual_details')
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.tab-link');
        const tabContents = document.querySelectorAll('.tab-content');
        const editButton = document.getElementById('edit-button');
        const saveButton = document.getElementById('save-button');
        const formInputs = document.querySelectorAll('input, select');

        tabs.forEach(tab => {
            tab.addEventListener('click', function (e) {
                e.preventDefault();
                tabs.forEach(t => {
                    t.classList.remove('border-blue-500', 'text-blue-600');
                    t.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                });
                this.classList.add('border-blue-500', 'text-blue-600');
                this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');

                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });
                document.getElementById(this.dataset.tab).classList.remove('hidden');
            });
        });

        editButton.addEventListener('click', function () {
            formInputs.forEach(input => {
                input.disabled = false;
            });
            this.classList.add('hidden');
            saveButton.classList.remove('hidden');
        });
    });
</script>
@endpush
