@extends('layouts.admin')

@section('title', 'Assign Plan Manually - Admin')

@section('content')

<section class="py-8 lg:py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <h1 class="text-2xl font-medium text-zinc-900 mb-1">Assign Plan Manually</h1>
            <p class="text-zinc-500 text-sm">Select a user and a plan to instantly activate a subscription.</p>
        </div>

        @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-sm text-sm text-red-700">
            {{ session('error') }}
        </div>
        @endif

        <div class="bg-white border border-stone-200/50 rounded-sm p-6 shadow-sm">
            <form action="{{ route('admin.subscriptions.store-assign') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="user_id" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">Select User</label>
                    <select name="user_id" id="user_id" required class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-sm text-sm focus:outline-none focus:border-[#2563EB]">
                        <option value="">-- Choose a User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ (isset($selectedUserId) && $selectedUserId == $user->id) ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="plan_id" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">Select Plan</label>
                    <select name="plan_id" id="plan_id" required class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-sm text-sm focus:outline-none focus:border-[#2563EB]">
                        <option value="">-- Choose a Plan --</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} (₹{{ number_format($plan->price, 0) }}) - {{ $plan->duration_days }} Days</option>
                        @endforeach
                    </select>
                    @error('plan_id')
                        <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">Assignment Type</label>
                    <div class="flex flex-col gap-3 p-4 bg-stone-50 border border-stone-200 rounded-sm">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="radio" name="assign_type" value="custom_offer" checked class="mt-1 w-4 h-4 accent-[#2563EB]">
                            <div>
                                <p class="text-sm font-bold text-zinc-900">Assign as Custom Offer (User Pays)</p>
                                <p class="text-xs text-zinc-500">The user will see this exclusive offer on their dashboard and must check out and pay to activate it.</p>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="radio" name="assign_type" value="instant" class="mt-1 w-4 h-4 accent-[#2563EB]">
                            <div>
                                <p class="text-sm font-bold text-zinc-900">Activate Instantly (Free / Manual override)</p>
                                <p class="text-xs text-zinc-500">The plan will be instantly activated for the user without requiring them to pay.</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="mb-6" id="discounted-price-block">
                    <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">Custom Discounted Price (Optional)</label>
                    <input type="number" name="discounted_price" min="0" step="0.01" class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-sm text-sm focus:outline-none focus:border-[#2563EB]" placeholder="e.g. 299">
                    <p class="text-xs text-zinc-500 mt-1">Leave blank to use the plan's default price. Only applies if "Assign as Custom Offer" is selected.</p>
                    @error('discounted_price')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-8 flex justify-between items-center">
                    <a href="{{ route('admin.subscriptions') }}" class="text-sm font-semibold text-zinc-500 hover:text-zinc-900">Cancel</a>
                    <button type="submit" class="px-6 py-3 bg-[#2563EB] text-white text-sm font-bold rounded-sm hover:bg-blue-700 transition-colors shadow-sm">
                        Assign Plan
                    </button>
                </div>
            </form>
        </div>

    </div>
</section>

@endsection
