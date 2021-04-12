@extends('layout')
<form method="POST" action="{{ route('pay') }}" accept-charset="UTF-8" class="form-horizontal" role="form">
    <div class="row flex-center" style="margin-top:40px;">
      <div class="col-md-8 col-md-offset-2">
        <p>
            <div>
                Subscription Amount: <br><br>
                ₦ {{round($amount/100,2)}}
            </div>
        </p>

        <input type="hidden" name="email" value="{{$autoshop->auto_shop_email}}"> {{-- required --}}
        <input type="hidden" name="amount" value="{{$amount}}"> {{-- required in kobo --}}
        <input type="hidden" name="quantity" value="1">
        <input type="hidden" name="currency" value="NGN">
        <input type="hidden" name="metadata" value="{{ json_encode($array = ['duration' => $duration,'autoshop_id' => $autoshop->id,'subscription_id'=>$subscription->id]) }}" > {{-- For other necessary things you want to add to your payload. it is optional though --}}
        <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}"> {{-- required --}}
        {{ csrf_field() }} {{-- works only when using laravel 5.1, 5.2 --}}
        <p>
          <button class="btn btn-success btn-lg btn-block" type="submit" value="Pay Now!">
            <i class="fa fa-plus-circle fa-lg"></i> Pay Now to Subscribe!
          </button>
        </p>
      </div>
    </div>
</form>