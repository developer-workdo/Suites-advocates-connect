<table class="table dataTable table modal-table">
    <tr>
        <th>{{ __('Order Id') }}</th>
        <td>{{ $order->order_id }}</td>
    </tr>
    <tr>
        <th>{{ __('Plan Name ') }}</th>
        <td>{{ $order->plan_name }}</td>
    </tr>
    <tr>
        <th>{{ __('Plan Price') }}</th>
        <td>{{ !empty($order->price_currency_symbol) ? $order->price_currency_symbol : '$' }}{{ $order->price }}</td>
    </tr>
    <tr>
        <th>{{ __('Payment Type') }}</th>
        <td>{{ $order->payment_type }}</td>
    </tr>
    <tr>
        <th>{{ __('Payment Status') }}</th>
        <td>{{ $order->payment_status }}</td>
    </tr>
</table>
{{ Form::open(['route' => ['pesapal.refund.request', $order->id], 'method' => 'POST']) }}
<div class="modal-body">
    <div class="row">
        <input type="hidden"name="pesapal_order_tracking_id" value="{{ $order->pesapal_order_tracking_id }}">
        <input type="hidden"name="pesapal_confirmation_code" value="{{ $order->pesapal_confirmation_code }}">
        <div class="form-group col-md-12">
            <div class="mb-3">
                <label for="amount" class="form-label">{{ __('Refund Amount') }}</label>
                <input type="number" step="0.01" class="form-control" id="amount" name="refund_amount"
                    placeholder="{{ __('Enter Refund Amount') }}" required>
            </div>
        </div>
        <div class="form-group col-md-12">
            <div class="mb-3">
                <label for="refund_reason" class="form-label">{{ __('Reason for Refund') }}</label>
                <textarea class="form-control" id="refund_reason" name="refund_reason" rows="3" required></textarea>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Refund') }}" class="btn btn-primary ms-2">
</div>
{{ Form::close() }}
