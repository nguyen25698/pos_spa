@extends('layouts.app')

@section('title', 'POS Terminal - Nail Spa POS')

@section('content')
<div class="card">
    <h2>💰 Point of Sale Terminal</h2>
    
    <form action="{{ route('pos.checkout') }}" method="POST" id="pos-form">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label for="customer_id">Customer (Optional)</label>
                <select name="customer_id" id="customer_id" class="form-control">
                    <option value="">Walk-in Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="staff_id">Staff Member (Optional)</label>
                <select name="staff_id" id="staff_id" class="form-control">
                    <option value="">Select Staff</option>
                    @foreach($staff as $member)
                        <option value="{{ $member->id }}">{{ $member->full_name }} - {{ $member->position }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
            <div>
                <h3>Add Items</h3>
                
                <div class="form-group">
                    <label>Item Type</label>
                    <select id="item-type" class="form-control" onchange="toggleItemSelect()">
                        <option value="service">Service</option>
                        <option value="product">Product</option>
                    </select>
                </div>

                <div class="form-group">
                    <label id="service-label">Select Service</label>
                    <select id="service-select" class="form-control">
                        <option value="">Choose a service...</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" data-price="{{ $service->price }}" data-name="{{ $service->name }}">
                                {{ $service->name }} - ${{ number_format($service->price, 2) }} ({{ $service->duration_minutes }} min)
                            </option>
                        @endforeach
                    </select>

                    <label id="product-label" style="display: none;">Select Product</label>
                    <select id="product-select" class="form-control" style="display: none;">
                        <option value="">Choose a product...</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->sale_price }}" data-name="{{ $product->name }}" data-qty="{{ $product->quantity }}">
                                {{ $product->name }} - ${{ number_format($product->sale_price, 2) }} (Stock: {{ $product->quantity }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" id="quantity" class="form-control" value="1" min="1">
                </div>

                <button type="button" class="btn btn-secondary" onclick="addItem()" style="margin-top: 0.5rem;">Add to Cart</button>

                <h3 style="margin-top: 2rem;">Cart Items</h3>
                <table id="cart-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="cart-items">
                    </tbody>
                </table>
                
                <div id="items-container" style="margin-top: 1rem;">
                    <!-- Hidden inputs for form submission -->
                </div>
            </div>

            <div>
                <div class="card" style="background: #f8f9fa;">
                    <h3>Order Summary</h3>
                    
                    <div style="margin: 1rem 0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>Subtotal:</span>
                            <span id="subtotal">$0.00</span>
                        </div>
                        
                        <div class="form-group">
                            <label for="tax-rate">Tax Rate (%)</label>
                            <input type="number" id="tax-rate" name="tax_rate" class="form-control" value="0" min="0" max="100" step="0.1" onchange="calculateTotal()">
                        </div>
                        
                        <div class="form-group">
                            <label for="discount">Discount ($)</label>
                            <input type="number" id="discount" name="discount_amount" class="form-control" value="0" min="0" step="0.01" onchange="calculateTotal()">
                        </div>
                        
                        <hr style="margin: 1rem 0;">
                        
                        <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: bold;">
                            <span>Total:</span>
                            <span id="total">$0.00</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="payment-method">Payment Method</label>
                        <select name="payment_method" id="payment-method" class="form-control" required>
                            <option value="cash">Cash</option>
                            <option value="credit">Credit Card</option>
                            <option value="debit">Debit Card</option>
                            <option value="gift_card">Gift Card</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes (Optional)</label>
                        <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                    </div>

                    <button type="submit" class="btn btn-success" style="width: 100%; margin-top: 1rem;">Complete Sale</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let cart = [];

function toggleItemSelect() {
    const type = document.getElementById('item-type').value;
    if (type === 'service') {
        document.getElementById('service-select').style.display = 'block';
        document.getElementById('service-label').style.display = 'block';
        document.getElementById('product-select').style.display = 'none';
        document.getElementById('product-label').style.display = 'none';
    } else {
        document.getElementById('service-select').style.display = 'none';
        document.getElementById('service-label').style.display = 'none';
        document.getElementById('product-select').style.display = 'block';
        document.getElementById('product-label').style.display = 'block';
    }
}

function addItem() {
    const type = document.getElementById('item-type').value;
    const select = type === 'service' ? 
        document.getElementById('service-select') : 
        document.getElementById('product-select');
    
    const option = select.options[select.selectedIndex];
    if (!option.value) return;

    const quantity = parseInt(document.getElementById('quantity').value);
    const item = {
        type: type,
        id: type === 'service' ? option.value : null,
        inventory_id: type === 'product' ? option.value : null,
        name: option.dataset.name,
        unit_price: parseFloat(option.dataset.price),
        quantity: quantity
    };

    cart.push(item);
    renderCart();
    select.value = '';
}

function removeItem(index) {
    cart.splice(index, 1);
    renderCart();
}

function renderCart() {
    const tbody = document.getElementById('cart-items');
    const container = document.getElementById('items-container');
    tbody.innerHTML = '';
    container.innerHTML = '';

    cart.forEach((item, index) => {
        const total = item.unit_price * item.quantity;
        
        tbody.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td><span class="badge badge-${item.type === 'service' ? 'info' : 'warning'}">${item.type}</span></td>
                <td>${item.quantity}</td>
                <td>$${item.unit_price.toFixed(2)}</td>
                <td>$${total.toFixed(2)}</td>
                <td><button type="button" class="btn btn-danger" onclick="removeItem(${index})" style="padding: 0.25rem 0.5rem;">×</button></td>
            </tr>
        `;

        container.innerHTML += `
            <input type="hidden" name="items[${index}][item_type]" value="${item.type}">
            ${item.id ? `<input type="hidden" name="items[${index}][id]" value="${item.id}">` : ''}
            ${item.inventory_id ? `<input type="hidden" name="items[${index}][inventory_id]" value="${item.inventory_id}">` : ''}
            <input type="hidden" name="items[${index}][name]" value="${item.name}">
            <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
            <input type="hidden" name="items[${index}][unit_price]" value="${item.unit_price}">
        `;
    });

    calculateTotal();
}

function calculateTotal() {
    let subtotal = cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
    const taxRate = parseFloat(document.getElementById('tax-rate').value) || 0;
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const taxAmount = (subtotal * taxRate) / 100;
    const total = subtotal + taxAmount - discount;

    document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('total').textContent = '$' + total.toFixed(2);
}
</script>
@endsection
