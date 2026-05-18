@extends('layouts.app')

@section('title', 'Secure Checkout - UnlockRentals')

@section('content')
<div class="relative overflow-hidden pt-32 pb-24 lg:pt-40 lg:pb-32 bg-stone-50/50 min-h-screen" id="checkout-page">
    {{-- High-end background glowing circles --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[-20%] left-[-10%] w-[600px] h-[600px] bg-blue-500/5 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-purple-500/5 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="text-center mb-14 max-w-lg mx-auto">
            <span class="text-[10px] font-bold text-[#2563EB] uppercase tracking-[0.25em] block mb-3 font-sans">Payment Portal</span>
            <h1 class="text-3xl lg:text-4xl font-extrabold text-zinc-900 tracking-tight leading-tight mb-2">
                Secure <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#2563EB] to-indigo-600 font-serif italic font-normal">Checkout</span>
            </h1>
            <p class="text-zinc-500 text-sm font-light leading-relaxed">Review your selected plan and finalize your secure transaction.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            {{-- Plan Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-[4px] bg-gradient-to-r from-[#2563EB] to-indigo-600"></div>
                    
                    <h2 class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-4">Plan Summary</h2>
                    <div class="mb-5">
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[9px] font-extrabold rounded-md uppercase tracking-wider">Premium Access</span>
                        </div>
                        <h3 class="text-xl font-bold text-zinc-900 font-sans">{{ $plan->name }}</h3>
                        <p class="text-xs text-zinc-400 mt-1 leading-relaxed font-light">{{ $plan->description }}</p>
                    </div>
                    
                    <div class="space-y-3.5 py-4 border-t border-b border-stone-100">
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500 font-light">Plan Price</span>
                            <span class="text-zinc-900 font-bold font-mono">₹{{ number_format($plan->price, 0) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500 font-light">Duration</span>
                            <span class="text-zinc-900 font-bold font-mono">{{ $plan->duration_days }} Days</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500 font-light">Platform Fee</span>
                            <span class="text-emerald-600 font-bold">Free</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-5">
                        <span class="text-xs font-bold text-zinc-900 uppercase tracking-wider">Total to Pay</span>
                        <span class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[#2563EB] to-indigo-600 font-mono">₹{{ number_format($plan->price, 0) }}</span>
                    </div>
                </div>

                {{-- Trust Details --}}
                <div class="mt-6 p-5 bg-gradient-to-r from-blue-50/50 to-indigo-50/30 border border-blue-100 rounded-2xl">
                    <div class="flex gap-3">
                        <i class="ph-fill ph-shield-check text-blue-500 text-2xl flex-shrink-0"></i>
                        <p class="text-xs text-blue-800 leading-relaxed font-light">
                            Your payment is fully secured with **256-bit SSL encryption**. We act only as a facilitator and do not store card or banking credentials.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Payment Selector --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Payment Methods --}}
                <div class="bg-white border border-stone-200 rounded-2xl p-8 shadow-sm" x-data="{ method: 'upi' }">
                    <h2 class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-6">Select Payment Method</h2>
                    
                    <div class="space-y-4">
                        {{-- UPI Option --}}
                        <label :class="method === 'upi' ? 'border-[#2563EB] bg-blue-50/10' : 'border-stone-200 hover:bg-stone-50'" 
                               class="group relative flex items-center p-5 border rounded-2xl cursor-pointer transition-all duration-300" @click="method = 'upi'">
                            <input type="radio" name="payment_method" value="upi" id="payment_upi" class="sr-only" :checked="method === 'upi'">
                            <div class="w-14 h-12 bg-white rounded-xl flex items-center justify-center mr-4 p-1.5 border border-stone-200 flex-shrink-0 transition-transform group-hover:scale-105 shadow-sm">
                                <img src="https://www.vectorlogo.zone/logos/upi/upi-ar21.svg" class="w-full h-auto object-contain" alt="UPI" referrerpolicy="no-referrer">
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-zinc-900 leading-none">UPI (PhonePe, Google Pay, BHIM)</p>
                                <div class="flex items-center gap-4 mt-2">
                                    <div class="flex items-center gap-1 select-none">
                                        <img src="https://img.icons8.com/color/48/google-pay.png" class="h-4.5 w-auto object-contain" alt="Google Pay" referrerpolicy="no-referrer">
                                        <span class="text-[9px] text-zinc-500 font-bold tracking-tight">GPay</span>
                                    </div>
                                    <div class="flex items-center gap-1 select-none">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/e/e1/PhonePe_Logo.svg" class="h-4.5 w-auto object-contain" alt="PhonePe" referrerpolicy="no-referrer">
                                        <span class="text-[9px] text-[#5F259F] font-bold tracking-tight">PhonePe</span>
                                    </div>
                                    <div class="flex items-center gap-1 select-none">
                                        <img src="https://img.icons8.com/color/48/paytm.png" class="h-3.5 w-auto object-contain" alt="Paytm" referrerpolicy="no-referrer">
                                        <span class="text-[9px] text-[#002e6e] font-bold tracking-tight">Paytm</span>
                                    </div>
                                </div>
                            </div>
                            <div class="relative w-5 h-5 flex-shrink-0">
                                <div :class="method === 'upi' ? 'border-[#2563EB]' : 'border-stone-300'" class="absolute inset-0 border-2 rounded-full transition-colors"></div>
                                <div :class="method === 'upi' ? 'scale-100' : 'scale-0'" class="absolute inset-[3px] bg-[#2563EB] rounded-full transition-transform duration-200"></div>
                            </div>
                        </label>

                        {{-- Credit Card Option --}}
                        <label :class="method === 'card' ? 'border-[#2563EB] bg-blue-50/10' : 'border-stone-200 hover:bg-stone-50'" 
                               class="group relative flex items-center p-5 border rounded-2xl cursor-pointer transition-all duration-300" @click="method = 'card'">
                            <input type="radio" name="payment_method" value="card" id="payment_card" class="sr-only" :checked="method === 'card'">
                            <div class="w-14 h-12 bg-white rounded-xl flex items-center justify-center mr-4 p-2 border border-stone-200 flex-shrink-0 transition-transform group-hover:scale-105 shadow-sm">
                                <i class="ph-bold ph-credit-card text-2xl text-indigo-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-zinc-900 leading-none">Credit / Debit Card</p>
                                <div class="flex items-center gap-4 mt-2">
                                    <div class="flex items-center gap-1 select-none">
                                        <img src="https://img.icons8.com/color/48/visa.png" class="h-3.5 w-auto object-contain" alt="Visa" referrerpolicy="no-referrer">
                                        <span class="text-[9px] text-[#1A1F71] font-bold tracking-tight">Visa</span>
                                    </div>
                                    <div class="flex items-center gap-1 select-none">
                                        <img src="https://img.icons8.com/color/48/mastercard.png" class="h-4.5 w-auto object-contain" alt="Mastercard" referrerpolicy="no-referrer">
                                        <span class="text-[9px] text-zinc-500 font-bold tracking-tight">Mastercard</span>
                                    </div>
                                    <div class="flex items-center gap-1 select-none">
                                        <img src="https://img.icons8.com/color/48/rupay.png" class="h-4 w-auto object-contain" alt="RuPay" referrerpolicy="no-referrer">
                                        <span class="text-[9px] text-[#EE7826] font-bold tracking-tight">RuPay</span>
                                    </div>
                                </div>
                            </div>
                            <div class="relative w-5 h-5 flex-shrink-0">
                                <div :class="method === 'card' ? 'border-[#2563EB]' : 'border-stone-300'" class="absolute inset-0 border-2 rounded-full transition-colors"></div>
                                <div :class="method === 'card' ? 'scale-100' : 'scale-0'" class="absolute inset-[3px] bg-[#2563EB] rounded-full transition-transform duration-200"></div>
                            </div>
                        </label>

                        {{-- Net Banking Option --}}
                        <label :class="method === 'net' ? 'border-[#2563EB] bg-blue-50/10' : 'border-stone-200 hover:bg-stone-50'" 
                               class="group relative flex items-center p-5 border rounded-2xl cursor-pointer transition-all duration-300" @click="method = 'net'">
                            <input type="radio" name="payment_method" value="net" id="payment_net" class="sr-only" :checked="method === 'net'">
                            <div class="w-14 h-12 bg-white rounded-xl flex items-center justify-center mr-4 p-2 border border-stone-200 flex-shrink-0 transition-transform group-hover:scale-105 shadow-sm">
                                <i class="ph-bold ph-bank text-2xl text-purple-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-zinc-900 leading-none">Net Banking</p>
                                <div class="flex items-center gap-4 mt-2">
                                    <div class="flex items-center gap-1 select-none">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/cc/SBI-logo.svg" class="h-4.5 w-auto object-contain" alt="SBI" referrerpolicy="no-referrer">
                                        <span class="text-[9px] text-[#00B1EC] font-bold tracking-tight">SBI</span>
                                    </div>
                                    <div class="flex items-center gap-1 select-none">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/e/eb/HDFC%20Bank%20Logo.svg" class="h-3.5 w-auto object-contain" alt="HDFC" referrerpolicy="no-referrer">
                                        <span class="text-[9px] text-[#003366] font-bold tracking-tight">HDFC</span>
                                    </div>
                                    <div class="flex items-center gap-1 select-none">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/1/12/ICICI_Bank_Logo.svg" class="h-4.5 w-auto object-contain" alt="ICICI" referrerpolicy="no-referrer">
                                        <span class="text-[9px] text-[#751512] font-bold tracking-tight">ICICI</span>
                                    </div>
                                </div>
                            </div>
                            <div class="relative w-5 h-5 flex-shrink-0">
                                <div :class="method === 'net' ? 'border-[#2563EB]' : 'border-stone-300'" class="absolute inset-0 border-2 rounded-full transition-colors"></div>
                                <div :class="method === 'net' ? 'scale-100' : 'scale-0'" class="absolute inset-[3px] bg-[#2563EB] rounded-full transition-transform duration-200"></div>
                            </div>
                        </label>
                    </div>

                    {{-- Form Submission --}}
                    <form action="{{ route('plans.purchase.process', $plan) }}" method="POST" id="payment-form" class="mt-8">
                        @csrf
                        
                        {{-- Hidden inputs for Razorpay --}}
                        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                        <input type="hidden" name="razorpay_signature" id="razorpay_signature">

                        <div id="payment-simulation-box" class="hidden mb-6 p-8 bg-zinc-900 rounded-2xl text-center">
                            <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-white/20 border-t-white mb-4"></div>
                            <p class="text-white font-medium">Connecting to secure gateway...</p>
                            <p class="text-white/50 text-[11px] mt-2 italic">Please do not refresh or click back button</p>
                        </div>

                        <button type="button" id="pay-button" class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-extrabold uppercase tracking-wider rounded-xl shadow-xl shadow-blue-500/15 hover:shadow-2xl hover:shadow-blue-500/25 hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-3">
                            <span class="btn-text flex items-center gap-2">
                                <i class="ph-bold ph-lock-key text-base"></i> 
                                <span>Pay ₹{{ number_format($plan->price, 0) }} Now</span>
                            </span>
                            <span class="btn-loader hidden flex items-center gap-2">
                                <i class="ph ph-circle-notch animate-spin text-xl"></i> Securely Connecting...
                            </span>
                        </button>
                    </form>
                </div>

                {{-- Full Page Processing Overlay --}}
                <div id="processing-overlay" class="fixed inset-0 bg-white/95 backdrop-blur-md z-[9999] hidden flex flex-col items-center justify-center">
                    <div class="relative w-24 h-24 mb-6">
                        <div class="absolute inset-0 border-4 border-[#2563EB]/10 rounded-full"></div>
                        <div class="absolute inset-0 border-4 border-t-[#2563EB] rounded-full animate-spin"></div>
                    </div>
                    <h2 class="text-2xl font-bold text-zinc-900 mb-2">Processing Your Payment</h2>
                    <p class="text-zinc-500 animate-pulse font-light text-sm">Please do not refresh or close this window...</p>
                </div>

                {{-- Trust Badges --}}
                <div class="flex items-center justify-center gap-4 py-4 border-t border-stone-100 mt-6 select-none">
                    <div class="flex items-center gap-1 text-[9px] font-bold text-zinc-400 uppercase tracking-widest bg-stone-50 px-2.5 py-1 rounded-md border border-stone-200/60 shadow-sm">
                        <i class="ph-bold ph-shield-check text-blue-600 text-xs"></i>
                        <span>SSL Secured</span>
                    </div>
                    <div class="flex items-center gap-1 text-[9px] font-bold text-zinc-400 uppercase tracking-widest bg-stone-50 px-2.5 py-1 rounded-md border border-stone-200/60 shadow-sm">
                        <i class="ph-bold ph-lightning text-amber-500 text-xs"></i>
                        <span>Instant Active</span>
                    </div>
                    <div class="flex items-center gap-1 text-[9px] font-bold text-zinc-400 uppercase tracking-widest bg-stone-50 px-2.5 py-1 rounded-md border border-stone-200/60 shadow-sm">
                        <i class="ph-bold ph-lock text-emerald-500 text-xs"></i>
                        <span>PCI Compliant</span>
                    </div>
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
            "image": "https://cdn-icons-png.flaticon.com/512/5551/5551980.png",
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
