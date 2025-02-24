<div x-data="{
  stripe: null,
  policy: @entangle('policy'),
  paymentElement: null,
  processing: false,
  error: null,
  handleSubmit() {
    this.processing = true
    this.error = null

    address = {
      city: '{{ addslashes($this->billing->city) }}',
      country: '{{ addslashes($this->billing->country->iso2) }}',
      line1: '{{ addslashes($this->billing->line_one) }}',
      line2: '{{ addslashes($this->billing->line_two) }}',
      postal_code: '{{ addslashes($this->billing->postcode) }}',
      state: '{{ addslashes($this->billing->state) }}',
    }

    this.stripe.confirmPayment({
        elements,
        confirmParams: {
          // Make sure to change this to your payment completion page
          return_url: '{{ $returnUrl ?: url()->current() }}',
          payment_method_data: {
            billing_details: {
              name: '{{ $this->billing->first_name }} {{ $this->billing->last_name }}',
              email: '{{ $this->billing->contact_email }}',
              phone: '{{ $this->billing->contact_phone }}',
              address
            }
          }
        },
      }).then(result => {
        if (result.error) {
          this.error = result.error.message
          this.processing = false
        }
      }).catch(error => {
        this.processing = false
      })
  },
  init() {
    this.stripe = Stripe('{{ config('services.stripe.public_key')}}');

    elements = this.stripe.elements({
      clientSecret: '{{ $this->clientSecret }}'
    });

    this.paymentElement = elements.create('payment', {
      fields: {
        billingDetails: 'never'
      }
    });
    this.paymentElement.mount(this.$refs.paymentElement);
  }
}">
    <!-- Display a payment form -->
    <form id="paymentForm" x-on:submit.prevent="handleSubmit()">
        <input type="hidden" name="dataValue" id="dataValue" />
        <input type="hidden" name="dataDescriptor" id="dataDescriptor" />
        <button type="button"
                class="AcceptUI"
                data-billingAddressOptions='{"show":true, "required":false}'
                data-apiLoginID="YOUR API LOGIN ID"
                data-clientKey="YOUR PUBLIC CLIENT KEY"
                data-acceptUIFormBtnTxt="Submit"
                data-acceptUIFormHeaderTxt="Card Information"
                data-paymentOptions='{"showCreditCard": true, "showBankAccount": true}'
                data-responseHandler="responseHandler">Pay
        </button>
    </form>
</div>
