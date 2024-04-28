<div class="d-flex flex-column justify-content-center align-items-center">
    @foreach($customers as $index => $customer)
        @if($customer->id ==  $user->customer_id)
            <h5 class="text-primary mt-1">{{ Str::limit($customer->nombre, 60) }}</h5>
        @endif
    @endforeach
</div>
