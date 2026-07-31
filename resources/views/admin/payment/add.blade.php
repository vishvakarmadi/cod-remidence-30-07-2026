@extends('admin.admin_layouts')
@section('admin_content')

<style>
#payment-processing-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(15, 23, 42, 0.9);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 9999999 !important;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    text-align: center;
}
.payment-processing-content {
    background: rgba(30, 41, 59, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 40px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.5);
    display: flex;
    flex-direction: column;
    align-items: center;
}
.payment-spinner {
    border: 4px solid rgba(255, 255, 255, 0.1);
    width: 64px;
    height: 64px;
    border-radius: 50%;
    border-left-color: #ff7529;
    animation: spin 1s linear infinite;
    margin-bottom: 24px;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
.payment-title {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 8px;
    color: #ffffff;
}
.payment-subtitle {
    font-size: 15px;
    font-weight: 500;
    color: #ff7529;
    margin-bottom: 24px;
    letter-spacing: 0.5px;
}
.payment-warning-box {
    font-size: 13px;
    color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
    padding: 16px;
    border-radius: 8px;
    border: 1px solid rgba(239, 68, 68, 0.2);
    line-height: 1.6;
    display: flex;
    align-items: flex-start;
    text-align: left;
}
.payment-warning-box i {
    font-size: 18px;
    margin-right: 12px;
    margin-top: 2px;
    color: #ef4444;
}
</style>

<div class="container-fluid">
    <!-- Page header section  -->
    
<div class="row clearfix">
    <div class="col-12">
        <div class="card pt-30">
            <div class="card-header">
                <h5 class="card-title mb-0">Wallet Balance: Rs
                    <span>{{ Auth::guard('admin')->user()->wallet_blc }}</span><a data-action="collapse"
                        class="float-right toggle-icon"><i class="fa fa-minus"></i></a></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <strong>Quick Recharge:</strong>
                            <div class="btn-group " role="group" aria-label="Quick Recharge Buttons">
                                <input type="button" class="btn btn-primary" value="1000"
                                    onclick="calculation(this.value, 'amount')">
                                <input type="button" class="btn btn-primary" value="2000"
                                    onclick="calculation(this.value, 'amount')">
                                <input type="button" class="btn btn-primary" value="5000"
                                    onclick="calculation(this.value, 'amount')">
                                <input type="button" class="btn btn-primary" value="10000"
                                    onclick="calculation(this.value, 'amount')">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="coupon_code" id="label_coupon_code" class="text-left">Enter Amount:</label>
                            <input type="number" name="amount" class="form-control phone-number"
                                placeholder="Minimum recharge amount Rs 500" id="amount" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="coupon_code" id="label_coupon_code" class="text-left">Apply Coupon code (Optional)</label>
                            
                            @php
                                $userForBlade = auth()->guard('admin')->user();
                                $userIdForBlade = $userForBlade->id;
                                $userEmailForBlade = trim($userForBlade->email ?? '');
                                $companyIdForBlade = $userForBlade->company_id;

                                $hasUsedCouponForBlade = false;

                                // Check in Recharge table by user_id
                                if (\App\Models\Admin\Recharge::where('user_id', $userIdForBlade)->where('coupon_amount', '>', 0)->exists()) {
                                    $hasUsedCouponForBlade = true;
                                }

                                // Check in Recharge table by company_id (if valid and > 1)
                                if (!$hasUsedCouponForBlade && $companyIdForBlade !== null && $companyIdForBlade !== '' && (int)$companyIdForBlade > 1) {
                                    if (\App\Models\Admin\Recharge::where('company_id', $companyIdForBlade)->where('coupon_amount', '>', 0)->exists()) {
                                        $hasUsedCouponForBlade = true;
                                    }
                                }

                                // Check in Payments table by email (if email is not empty and is valid)
                                if (!$hasUsedCouponForBlade && !empty($userEmailForBlade) && filter_var($userEmailForBlade, FILTER_VALIDATE_EMAIL)) {
                                    if (\App\Models\Admin\Payment::where('user_email', $userEmailForBlade)->where('coupon_amount', '>', 0)->exists()) {
                                        $hasUsedCouponForBlade = true;
                                    }
                                }

                                // Check in Payments table by company_id (if valid and > 1)
                                if (!$hasUsedCouponForBlade && $companyIdForBlade !== null && $companyIdForBlade !== '' && (int)$companyIdForBlade > 1) {
                                    if (\App\Models\Admin\Payment::where('company_id', $companyIdForBlade)->where('coupon_amount', '>', 0)->exists()) {
                                        $hasUsedCouponForBlade = true;
                                    }
                                }
                            @endphp

                            @if($hasUsedCouponForBlade)
                                <div class="alert alert-warning d-flex align-items-center" role="alert" style="border-radius: 8px; border-left: 4px solid #ffc107; background-color: #fffdf5; font-size: 13px; font-weight: 500; color: #856404; padding: 12px 15px; margin-top: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                    <i class="fa fa-info-circle mr-2" style="font-size: 16px; color: #ffc107;"></i>
                                    <span>Coupon already redeemed. Only one coupon usage is allowed per user.</span>
                                </div>
                            @else
                                <div class="input-group">
                                    <input type="text" name="coupon_code" id="coupon_code" class="form-control"
                                        placeholder="Coupon code">
                                    <div class="input-group-append" id="apply_button">
                                        <button type="button" class="btn btn-primary" id="apply_coupon"
                                            onclick="applyCoupon()">Apply</button>
                                    </div>
                                </div>
                                <div id="coupon_msg" class="mt-2" style="font-weight: bold;"></div>
                            @endif
                            
                            @if(isset($coupons) && $coupons->count() > 0)
                                <div class="mt-3">
                                    <label class="text-muted" style="font-size: 13px; font-weight: 600;">Available Coupons:</label>
                                    <div class="d-flex flex-wrap">
                                        @foreach($coupons as $coupon)
                                            <div class="card p-3 mb-2 mr-2 d-inline-block" style="border: 1.5px dashed #28a745; border-radius: 8px; background-color: #f4faf6; cursor: pointer; max-width: 250px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(40, 167, 69, 0.08);" onclick="selectCoupon('{{ $coupon->coupon_code }}')" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(40, 167, 69, 0.15)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 2px 4px rgba(40, 167, 69, 0.08)';">
                                                <span class="badge" style="font-size: 13px; font-weight: bold; background-color: #28a745; color: #fff; padding: 4px 8px; border-radius: 4px; letter-spacing: 0.5px;">{{ $coupon->coupon_code }}</span>
                                                <div style="font-size: 11px; margin-top: 8px; color: #1b5e20; line-height: 1.4; font-weight: 500;">
                                                    @if($coupon->coupon_type == 'Percentage')
                                                        Get <strong>{{ $coupon->coupon_discount }}% off</strong> on your recharge!
                                                    @elseif($coupon->coupon_type == 'Amount')
                                                        Get <strong>₹{{ $coupon->coupon_discount }} off</strong>!
                                                    @else
                                                        Get <strong>₹{{ $coupon->coupon_discount }} Cashback</strong>!
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="button"  class="btn btn-primary col-lg-3" id="model">Recharge</button>
                    </div><br>
                    <div id="codpayment"></div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="rechargeForm" action="{{route('admin.payment.add_money')}}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">You Are Just One Step Away</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Name</label><span class="required"> *</span>
                            <input class="form-control" type="text" name="name" required
                                value="{{ Auth::guard('admin')->user()->name }}" readonly>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="form-control-label">Email</label><span class="required"> *</span>
                            <input class="form-control" type="email" name="email" required
                                value="{{ Auth::guard('admin')->user()->email }}" readonly>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Phone Number</label><span class="required"> *</span>
                            <input class="form-control" type="Number" name="number"
                                value="{{ Auth::guard('admin')->user()->mobile }}" required readonly>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="coupon_code" id="label_coupon_code" class="text-left">Select Payment Mode:</label>
                                <select name="payment" class="form-control" id="paymentMethod">
                                    <option value="1">Online Payment</option>
                                    <!--<option value="2">COD Remittance</option>-->
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Amount to be Paid</label><span class="required"> *</span>
                            <input class="form-control" type="Number" name="modal_amount" id="modal_amount" required readonly>
                            <input type="hidden" name="total_amount" id="total_amount" required>
                        </div>
                    </div>
                    <input type="hidden" name="razorpay_response" id="razorpay_response" value="">
                    <input type="hidden" name="coupon_code" id="coupon_code_applied" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="payButton">Proceed To Pay</button>
                </div>
            </div>

    
        </form>
    </div>
</div>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<?php $currentuser = Auth::guard('admin')->user()->id; ?>
<script>
    var isSubmitting = false;

    window.addEventListener('beforeunload', function (e) {
        if (isSubmitting) {
            e.preventDefault();
            e.returnValue = 'Payment is being processed. Please do not refresh or close the page.';
            return 'Payment is being processed. Please do not refresh or close the page.';
        }
    });

    window.addEventListener('keydown', function (e) {
        if (isSubmitting) {
            if (e.key === 'F5' || 
                (e.ctrlKey && e.key === 'r') || 
                (e.ctrlKey && e.shiftKey && e.key === 'R') ||
                (e.key === 'Backspace' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA')) {
                e.preventDefault();
            }
        }
    });

    function showPaymentProcessingOverlay() {
        isSubmitting = true;
        
        // Hide modal
        $('#exampleModalCenter').modal('hide');
        
        var overlayHtml = `
            <div id="payment-processing-overlay">
                <div class="payment-processing-content">
                    <div class="payment-spinner"></div>
                    <div class="payment-title">Payment Processing!</div>
                    <div class="payment-subtitle">Updating your wallet balance...</div>
                    <div class="payment-warning-box">
                        <i class="fa fa-exclamation-triangle"></i>
                        <div>
                            <strong>WARNING:</strong> Please do NOT refresh this page or close the window. Cutting/closing this page will interrupt the transaction and may lead to payment failure or wallet balance not reflecting.
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('body').append(overlayHtml);
    }

    document.getElementById('payButton').onclick = function () {
        if($("select[name=payment]").val() == 1){
            var amount = (document.getElementById('amount').value) + '.00';
            var razorpayOptions = {
                key: "{{ env('RAZORPAY_KEY') }}",
                amount: amount *100, 
                name: "{{ Auth::guard('admin')->user()->name }}",
                description: "Razorpay payment",
                image: "/public/avatar.png",
                prefill: {
                    name: "{{ Auth::guard('admin')->user()->name }}",
                    email: "{{ Auth::guard('admin')->user()->email }}"
                },
                theme: {
                    color: "#ff7529"
                },
                notes: {
                    user_id: "{{ Auth::guard('admin')->user()->id }}",
                    coupon_code: document.getElementById('coupon_code_applied') ? document.getElementById('coupon_code_applied').value : ""
                },
                handler: function (response) {
                    console.log('Payment successful:', response);
                    document.getElementById('razorpay_response').value = JSON.stringify(response);
                    
                    showPaymentProcessingOverlay();
                    
                    $.ajax({
                        url: "{{ route('admin.payment.add_money') }}",
                        type: "POST",
                        data: $('#rechargeForm').serialize(),
                        success: function(res) {
                            $('#payment-processing-overlay').remove();
                            location.reload();
                        },
                        error: function(err) {
                            $('#payment-processing-overlay').remove();
                            if(err.responseJSON && err.responseJSON.message) {
                                toastr.error(err.responseJSON.message);
                            } else {
                                toastr.error('Payment processing failed. Please try again.');
                            }
                        }
                    });
                },
                prefill: {
                    name: "{{ Auth::guard('admin')->user()->name }}",
                    email: "{{ Auth::guard('admin')->user()->email }}"
                }
            };
            var rzp = new Razorpay(razorpayOptions);
            rzp.open();
        } else {
            const paymentMethod = $('#paymentMethod').val();
            const amount = $('#modal_amount').val();

            showPaymentProcessingOverlay();

            $.ajax({
                url: "{{ route('admin.payment.cod') }}",
                type: "get",
                data: {
                    paymentMethod: paymentMethod,
                    amount: amount,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    isSubmitting = false;
                    $('#payment-processing-overlay').remove();
                    location.reload();
                },
                error: function(error) {
                    isSubmitting = false;
                    $('#payment-processing-overlay').remove();
                    console.error(error);
                    toastr.error('Payment processing failed. Please try again.');
                }
            });
        }
    };
</script>


<script>
function calculation(val, amount) {
    $('#amount').val(val);
    $('#total_amount').val(val);
}

$('#amount').on('input', function(){
    $('#total_amount').val($(this).val());
});

function applyCoupon() {
    var couponCode = document.getElementById('coupon_code').value;
    var amount = document.getElementById('amount').value;

    // Check if the couponCode is empty
    if (amount.trim() === '') {
        toastr.error('Enter amount first..!!');
        return; // Exit the function if couponCode is empty
    }
    
    // Check if the couponCode is empty
    if (couponCode.trim() === '') {
        toastr.error('Enter coupon first..!!');
        return; 
    }

    $.ajax({
        url: "{{ route('admin.coupons.validate') }}",
        type: "get",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            coupon_code: couponCode,
            amount: amount,
        },
        success: function(response) {
            if (response.success) {
                var amountInput = $('#amount');
                $('#coupon_code_applied').val(couponCode);
                
                if (response.is_cashback) {
                    var msg = 'Coupon ' + couponCode + ' applied! ₹' + response.cashback_amount + ' cashback will be added after payment.';
                    toastr.success(msg);
                    $('#coupon_msg').html('<span class="text-success">' + msg + '</span>');
                    // Don't reduce the amount
                } else {
                    amountInput.val(response.amount);
                    $('#modal_amount').val(response.amount);
                    var msg = 'Discount applied. New amount: ₹' + response.amount;
                    toastr.success(msg);
                    $('#coupon_msg').html('<span class="text-success">' + msg + '</span>');
                }
                
                $('#apply_button').hide();
                $('#coupon_code').prop('readonly', true);
            } else {
                var errorMsg = response.message || 'Invalid coupon or expired';
                toastr.error(errorMsg);
                $('#coupon_msg').html('<span class="text-danger">' + errorMsg + '</span>');
            }
        },
        error: function() {
            toastr.error('Error while validating coupon');
        }
    });
}


$(document).ready(function() {
    $("#model").on("click", function(event) {
        var amountInput = document.getElementById('amount');
        if (amountInput.value.trim() !== '') {
            if(amountInput.value.trim() < 500){
               toastr.error("Minimum recharge amount is 500..!!"); 
            }else{
                $('#exampleModalCenter').modal('show'); 
                $('#modal_amount').val(amountInput.value);
            }
        } else {
            toastr.error("Enter money first..!!");
        }
    });
});

function selectCoupon(code) {
    $('#coupon_code').val(code);
    toastr.info('Coupon code ' + code + ' selected! Click "Apply" to validate.');
}

</script>

@endsection