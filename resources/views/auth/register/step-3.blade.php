@extends('layouts.guest')

@section('title', 'Register - Step 3')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-lg p-8 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Additional Details (Step 3 of 5)</h2>
        <form action="{{ route('register.step3.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="education_id" class="block text-sm font-medium text-gray-700">Education *</label>
                <select name="education_id" id="education_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    @foreach($educations as $education)
                        <option value="{{ $education->id }}">{{ $education->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="profession_id" class="block text-sm font-medium text-gray-700">Profession *</label>
                <select name="profession_id" id="profession_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    @foreach($professions as $profession)
                        <option value="{{ $profession->id }}">{{ $profession->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Languages *</label>
                <div class="mt-2 grid grid-cols-3 gap-2">
                    @foreach($languages as $language)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="languages[]" value="{{ $language->id }}" class="form-checkbox">
                            <span class="ml-2">{{ $language->name }}</span>
                        </label>
                    @endforeach
                </div>
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
