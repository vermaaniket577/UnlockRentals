@extends('layouts.app')

@section('title', 'Secure Checkout - UnlockRentals')

@section('content')
<div class="py-12 lg:py-20 bg-stone-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <h1 class="text-3xl font-serif font-light text-zinc-900 mb-2">Secure Checkout</h1>
            <p class="text-zinc-500 text-sm">Review your plan and complete payment to unlock owner contacts.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            {{-- Plan Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white border border-stone-200 rounded-sm p-6 shadow-sm">
                    <h2 class="text-xs font-bold text-zinc-400 uppercase tracking-widest mb-4">Plan Summary</h2>
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-zinc-900">{{ $plan->name }}</h3>
                        <p class="text-xs text-zinc-500 mt-1">{{ $plan->description }}</p>
                    </div>
                    
                    <div class="space-y-3 py-4 border-t border-b border-stone-100">
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500">Plan Price</span>
                            <span class="text-zinc-900 font-medium">{{ $plan->formatted_price }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500">Duration</span>
                            <span class="text-zinc-900 font-medium">{{ $plan->duration_days }} Days</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500">Platform Fee</span>
                            <span class="text-emerald-500 font-medium">Free</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <span class="text-sm font-bold text-zinc-900 uppercase">Total to Pay</span>
                        <span class="text-2xl font-black text-[#2563EB]">{{ $plan->formatted_price }}</span>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-blue-50/50 border border-blue-100 rounded-sm">
                    <div class="flex gap-3">
                        <i class="ph ph-shield-check text-blue-500 text-xl"></i>
                        <p class="text-xs text-blue-700 leading-relaxed">
                            Your payment is secured with 256-bit SSL encryption. We do not store your card or bank details.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Payment Selector --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Payment Methods --}}
                <div class="bg-white border border-stone-200 rounded-sm p-6 shadow-sm" x-data="{ method: 'upi' }">
                    <h2 class="text-xs font-bold text-zinc-400 uppercase tracking-widest mb-6">Select Payment Method</h2>
                    
                    <div class="space-y-3">
                        {{-- UPI --}}
                        <label class="group relative flex items-center p-4 border rounded-sm cursor-pointer transition-all hover:bg-stone-50 border-stone-200 group-has-[:checked]:border-[#2563EB] group-has-[:checked]:bg-blue-50/10">
                            <input type="radio" name="payment_method" value="upi" id="payment_upi" class="sr-only peer" checked>
                            <div class="w-10 h-10 bg-stone-50 rounded-sm flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                <i class="ph ph-qr-code text-xl text-zinc-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-zinc-900">UPI (PhonePe, Google Pay, BHIM)</p>
                                <p class="text-[11px] text-zinc-500">Instant activation using your favorite UPI app</p>
                            </div>
                            <div class="relative w-5 h-5 flex-shrink-0">
                                <div class="absolute inset-0 border-2 border-stone-300 rounded-full group-has-[:checked]:border-[#2563EB] transition-colors"></div>
                                <div class="absolute inset-[3px] bg-[#2563EB] rounded-full scale-0 group-has-[:checked]:scale-100 transition-transform duration-200"></div>
                            </div>
                        </label>

                        {{-- Cards --}}
                        <label class="group relative flex items-center p-4 border rounded-sm cursor-pointer transition-all hover:bg-stone-50 border-stone-200 group-has-[:checked]:border-[#2563EB] group-has-[:checked]:bg-blue-50/10">
                            <input type="radio" name="payment_method" value="card" id="payment_card" class="sr-only peer">
                            <div class="w-10 h-10 bg-stone-50 rounded-sm flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                <i class="ph ph-credit-card text-xl text-zinc-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-zinc-900">Credit / Debit Card</p>
                                <p class="text-[11px] text-zinc-500">Visa, Mastercard, RuPay, and Maestro supported</p>
                            </div>
                            <div class="relative w-5 h-5 flex-shrink-0">
                                <div class="absolute inset-0 border-2 border-stone-300 rounded-full group-has-[:checked]:border-[#2563EB] transition-colors"></div>
                                <div class="absolute inset-[3px] bg-[#2563EB] rounded-full scale-0 group-has-[:checked]:scale-100 transition-transform duration-200"></div>
                            </div>
                        </label>

                        {{-- Net Banking --}}
                        <label class="group relative flex items-center p-4 border rounded-sm cursor-pointer transition-all hover:bg-stone-50 border-stone-200 group-has-[:checked]:border-[#2563EB] group-has-[:checked]:bg-blue-50/10">
                            <input type="radio" name="payment_method" value="net" id="payment_net" class="sr-only peer">
                            <div class="w-10 h-10 bg-stone-50 rounded-sm flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                <i class="ph ph-bank text-xl text-zinc-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-zinc-900">Net Banking</p>
                                <p class="text-[11px] text-zinc-500">All major Indian banks supported</p>
                            </div>
                            <div class="relative w-5 h-5 flex-shrink-0">
                                <div class="absolute inset-0 border-2 border-stone-300 rounded-full group-has-[:checked]:border-[#2563EB] transition-colors"></div>
                                <div class="absolute inset-[3px] bg-[#2563EB] rounded-full scale-0 group-has-[:checked]:scale-100 transition-transform duration-200"></div>
                            </div>
                        </label>
                    </div>

                    {{-- Form Submission --}}
                    <form action="{{ route('plans.purchase.process', $plan) }}" method="POST" id="payment-form" class="mt-10">
                        @csrf
                        
                        {{-- Hidden inputs for Razorpay --}}
                        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                        <input type="hidden" name="razorpay_signature" id="razorpay_signature">

                        <div id="payment-simulation-box" class="hidden mb-6 p-8 bg-zinc-900 rounded-sm text-center">
                            <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-white/20 border-t-white mb-4"></div>
                            <p class="text-white font-medium">Connecting to secure gateway...</p>
                            <p class="text-white/50 text-[11px] mt-2 italic">Please do not refresh or click back button</p>
                        </div>

                        <button type="button" id="pay-button" class="w-full py-4 bg-[#2563EB] text-white font-bold uppercase tracking-widest rounded-sm shadow-xl shadow-blue-600/20 hover:bg-[#1D4ED8] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3">
                            <span class="btn-text flex items-center gap-3">
                                <i class="ph ph-lock-key"></i> Pay {{ $plan->formatted_price }} Now
                            </span>
                            <span class="btn-loader hidden flex items-center gap-2">
                                <i class="ph ph-circle-notch animate-spin text-xl"></i> Securely Connecting...
                            </span>
                        </button>
                    </form>
                </div>

                {{-- Full Page Processing Overlay --}}
                <div id="processing-overlay" class="fixed inset-0 bg-white/90 backdrop-blur-sm z-[9999] hidden flex flex-col items-center justify-center">
                    <div class="relative w-24 h-24 mb-6">
                        <div class="absolute inset-0 border-4 border-[#2563EB]/10 rounded-full"></div>
                        <div class="absolute inset-0 border-4 border-t-[#2563EB] rounded-full animate-spin"></div>
                    </div>
                    <h2 class="text-xl font-bold text-zinc-900 mb-2">Processing Your Payment</h2>
                    <p class="text-zinc-500 animate-pulse">Please do not refresh or close this window...</p>
                </div>

                {{-- Trust Badges --}}
                <div class="flex items-center justify-center gap-10 opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-500 py-6 border-t border-stone-100 mt-4">
                    <img src="https://www.vectorlogo.zone/logos/upi/upi-ar21.svg" class="h-8 w-auto" alt="UPI">
                    <img src="https://www.vectorlogo.zone/logos/visa/visa-ar21.svg" class="h-5 w-auto" alt="Visa">
                    <img src="https://www.vectorlogo.zone/logos/mastercard/mastercard-ar21.svg" class="h-10 w-auto" alt="Mastercard">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/c/cb/Rupay-Logo.png" class="h-4 w-auto" alt="RuPay">
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('pay-button').addEventListener('click', function(e) {
    const btn = this;
    const simulation = document.getElementById('payment-simulation-box');
    const form = document.getElementById('payment-form');

    @if(isset($razorpayOrder) && $razorpayKeyId)
        const options = {
            "key": "{{ $razorpayKeyId }}",
            "amount": "{{ $razorpayOrder->amount }}",
            "currency": "INR",
            "name": "UnlockRentals",
            "description": "Purchase {{ $plan->name }} Plan",
            "image": "https://cdn-icons-png.flaticon.com/512/5551/5551980.png", {{-- Fallback for logo --}}
            "order_id": "{{ $razorpayOrder->id }}",
            "handler": function (response){
                // Show full screen processing overlay
                document.getElementById('processing-overlay').classList.remove('hidden');
                
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                document.getElementById('razorpay_signature').value = response.razorpay_signature;
                
                form.submit();
            },
            "prefill": {
                "name": "{{ auth()->user()->name }}",
                "email": "{{ auth()->user()->email }}",
                "contact": "{{ auth()->user()->phone ?? '' }}"
            },
            "theme": {
                "color": "#2563EB"
            },
            "modal": {
                "ondismiss": function(){
                    btn.disabled = false;
                    btn.querySelector('.btn-text').classList.remove('hidden');
                    btn.querySelector('.btn-loader').classList.add('hidden');
                }
            }
        };

        // Show button loader while opening modal
        btn.disabled = true;
        btn.querySelector('.btn-text').classList.add('hidden');
        btn.querySelector('.btn-loader').classList.remove('hidden');

        const rzp1 = new Razorpay(options);
        rzp1.open();
    @else
        alert('Payment gateway is currently not configured by the administrator. Please try again later.');
    @endif
});
</script>
@endpush
@endsection
