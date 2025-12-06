@extends('layouts.guest')

@section('title', 'Register - Step 4')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-lg p-8 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Spiritual Details (Step 4 of 5)</h2>
        <form action="{{ route('register.step4.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Are you Initiated? *</label>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" name="initiated" value="1" class="form-radio" required onchange="toggleInitiation(true)">
                        <span class="ml-2">Yes</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" name="initiated" value="0" class="form-radio" onchange="toggleInitiation(false)">
                        <span class="ml-2">No</span>
                    </label>
                </div>
            </div>

            <div class="mb-4 hidden" id="spiritual_master_div">
                <label for="spiritual_master_name" class="block text-sm font-medium text-gray-700">Spiritual Master Name</label>
                <input type="text" name="spiritual_master_name" id="spiritual_master_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div class="mb-4 hidden" id="not_initiated_div">
                <div class="mb-4">
                    <label for="rounds" class="block text-sm font-medium text-gray-700">How many rounds you are doing? *</label>
                    <select name="rounds" id="rounds" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @for ($i = 0; $i <= 16; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Shiksha level</label>
                    <div class="mt-2 grid grid-cols-3 gap-2">
                        @foreach($shikshaLevels as $level)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="shiksha_levels[]" value="{{ $level->id }}" class="form-checkbox">
                                <span class="ml-2">{{ $level->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Second Initiation *</label>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" name="second_initiation" value="1" class="form-radio" required>
                        <span class="ml-2">Yes</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" name="second_initiation" value="0" class="form-radio">
                        <span class="ml-2">No</span>
                    </label>
                </div>
            </div>

            <div class="mb-4">
                <label for="bhakti_sadan_id" class="block text-sm font-medium text-gray-700">Connected to which Bhakti Sadan *</label>
                <select name="bhakti_sadan_id" id="bhakti_sadan_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    @foreach($bhaktiSadans as $sadan)
                        <option value="{{ $sadan->id }}">{{ $sadan->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Do you have ISKCON life membership? *</label>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" name="life_membership" value="1" class="form-radio" required onchange="toggleLifeMembership(true)">
                        <span class="ml-2">Yes</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" name="life_membership" value="0" class="form-radio" onchange="toggleLifeMembership(false)">
                        <span class="ml-2">No</span>
                    </label>
                </div>
            </div>

            <div class="mb-4 hidden" id="life_membership_div">
                <div class="mb-4">
                    <label for="life_member_no" class="block text-sm font-medium text-gray-700">Life Member No</label>
                    <input type="text" name="life_member_no" id="life_member_no" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="mb-4">
                    <label for="temple" class="block text-sm font-medium text-gray-700">Taken from Which Temple</label>
                    <input type="text" name="temple" id="temple" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Temple Services *</label>
                <div class="mt-2 grid grid-cols-3 gap-2">
                    @foreach($services as $service)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="services[]" value="{{ $service->id }}" class="form-checkbox">
                            <span class="ml-2">{{ $service->name }}</span>
                        </label>
                    @endforeach
                </div>
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
