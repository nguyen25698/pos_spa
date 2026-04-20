@extends('layouts.pos')

@section('title', 'POS Terminal - Nail Spa POS')

@section('content')
<form action="{{ route('pos.checkout') }}" method="POST" id="checkoutForm">
    @csrf

    <div class="pos-container">
        <!-- Main Area -->
        <div class="main-area">
            <!-- Staff Selection -->
            <div class="staff-section">
                <div class="staff-grid" id="staffGrid">
                    <button type="button" class="staff-btn active" data-staff-id="">
                        Any Staff
                    </button>
                    @foreach($staff as $member)
                    <button type="button" class="staff-btn" data-staff-id="{{ $member->id }}">
                        {{ $member->first_name }}
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Services Selection Area -->
            <div class="services-area">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3 style="font-size: 1.1rem; color: #333;">Services</h3>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" placeholder="Search services..." style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem;" id="serviceSearch">
                    </div>
                </div>

                <!-- Services Grid -->
                <div class="services-grid" id="servicesGrid">
                    @foreach($services as $service)
                    <div class="service-card" data-service-id="{{ $service->id }}" data-service-name="{{ $service->name }}" data-service-price="{{ $service->price }}">
                        <div class="service-name">{{ $service->name }}</div>
                        <div class="service-price">${{ number_format($service->price, 2) }}</div>
                    </div>
                    @endforeach
                </div>

                <!-- Selected Services -->
                <div style="margin-top: 1.5rem;">
                    <h4 style="font-size: 0.9rem; color: #666; margin-bottom: 0.5rem;">Selected Services:</h4>
                    <div id="cartItems">
                        <!-- Cart items will be added here dynamically -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            <div class="panel-tabs">
                <button type="button" class="panel-tab active" data-tab="checkin">Check-in List</button>
                <button type="button" class="panel-tab" data-tab="saved">Saved Tickets</button>
            </div>

            <div class="panel-content">
                <!-- Customer Selection -->
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="font-size: 0.85rem;">Customer</label>
                    <select name="customer_id" id="customer_id" style="font-size: 0.9rem;">
                        <option value="">Walk-in Customer</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <h4 style="font-size: 0.9rem; margin-bottom: 0.75rem;">Order Summary</h4>
                    
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="subtotalDisplay">$0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax (8%):</span>
                        <span id="taxDisplay">$0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Discount:</span>
                        <span id="discountDisplay">$0.00</span>
                    </div>
                    <div class="summary-total">
                        <span>Total:</span>
                        <span id="totalDisplay">$0.00</span>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="form-group">
                    <label style="font-size: 0.85rem;">Payment Method</label>
                    <select name="payment_method" required style="font-size: 0.9rem;">
                        <option value="cash">💵 Cash</option>
                        <option value="card">💳 Credit/Debit Card</option>
                        <option value="mobile">📱 Mobile Payment</option>
                    </select>
                </div>

                <div class="form-group">
                    <label style="font-size: 0.85rem;">Notes</label>
                    <textarea name="notes" rows="2" placeholder="Additional notes..." style="font-size: 0.9rem;"></textarea>
                </div>

                <!-- Hidden Inputs -->
                <input type="hidden" name="subtotal" id="subtotalInput" value="0">
                <input type="hidden" name="tax_amount" id="taxInput" value="0">
                <input type="hidden" name="discount_amount" id="discountInput" value="0">
                <input type="hidden" name="total_amount" id="totalInput" value="0">
                <input type="hidden" name="items" id="itemsInput" value="">
                <input type="hidden" name="staff_id" id="staffInput" value="">
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="bottom-bar">
        <div class="bottom-left">
            <button type="button" class="bottom-btn">🎁 Gift Card</button>
            <button type="button" class="bottom-btn">📦 Store Sale</button>
            <button type="button" class="bottom-btn" onclick="saveTicket()">💾 Save</button>
        </div>
        <button type="submit" class="payment-btn">Payment</button>
    </div>
</form>

<script>
const servicesData = @json($services);
let selectedItems = [];
let selectedStaffId = '';

// Staff button selection
document.querySelectorAll('.staff-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.staff-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        selectedStaffId = this.dataset.staffId;
        document.getElementById('staffInput').value = selectedStaffId;
    });
});

// Service card selection
document.querySelectorAll('.service-card').forEach(card => {
    card.addEventListener('click', function() {
        const serviceId = this.dataset.serviceId;
        const serviceName = this.dataset.serviceName;
        const servicePrice = parseFloat(this.dataset.servicePrice);

        // Check if already in cart
        const existingIndex = selectedItems.findIndex(item => item.service_id === serviceId);
        
        if (existingIndex >= 0) {
            selectedItems[existingIndex].quantity += 1;
        } else {
            selectedItems.push({
                service_id: serviceId,
                service_name: serviceName,
                price: servicePrice,
                quantity: 1
            });
        }

        renderCart();
        calculateTotal();
    });
});

function renderCart() {
    const cartContainer = document.getElementById('cartItems');
    cartContainer.innerHTML = '';

    selectedItems.forEach((item, index) => {
        const cartItem = document.createElement('div');
        cartItem.className = 'cart-item';
        cartItem.innerHTML = `
            <div class="cart-item-info">
                <div class="cart-item-name">${item.service_name}</div>
                <div class="cart-item-price">$${item.price.toFixed(2)} each</div>
            </div>
            <div class="cart-item-qty">
                <button type="button" class="qty-btn" onclick="updateQuantity(${index}, -1)">-</button>
                <span>${item.quantity}</span>
                <button type="button" class="qty-btn" onclick="updateQuantity(${index}, 1)">+</button>
            </div>
            <button type="button" class="remove-btn" onclick="removeItem(${index})">×</button>
        `;
        cartContainer.appendChild(cartItem);
    });
}

function updateQuantity(index, change) {
    selectedItems[index].quantity += change;
    if (selectedItems[index].quantity <= 0) {
        selectedItems.splice(index, 1);
    }
    renderCart();
    calculateTotal();
}

function removeItem(index) {
    selectedItems.splice(index, 1);
    renderCart();
    calculateTotal();
}

function calculateTotal() {
    let subtotal = 0;

    selectedItems.forEach(item => {
        subtotal += item.price * item.quantity;
    });

    const tax = subtotal * 0.08;
    const discount = 0;
    const total = subtotal + tax - discount;

    document.getElementById('subtotalDisplay').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('taxDisplay').textContent = '$' + tax.toFixed(2);
    document.getElementById('discountDisplay').textContent = '$' + discount.toFixed(2);
    document.getElementById('totalDisplay').textContent = '$' + total.toFixed(2);

    document.getElementById('subtotalInput').value = subtotal.toFixed(2);
    document.getElementById('taxInput').value = tax.toFixed(2);
    document.getElementById('discountInput').value = discount.toFixed(2);
    document.getElementById('totalInput').value = total.toFixed(2);
    document.getElementById('itemsInput').value = JSON.stringify(selectedItems);
}

function saveTicket() {
    alert('Ticket saved! (Feature coming soon)');
}

// Search services
document.getElementById('serviceSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('.service-card').forEach(card => {
        const name = card.dataset.serviceName.toLowerCase();
        card.style.display = name.includes(searchTerm) ? 'block' : 'none';
    });
});

// Panel tabs
document.querySelectorAll('.panel-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.panel-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
    });
});
</script>
@endsection
