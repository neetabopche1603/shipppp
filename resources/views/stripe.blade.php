<!DOCTYPE html>
<html>
   <head>
      <title>Payment</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <style type="text/css">
         .panel-title {
         display: inline;
         font-weight: bold;
         }
         .display-table {
         display: table;
         }
         .display-tr {
         display: table-row;
         }
         .display-td {
         display: table-cell;
         vertical-align: middle;
         width: 61%;
         }

         body{

            background-color: #eee;
            }

            .container{

               height: 80vh;

            }


            .card{
            border:none;
            }

            .form-control {
            border-bottom: 2px solid #eee !important;
            border: none;
            font-weight: 600
            }

            .form-control:focus {
            color: #495057;
            background-color: #fff;
            border-color: #8bbafe;
            outline: 0;
            box-shadow: none;
            border-radius: 0px;
            border-bottom: 2px solid blue !important;
            }



            .inputbox {
            position: relative;
            margin-bottom: 20px;
            width: 100%
            }

            .inputbox span {
            position: absolute;
            top: 7px;
            left: 11px;
            transition: 0.5s
            }

            .inputbox i {
            position: absolute;
            top: 13px;
            right: 8px;
            transition: 0.5s;
            color: #3F51B5
            }

            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0
            }

            .inputbox input:focus~span {
            transform: translateX(-0px) translateY(-15px);
            font-size: 12px
            }

            .inputbox input:valid~span {
            transform: translateX(-0px) translateY(-15px);
            font-size: 12px
            }

            .card-blue{

            background-color: #492bc4;
            color:#fff;
            padding: 20px;
            }

            .hightlight{

            background-color: #5737d9;
            padding: 10px;
            border-radius: 10px;
            margin-top: 15px;
            font-size: 14px;
            }

            .yellow{

            color: #fdcc49; 
            }

            .decoration{

            text-decoration: none;
            font-size: 14px;
            }

            .btn-success {
            color: #fff;
            background-color: #492bc4;
            border-color:#492bc4;
            }

            .btn-success:hover {
            color: #fff;
            background-color:#492bc4;
            border-color: #492bc4;
            }


            .decoration:hover{

            text-decoration: none;
            color: #fdcc49; 
            }     
      </style>
   </head>
   <body>
      <br>
      <br>
      <div class="container bg-info">
            <div class="mb-4">

               <h2 style="margin-left: 100px;">Confirm order and pay</h2>
               <span style="margin-left: 100px;">please make the payment, after that you can enjoy all the features and benefits.</span>
               
            </div>
         {{-- <h1 class="text-center" style="font-weight: 500px;font-size: 27px;"> Stripe Payment Gateway  Shipment </h1> --}}
         <br>
         <div class="row">
            <div class="col-md-6 border border-dark col-md-offset-1">
               <div class="panel panel-default credit-card-box">
                  <div class="panel-heading" >
                     <div class="row display-tr" >
                        <h3 class="panel-title display-td" style="font-size: 13px;">Payment Details</h3>
                        <div class="display-td" >                            
                        </div>
                     </div>
                  </div>
                  <div class="panel-body">
                     @if (Session::has('success'))
                     <div class="alert alert-success text-center">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <p>{{ Session::get('success') }}</p>
                     </div>
                     @endif
                     <!-- <form 
                        role="form" 
                        action="{{ route('stripe.post') }}" 
                        method="post" 
                        class="require-validation"
                        data-cc-on-file="false"
                        data-stripe-publishable-key="{{ env('STRIPE_KEY') }}"
                        id="payment-form"> -->

                        <form action="{{ route('stripe.post') }}" method="post" id="payment-form">
                        @csrf
                        <!-- <div class='form-row row'>
                           <div class='col-xs-12 form-group required'>
                              <label class='control-label'>Name on Card</label> <input
                                 class='form-control name' size='4' type='text'>
                           </div>
                        </div>
                        <div class='form-row row'>
                           <div class='col-xs-12 form-group card required'>
                              <label class='control-label'>Card Number</label> <input
                                 autocomplete='off' id="cc-cardr" class='form-control card-number' size='19' maxlength="19" type='text'>
                           </div>
                        </div> -->
                        {{-- <input type="hidden" name="amount" value="{{ $data->total_amount }}"> --}}
                        <div class='form-row row'>

                        <input type='hidden' name='order_id' value="{{ $_GET['order_id'] }}"/>

                        <?php if(isset($_GET['uid'])){?>
                          <input type='hidden' name='uid' value="{{ $_GET['uid'] }}"/>
                      <?php   }else{

                        } ?>
                        
                        <input type='hidden' name='amount' value="{{ $total }}"/>
                        <input type='hidden' name='type' value="{{ $_GET['type'] }}" />
                           <!-- <div class='col-xs-12 col-md-4 form-group cvc required'>
                              <label class='control-label'>CVC</label> <input autocomplete='off'
                                 class='form-control card-cvc' placeholder='ex. 311' size='4'
                                 type='text'>
                           </div>
                           <div class='col-xs-12 col-md-4 form-group expiration required'>
                              <label class='control-label'>Expiration Month</label> <input
                                 class='form-control card-expiry-month' placeholder='MM' size='2'
                                 type='text'>
                           </div>
                           <div class='col-xs-12 col-md-4 form-group expiration required'>
                              <label class='control-label'>Expiration Year</label> <input
                                 class='form-control card-expiry-year' placeholder='YYYY' size='4'
                                 type='text'>
                           </div> -->

                           <div id="card-element" class="form-control" style='height: 2.4em; padding-top: .7em;'>
                           </div>
                                <div id="card-errors" role="alert"></div>


                        </div>
                        {{-- 
                        <div class='form-row row'>
                           <div class='col-md-12 error form-group hide'>
                              <div class='alert-danger alert'>Please correct the errors and try
                                 again.
                              </div>
                           </div>
                        </div>
                        --}}

                        <div class="row">
                           <div class="col-xs-12">
                              &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           </div>
                        </div>


                        <div class="row">
                           <div class="col-xs-12">
                              <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now</button>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
            <div class="col-md-4">

               <div class="card card-blue p-3 text-white mb-3">

              
                

               
  
                  <span>You have to pay</span>
                   <div class="d-flex flex-row align-items-end mb-3">
                       <h1 class="mb-0 yellow">${{ $total }}</h1>
                       <input type="hidden" id="totals" name="totals" value = "{{ $total }}">
                   </div>
  
                   <span>Enjoy all the features and perk after you complete the payment</span>
  
                   <div class="hightlight">
  
                       <span>100% Guaranteed support and update.</span>
                       
  
                   </div>
                   
               </div>
               
            </div>
         </div>
      </div>
   </body>

 <!--  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
       const cc = $('#cc-cardr');

