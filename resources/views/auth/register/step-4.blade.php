@extends('layouts.guest')

@section('title', 'Register - Step 4')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-lg p-8 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Spiritual Details (Step 4 of 5)</h2>

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

        <form action="{{ route('register.step4.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Are you Initiated? *</label>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" name="initiated" value="1" class="form-radio" required onchange="toggleInitiation(true)" {{ old('initiated') == '1' ? 'checked' : '' }}>
                        <span class="ml-2">Yes</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" name="initiated" value="0" class="form-radio" onchange="toggleInitiation(false)" {{ old('initiated') == '0' ? 'checked' : '' }}>
                        <span class="ml-2">No</span>
                    </label>
                </div>
                @error('initiated')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4 @if(old('initiated') != '1') hidden @endif" id="spiritual_master_div">
                <label for="spiritual_master_name" class="block text-sm font-medium text-gray-700">Spiritual Master Name</label>
                <input type="text" name="spiritual_master_name" id="spiritual_master_name" value="{{ old('spiritual_master_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('spiritual_master_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4 @if(old('initiated') != '0') hidden @endif" id="not_initiated_div">
                <div class="mb-4">
                    <label for="rounds" class="block text-sm font-medium text-gray-700">How many rounds you are doing? *</label>
                    <select name="rounds" id="rounds" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @for ($i = 0; $i <= 16; $i++)
                            <option value="{{ $i }}" {{ old('rounds') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    @error('rounds')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Shiksha level</label>
                    <div class="mt-2 grid grid-cols-3 gap-2">
                        @foreach($shikshaLevels as $level)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="shiksha_levels[]" value="{{ $level->id }}" class="form-checkbox" {{ is_array(old('shiksha_levels')) && in_array($level->id, old('shiksha_levels')) ? 'checked' : '' }}>
                                <span class="ml-2">{{ $level->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('shiksha_levels')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Second Initiation *</label>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" name="second_initiation" value="1" class="form-radio" required {{ old('second_initiation') == '1' ? 'checked' : '' }}>
                        <span class="ml-2">Yes</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" name="second_initiation" value="0" class="form-radio" {{ old('second_initiation') == '0' ? 'checked' : '' }}>
                        <span class="ml-2">No</span>
                    </label>
                </div>
                @error('second_initiation')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="bhakti_sadan_id" class="block text-sm font-medium text-gray-700">Connected to which Bhakti Sadan *</label>
                <select name="bhakti_sadan_id" id="bhakti_sadan_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    @foreach($bhaktiSadans as $sadan)
                        <option value="{{ $sadan->id }}" {{ old('bhakti_sadan_id') == $sadan->id ? 'selected' : '' }}>{{ $sadan->name }}</option>
                    @endforeach
                </select>
                @error('bhakti_sadan_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Do you have ISKCON life membership? *</label>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" name="life_membership" value="1" class="form-radio" required onchange="toggleLifeMembership(true)" {{ old('life_membership') == '1' ? 'checked' : '' }}>
                        <span class="ml-2">Yes</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" name="life_membership" value="0" class="form-radio" onchange="toggleLifeMembership(false)" {{ old('life_membership') == '0' ? 'checked' : '' }}>
                        <span class="ml-2">No</span>
                    </label>
                </div>
                @error('life_membership')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4 @if(old('life_membership') != '1') hidden @endif" id="life_membership_div">
                <div class="mb-4">
                    <label for="life_member_no" class="block text-sm font-medium text-gray-700">Life Member No</label>
                    <input type="text" name="life_member_no" id="life_member_no" value="{{ old('life_member_no') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('life_member_no')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="temple" class="block text-sm font-medium text-gray-700">Taken from Which Temple</label>
                    <input type="text" name="temple" id="temple" value="{{ old('temple') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('temple')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Temple Services *</label>
                <div class="mt-2 grid grid-cols-3 gap-2">
                    @foreach($services as $service)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="services[]" value="{{ $service->id }}" class="form-checkbox" {{ is_array(old('services')) && in_array($service->id, old('services')) ? 'checked' : '' }}>
                            <span class="ml-2">{{ $service->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('services')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between mt-8">
                <a href="{{ route('register.step3.show') }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Next
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    function toggleInitiation(show) {
        const spiritualMasterDiv = document.getElementById('spiritual_master_div');
        const notInitiatedDiv = document.getElementById('not_initiated_div');
        if (show) {
            spiritualMasterDiv.classList.remove('hidden');
            notInitiatedDiv.classList.add('hidden');
        } else {
            spiritualMasterDiv.classList.add('hidden');
            notInitiatedDiv.classList.remove('hidden');
        }
    }

    function toggleLifeMembership(show) {
        const lifeMembershipDiv = document.getElementById('life_membership_div');
        if (show) {
            lifeMembershipDiv.classList.remove('hidden');
        } else {
            lifeMembershipDiv.classList.add('hidden');
        }
    }
</script>
@endsection
