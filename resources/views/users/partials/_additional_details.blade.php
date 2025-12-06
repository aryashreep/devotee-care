@if($view === 'view')
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <p class="text-gray-700 font-bold">Education:</p>
        <p>{{ $user->education->name ?? 'N/A' }}</p>
    </div>
    <div>
        <p class="text-gray-700 font-bold">Profession:</p>
        <p>{{ $user->profession->name ?? 'N/A' }}</p>
    </div>
    <div>
        <p class="text-gray-700 font-bold">Blood Group:</p>
        <p>{{ $user->bloodGroup->name ?? 'N/A' }}</p>
    </div>
    <div>
        <p class="text-gray-700 font-bold">Languages:</p>
        <p>{{ $user->languages->implode('name', ', ') }}</p>
    </div>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="education_id" class="block text-sm font-medium text-gray-700">Education</label>
        <select name="education_id" id="education_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @foreach($educations as $education)
                <option value="{{ $education->id }}" {{ old('education_id', $user->education_id) == $education->id ? 'selected' : '' }}>{{ $education->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="profession_id" class="block text-sm font-medium text-gray-700">Profession</label>
        <select name="profession_id" id="profession_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @foreach($professions as $profession)
                <option value="{{ $profession->id }}" {{ old('profession_id', $user->profession_id) == $profession->id ? 'selected' : '' }}>{{ $profession->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="blood_group_id" class="block text-sm font-medium text-gray-700">Blood Group</label>
        <select name="blood_group_id" id="blood_group_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @foreach($bloodGroups as $bloodGroup)
                <option value="{{ $bloodGroup->id }}" {{ old('blood_group_id', $user->blood_group_id) == $bloodGroup->id ? 'selected' : '' }}>{{ $bloodGroup->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Languages</label>
        <div class="mt-2 grid grid-cols-3 gap-2">
            @foreach($languages as $language)
                <label class="inline-flex items-center">
                    <input type="checkbox" name="languages[]" value="{{ $language->id }}" class="form-checkbox" {{ in_array($language->id, old('languages', $user->languages->pluck('id')->toArray())) ? 'checked' : '' }}>
                    <span class="ml-2">{{ $language->name }}</span>
                </label>
            @endforeach
        </div>
    </div>
</div>
@endif