function chunksOf(string, size) {
  var i, j, arr = [];
  for (i = 0, j = string.length; i < j; i += size) {
    arr.push(string.substring(i, i + size));
  }
  return arr;
}

cc.on('input', function() {
  const elem = cc.get(0);                    // store DOM element ref
  const cursorPosition = elem.selectionEnd;  // remember cursor position

  const value = cc.val().replace(/\D/g, ''); // strip non-numeric chars
  const numberChunks = chunksOf(value, 4);   // split into 4-digit chunks
  const newValue = numberChunks.join(' ');   // combine 4-digit chunks into a single string
  cc.val(newValue);                          // update new value

  elem.selectionStart = elem.selectionEnd = cursorPosition + 1; // reset cursor position since the value changed
});
</script>-->

   <script src="https://checkout.stripe.com/checkout.js"></script>

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 

   <script src="https://js.stripe.com/v3/"></script>
   


    <script>

        // function modalOpen(val)
        // {
        //     $("#planAmountButton").html("Pay $"+$(val).attr('data-price'));
        //     $("#planId").val($(val).attr('data-id'));
        //     $("#planModelHeader").html("Plan - "+$(val).attr('data-name'));
        //     $("#myModal").modal('show');

        // }

       // var stripe = Stripe('pk_test_51KsjneSF6gyJgm24UTuIV8ylEOJ8wDr9TmxHzX9z20LdOBb0bZHdBxj3aoygw09CqlyMOwd6IDoYFoDMGYYoecKF00OSY6RkFt');
       

        var stripe = Stripe('{{ env("STRIPE_KEY") }}');
        var cardss = stripe.elements();
        var style = {
            base: {
                fontSize: '16px',
                color: '#32325d',
            },
        };

        var card = cardss.create('card', {
            style: style,
            hidePostalCode : true

        });

        card.mount('#card-element');
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
                } else {
                stripeTokenHandler(result.token);
                }
            });
        });
        function stripeTokenHandler(token) {


           
            
            console.log('token',token)

           var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            



      
            var url_string = window.location.href; //window.location.href
            var url = new URL(url_string);
            var order_id = url.searchParams.get("order_id");
            var uid = url.searchParams.get("uid");
                  // var amount = url.searchParams.get("amount");
            var amount =  $('#totals').val();
                  // console.log(amount);
            var type1 = url.searchParams.get("type");
                  



            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);

            // hiddenInput.setAttribute('type', 'hidden');
            // hiddenInput.setAttribute('name', 'order_id');
            // hiddenInput.setAttribute('value', order_id);

            // hiddenInput.setAttribute('type', 'hidden');
            // hiddenInput.setAttribute('name', 'uid');
            // hiddenInput.setAttribute('value', uid);

            // hiddenInput.setAttribute('type', 'hidden');
            // hiddenInput.setAttribute('name', 'amount');
            // hiddenInput.setAttribute('value', amount);

            // hiddenInput.setAttribute('type', 'hidden');
            // hiddenInput.setAttribute('name', 'type');
            // hiddenInput.setAttribute('value', type1);

            form.appendChild(hiddenInput); 
            form.submit();
           
        }
    </script>


