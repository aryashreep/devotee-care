<div id="additional-details" class="tab-content hidden">
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="education">Education</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="education" name="education" type="text" value="{{ $user->education->name ?? '' }}" disabled>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="profession">Profession</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="profession" name="profession" type="text" value="{{ $user->profession->name ?? '' }}" disabled>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="blood_group">Blood Group</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="blood_group" name="blood_group" type="text" value="{{ $user->bloodGroup->name ?? '' }}" disabled>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Languages</label>
        <div class="flex flex-wrap">
            @foreach($user->languages as $language)
                <span class="bg-gray-200 text-gray-700 text-sm font-semibold mr-2 px-2.5 py-0.5 rounded-full">{{ $language->name }}</span>
            @endforeach
        </div>
    </div>
</div>
