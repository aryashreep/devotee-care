@extends('layouts.guest')

@section('title', 'Register - Step 3')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-lg p-8 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Additional Details (Step 3 of 5)</h2>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <span class="block sm:inline">There were some problems with your input.</span>
                <ul class="mt-3 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.step3.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="education_id" class="block text-sm font-medium text-gray-700">Education *</label>
                <select name="education_id" id="education_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="" selected>Select</option>
                    @foreach($educations as $education)
                        <option value="{{ $education->id }}" {{ old('education_id') == $education->id ? 'selected' : '' }}>{{ $education->name }}</option>
                    @endforeach
                </select>
                @error('education_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="profession_id" class="block text-sm font-medium text-gray-700">Profession *</label>
                <select name="profession_id" id="profession_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="" selected>Select</option>
                    @foreach($professions as $profession)
                        <option value="{{ $profession->id }}" {{ old('profession_id') == $profession->id ? 'selected' : '' }}>{{ $profession->name }}</option>
                    @endforeach
                </select>
                @error('profession_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="blood_group_id" class="block text-sm font-medium text-gray-700">Blood Group *</label>
                <select name="blood_group_id" id="blood_group_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="" selected>Select</option>
                    @foreach($bloodGroups as $bloodGroup)
                        <option value="{{ $bloodGroup->id }}" {{ old('blood_group_id') == $bloodGroup->id ? 'selected' : '' }}>{{ $bloodGroup->name }}</option>
                    @endforeach
                </select>
                @error('blood_group_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Languages *</label>
                <div class="mt-2 grid grid-cols-3 gap-2">
                    @foreach($languages as $language)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="languages[]" value="{{ $language->id }}" class="form-checkbox" {{ is_array(old('languages')) && in_array($language->id, old('languages')) ? 'checked' : '' }}>
                            <span class="ml-2">{{ $language->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('languages')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Dependants (Kids)</label>
                <div id="dependants-container" class="mt-2 space-y-4">
                    <!-- Dependant fields will be added here -->
                </div>
                <button type="button" id="add-dependant" class="mt-2 text-sm text-blue-500 hover:text-blue-700">
                    + Add More
                </button>
            </div>


            <div class="flex items-center justify-between mt-8">
                <a href="{{ route('register.step2.show') }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Next
                </button>
            </div>
        </form>
    </div>
</div>

<template id="dependant-template">
    <div class="dependant-row grid grid-cols-4 gap-4 p-4 border rounded-md relative">
        <input type="text" name="dependants[][name]" placeholder="Name" class="col-span-2 rounded-md border-gray-300 shadow-sm">
        <input type="number" name="dependants[][age]" placeholder="Age" class="rounded-md border-gray-300 shadow-sm">
        <select name="dependants[][gender]" class="rounded-md border-gray-300 shadow-sm">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <input type="date" name="dependants[][dob]" class="col-span-4 mt-2 rounded-md border-gray-300 shadow-sm">
        <button type="button" class="remove-dependant absolute top-2 right-2 text-red-500 hover:text-red-700">
            &times;
        </button>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addDependantBtn = document.getElementById('add-dependant');
    const dependantsContainer = document.getElementById('dependants-container');
    const dependantTemplate = document.getElementById('dependant-template');
    let dependantIndex = 0;

    addDependantBtn.addEventListener('click', function() {
        const newDependant = dependantTemplate.content.cloneNode(true);
        const inputs = newDependant.querySelectorAll('[name^="dependants"]');
        inputs.forEach(input => {
            const name = input.getAttribute('name').replace('[]', `[${dependantIndex}]`);
            input.setAttribute('name', name);
        });
        dependantsContainer.appendChild(newDependant);
        dependantIndex++;
    });

    dependantsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-dependant')) {
            e.target.closest('.dependant-row').remove();
        }
    });
});
</script>
@endsection
