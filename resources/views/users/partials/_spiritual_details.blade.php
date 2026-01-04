@if($view === 'view')
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <p class="text-gray-700 font-bold">Rounds:</p>
        <p>{{ $user->rounds ?? 'N/A' }}</p>
    </div>
    <div>
        <p class="text-gray-700 font-bold">Bhakti Sadan:</p>
        <p>{{ $user->bhaktiSadan->name ?? 'N/A' }}</p>
    </div>
    <div>
        <p class="text-gray-700 font-bold">Initiated:</p>
        <p>{{ $user->initiated ? 'Yes' : 'No' }}</p>
    </div>
    @if($user->initiated)
    <div>
        <p class="text-gray-700 font-bold">Initiated Name:</p>
        <p>{{ $user->initiated_name ?? 'N/A' }}</p>
    </div>
    <div>
        <p class="text-gray-700 font-bold">Spiritual Master Name:</p>
        <p>{{ $user->spiritual_master ?? 'N/A' }}</p>
    </div>
    @endif
    <div>
        <p class="text-gray-700 font-bold">Shiksha Levels:</p>
        <p>{{ $user->shikshaLevels->implode('name', ', ') }}</p>
    </div>
    <div>
        <p class="text-gray-700 font-bold">Second Initiation:</p>
        <p>{{ $user->second_initiation ? 'Yes' : 'No' }}</p>
    </div>
    <div>
        <p class="text-gray-700 font-bold">Life Membership:</p>
        <p>{{ $user->life_membership ? 'Yes' : 'No' }}</p>
    </div>
    @if($user->life_membership)
    <div>
        <p class="text-gray-700 font-bold">Life Member No:</p>
        <p>{{ $user->life_member_no ?? 'N/A' }}</p>
    </div>
    <div>
        <p class="text-gray-700 font-bold">Temple:</p>
        <p>{{ $user->temple ?? 'N/A' }}</p>
    </div>
    @endif
    <div>
        <p class="text-gray-700 font-bold">Services:</p>
        <p>{{ $user->sevas->implode('name', ', ') }}</p>
    </div>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="rounds" class="block text-sm font-medium text-gray-700">Rounds</label>
        <select name="rounds" id="rounds" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @for ($i = 0; $i <= 108; $i++)
                <option value="{{ $i }}" {{ old('rounds', $user->rounds) == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
        </select>
    </div>
    <div>
        <label for="bhakti_sadan_id" class="block text-sm font-medium text-gray-700">Bhakti Sadan</label>
        <select name="bhakti_sadan_id" id="bhakti_sadan_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @foreach($bhaktiSadans as $sadan)
            <option value="{{ $sadan->id }}" {{ old('bhakti_sadan_id', $user->bhakti_sadan_id) == $sadan->id ? 'selected' : '' }}>{{ $sadan->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Initiated</label>
        <div class="mt-2">
            <label class="inline-flex items-center">
                <input type="radio" name="initiated" value="1" class="form-radio" {{ old('initiated', $user->initiated) == 1 ? 'checked' : '' }}>
                <span class="ml-2">Yes</span>
            </label>
            <label class="inline-flex items-center ml-6">
                <input type="radio" name="initiated" value="0" class="form-radio" {{ old('initiated', $user->initiated) == 0 ? 'checked' : '' }}>
                <span class="ml-2">No</span>
            </label>
        </div>
    </div>
    <div>
        <label for="initiated_name" class="block text-sm font-medium text-gray-700">Initiated Name</label>
        <input type="text" name="initiated_name" id="initiated_name" value="{{ old('initiated_name', $user->initiated_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="spiritual_master" class="block text-sm font-medium text-gray-700">Spiritual Master Name</label>
        <input type="text" name="spiritual_master" id="spiritual_master" value="{{ old('spiritual_master', $user->spiritual_master) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Shiksha Levels</label>
        <div class="mt-2 grid grid-cols-3 gap-2">
            @foreach($shikshaLevels as $level)
            <label class="inline-flex items-center">
                <input type="checkbox" name="shiksha_levels[]" value="{{ $level->id }}" class="form-checkbox" {{ in_array($level->id, old('shiksha_levels', $user->shikshaLevels->pluck('id')->toArray())) ? 'checked' : '' }}>
                <span class="ml-2">{{ $level->name }}</span>
            </label>
            @endforeach
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Second Initiation</label>
        <div class="mt-2">
            <label class="inline-flex items-center">
                <input type="radio" name="second_initiation" value="1" class="form-radio" {{ old('second_initiation', $user->second_initiation) == 1 ? 'checked' : '' }}>
                <span class="ml-2">Yes</span>
            </label>
            <label class="inline-flex items-center ml-6">
                <input type="radio" name="second_initiation" value="0" class="form-radio" {{ old('second_initiation', $user->second_initiation) == 0 ? 'checked' : '' }}>
                <span class="ml-2">No</span>
            </label>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Life Membership</label>
        <div class="mt-2">
            <label class="inline-flex items-center">
                <input type="radio" name="life_membership" value="1" class="form-radio" {{ old('life_membership', $user->life_membership) == 1 ? 'checked' : '' }}>
                <span class="ml-2">Yes</span>
            </label>
            <label class="inline-flex items-center ml-6">
                <input type="radio" name="life_membership" value="0" class="form-radio" {{ old('life_membership', $user->life_membership) == 0 ? 'checked' : '' }}>
                <span class="ml-2">No</span>
            </label>
        </div>
    </div>
    <div>
        <label for="life_member_no" class="block text-sm font-medium text-gray-700">Life Member No</label>
        <input type="text" name="life_member_no" id="life_member_no" value="{{ old('life_member_no', $user->life_member_no) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="temple" class="block text-sm font-medium text-gray-700">Temple</label>
        <input type="text" name="temple" id="temple" value="{{ old('temple', $user->temple) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Services</label>
        <div class="mt-2 grid grid-cols-3 gap-2">
            @foreach($sevas as $service)
            <label class="inline-flex items-center">
                <input type="checkbox" name="services[]" value="{{ $service->id }}" class="form-checkbox" {{ in_array($service->id, old('services', $user->sevas->pluck('id')->toArray())) ? 'checked' : '' }}>
                <span class="ml-2">{{ $service->name }}</span>
            </label>
            @endforeach
        </div>
    </div>
</div>
@endif