<!--
   <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
   <script type="text/javascript">
      $(function() {
         
          var $form         = $(".require-validation");
         
          $('form.require-validation').bind('submit', function(e) {
              var $form         = $(".require-validation"),
              inputSelector = ['input[type=email]', 'input[type=password]',
                               'input[type=text]', 'input[type=file]',
                               'textarea'].join(', '),
              $inputs       = $form.find('.required').find(inputSelector),
              $errorMessage = $form.find('div.error'),
              valid         = true;
              $errorMessage.addClass('hide');
        
              $('.has-error').removeClass('has-error');
              $inputs.each(function(i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                  $input.parent().addClass('has-error');
                  $errorMessage.removeClass('hide');
                  e.preventDefault();
                }
              });
         
              if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                  name: $('.name').val(),
                  number: $('.card-number').val(),
                  cvc: $('.card-cvc').val(),
                  exp_month: $('.card-expiry-month').val(),
                  exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
              }
        
        });
        
        function stripeResponseHandler(status, response) {
              if (response.error) {
                  $('.error')
                      .removeClass('hide')
                      .find('.alert')
                      .text(response.error.message);
              } else {
                  /* token contains id, last4, and card type */
                  var token = response['id'];
                     
                  $form.find('input[type=text]').empty();
                  $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
      
                  var url_string = window.location.href; //window.location.href
                  var url = new URL(url_string);
                  var order_id = url.searchParams.get("order_id");
                  var uid = url.searchParams.get("uid");
                  // var amount = url.searchParams.get("amount");
                  var amount =  $('#totals').val();
                  // console.log(amount);
                  var type = url.searchParams.get("type");
                  $form.append("<input type='hidden' name='order_id' value='" + order_id + "'/>");
                  $form.append("<input type='hidden' name='uid' value='" + uid + "'/>");
                  $form.append("<input type='hidden' name='amount' value='" + amount + "'/>");
                  $form.append("<input type='hidden' name='type' value='" + type + "'/>");
      
                  $form.get(0).submit();
              }
          }
         
      });
   </script> -->
   @if (Session::has('success'))

   <script type="text/javascript">
      alert('Payment Succesful',3000);
        
       //window.close();

      <?php if($_GET['type']=='subscription'){  ?>
     window.location.href = "https://shipment.netlify.app/#/login";
     <?php }else{ ?>
             
         window.close();

    <?php   } ?> 
     
   </script>
   @endif
</html>