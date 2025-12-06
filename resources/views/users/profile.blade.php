@extends('layouts.app')

@section('title', 'User Profile')

@section('content')
<div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center">
            <img class="w-16 h-16 rounded-full mr-4" src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF' }}" alt="User Photo">
            <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
        </div>
        <div>
            @if(auth()->user()->hasRole('Admin') && request()->route()->getName() !== 'my-profile.show')
                <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2">
                    Return to User Listing
                </a>
            @endif
            <button id="edit-button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Edit
            </button>
            <button id="save-button" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline hidden">
                Save
            </button>
            <button id="cancel-button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline hidden">
                Cancel
            </button>
        </div>
    </div>

    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="#" class="tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="my-details">
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

    <div id="view-mode">
        <div id="my-details-view-content" class="tab-content">
            @include('users.partials._my_details', ['user' => $user, 'view' => 'view'])
        </div>
        <div id="contact-details-view-content" class="tab-content hidden">
            @include('users.partials._contact_details', ['user' => $user, 'view' => 'view'])
        </div>
        <div id="additional-details-view-content" class="tab-content hidden">
            @include('users.partials._additional_details', ['user' => $user, 'view' => 'view', 'languages' => $languages])
        </div>
        <div id="spiritual-details-view-content" class="tab-content hidden">
            @include('users.partials._spiritual_details', ['user' => $user, 'view' => 'view', 'shikshaLevels' => $shikshaLevels, 'sevas' => $sevas])
        </div>
    </div>

    <div id="edit-mode" class="hidden">
        @php
            $actionRoute = auth()->user()->hasRole('Admin') && request()->route()->getName() !== 'my-profile.show'
                ? route('profile.update', $user)
                : route('my-profile.update');
        @endphp
        <form id="edit-profile-form" action="{{ $actionRoute }}" method="POST">
            @csrf
            @method('PUT')
            <div id="my-details-edit-content" class="tab-content">
                @include('users.partials._my_details', ['user' => $user, 'view' => 'edit'])
            </div>
            <div id="contact-details-edit-content" class="tab-content hidden">
                @include('users.partials._contact_details', ['user' => $user, 'view' => 'edit'])
            </div>
            <div id="additional-details-edit-content" class="tab-content hidden">
                @include('users.partials._additional_details', ['user' => $user, 'view' => 'edit', 'educations' => $educations, 'professions' => $professions, 'bloodGroups' => $bloodGroups, 'languages' => $languages])
            </div>
            <div id="spiritual-details-edit-content" class="tab-content hidden">
                @include('users.partials._spiritual_details', ['user' => $user, 'view' => 'edit', 'bhaktiSadans' => $bhaktiSadans, 'shikshaLevels' => $shikshaLevels, 'sevas' => $sevas])
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButton = document.getElementById('edit-button');
        const saveButton = document.getElementById('save-button');
        const cancelButton = document.getElementById('cancel-button');
        const viewMode = document.getElementById('view-mode');
        const editMode = document.getElementById('edit-mode');
        const tabs = document.querySelectorAll('.tab-link');
        const viewTabContents = document.querySelectorAll('#view-mode .tab-content');
        const editTabContents = document.querySelectorAll('#edit-mode .tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();

                tabs.forEach(t => t.classList.remove('border-indigo-500', 'text-indigo-600'));
                this.classList.add('border-indigo-500', 'text-indigo-600');

                const tabName = this.dataset.tab;

                viewTabContents.forEach(content => {
                    if (content.id === `${tabName}-view-content`) {
                        content.classList.remove('hidden');
                    } else {
                        content.classList.add('hidden');
                    }
                });

                editTabContents.forEach(content => {
                    if (content.id === `${tabName}-edit-content`) {
                        content.classList.remove('hidden');
                    } else {
                        content.classList.add('hidden');
                    }
                });
            });
        });

        editButton.addEventListener('click', function() {
            viewMode.classList.add('hidden');
            editMode.classList.remove('hidden');
            editButton.classList.add('hidden');
            saveButton.classList.remove('hidden');
            cancelButton.classList.remove('hidden');
        });

        cancelButton.addEventListener('click', function() {
            viewMode.classList.remove('hidden');
            editMode.classList.add('hidden');
            editButton.classList.remove('hidden');
            saveButton.classList.add('hidden');
            cancelButton.classList.add('hidden');
        });

        saveButton.addEventListener('click', function() {
            document.getElementById('edit-profile-form').submit();
        });

        tabs[0].click();
    });
</script>
@endpush
@endsection
