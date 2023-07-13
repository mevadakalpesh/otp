@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
     @if( session()->has('error') )
      <div class="alert alert-danger">
         {{  session()->get('error') }}
      </div> 
      @endif
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          {{ __('Otp Verification') }}
        </div>

        <div class="card-body">
          <div class="alert alert-info">
            we have sent a OTP on {{ auth()->user()->email }}. please verify this
          </div>
          <form method="POST" action="{{ route('submitOtp') }}">
            @csrf

            <div class="row mb-3">
              <label for="otp" class="col-md-4 col-form-label
                text-md-end">{{ __('Enter OTP') }}</label>

              <div class="col-md-6">
                <input id="otp" type="otp" class="form-control @error('otp') is-invalid @enderror" name="otp" value="{{ old('otp') }}" required autocomplete="otp" autofocus>
                @error('otp')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror

                <p class="otp-note" style="display:none;">
                  otp will be Invalied after
                  <strong class="count-down">00:00</strong>
                </p>

              </div>
            </div>

            <div class="row mb-0">
              <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">
                  {{ __('Submit') }}
                </button>


                <a class="btn  resent-btn btn-link" style="display:none;"  href="javascript:void(0)">
                  {{ __('ReSend Otp') }}
                </a>
                <span style="display:none;" class="loader">Sending ...</span>

              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')

<script type="text/javascript">

  var otpData = @json($otpData);
countdown(otpData);
function countdown(otpData){
   $('.otp-note').show();
  var interwal =  setInterval(function () {
    const currentTimestamp = new Date().getTime();
    const timestamp2 = new Date(otpData.expires_at).getTime();
    const differenceInMilliseconds = timestamp2 - currentTimestamp;
    const differenceInSeconds = Math.floor(differenceInMilliseconds / 1000);
    if(differenceInSeconds < 0){
      clearInterval(interwal);
      $('.otp-note').hide();
      $('.resent-btn').show();
      return;
    }
    // Calculate the minutes and seconds
    const minutes = Math.floor(differenceInSeconds / 60);
    const seconds = differenceInSeconds % 60;
    $('.count-down').text(`${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`);
  }, 1000);
}
  
  
  
  $('.resent-btn').click(function(){
    $('.resent-btn').hide();
    $('.loader').show();
      var token ='{{ csrf_token()}}';
      $.ajax({
        url:'{{ route("resendOtp") }}',
        type:"POST",
        dataType:"JSON",
        data:{_token:token},
        success:function(data){
          if(data.code == 200){
            countdown(data.result);
          }
          $('.loader').hide();
        },
        error:function(data){
          $('.resent-btn').hide();
           $('.loader').hide();
        }
      });
  });
  
  
</script>

@endpush