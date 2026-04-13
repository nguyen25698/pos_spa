@extends('layouts.app')

@section('title', 'POS Terminal - Nail Spa POS')

@section('content')
<h2 style="margin-bottom: 1.5rem;">POS Terminal</h2>

<form action="{{ route('pos.checkout') }}" method="POST" id="checkoutForm">
    @csrf
    
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <div class="card">
            <h3 style="margin-bottom: 1rem;">Select Services</h3>
            
            <div class="form-group">
                <label>Select Customer (Optional)</label>
                <select name="customer_id" id="customer_id">
                    <option value="">Walk-in Customer</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label>Select Staff (Optional)</label>
                <select name="staff_id" id="staff_id">
                    <option value="">Select Technician</option>
                    @foreach($staff as $member)
                    <option value="{{ $member->id }}">{{ $member->first_name }} {{ $member->last_name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div id="servicesContainer">
                <table style="margin-top: 1rem;">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="servicesBody">
                    </tbody>
                </table>
                
                <button type="button" class="btn btn-primary" onclick="addServiceRow()" style="margin-top: 1rem;">+ Add Service</button>
            </div>
            
            <div class="form-group" style="margin-top: 1rem;">
                <label>Notes</label>
                <textarea name="notes" rows="2" placeholder="Additional notes..."></textarea>
            </div>
        </div>
        
        <div class="card">
            <h3 style="margin-bottom: 1rem;">Order Summary</h3>
            
            <div style="margin-bottom: 1rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Subtotal:</span>
                    <span id="subtotalDisplay">$0.00</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Tax (8%):</span>
                    <span id="taxDisplay">$0.00</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Discount:</span>
                    <span id="discountDisplay">$0.00</span>
                </div>
                <hr style="margin: 1rem 0;">
                <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: bold;">
                    <span>Total:</span>
                    <span id="totalDisplay">$0.00</span>
                </div>
            </div>
            
            <div class="form-group">
                <label>Payment Method</label>
                <select name="payment_method" required>
                    <option value="cash">Cash</option>
                    <option value="card">Credit/Debit Card</option>
                    <option value="mobile">Mobile Payment</option>
                </select>
            </div>
            
            <input type="hidden" name="subtotal" id="subtotalInput" value="0">
            <input type="hidden" name="tax_amount" id="taxInput" value="0">
            <input type="hidden" name="discount_amount" id="discountInput" value="0">
            <input type="hidden" name="total_amount" id="totalInput" value="0">
            <input type="hidden" name="items" id="itemsInput" value="">
            
            <button type="submit" class="btn btn-success" style="width: 100%; margin-top: 1rem;">Complete Sale</button>
        </div>
    </div>
</form>

<script>
const services = @json($services);
let selectedItems = [];

function addServiceRow() {
    const tbody = document.getElementById('servicesBody');
    const row = document.createElement('tr');
    
    let options = '<option value="">Select Service</option>';
    services.forEach(service => {
        options += `<option value="${service.id}" data-price="${service.price}">${service.name} - $${service.price.toFixed(2)}</option>`;
    });
    
    row.innerHTML = `
        <td><select class="service-select" onchange="updateItem(this)">${options}</select></td>
        <td><span class="item-price">$0.00</span></td>
        <td><input type="number" min="1" value="1" class="item-quantity" onchange="updateItem(this)" style="width: 60px;"></td>
        <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
    `;
    
    tbody.appendChild(row);
}

function updateItem(element) {
    const row = element.closest('tr');
    const select = row.querySelector('.service-select');
    const quantity = row.querySelector('.item-quantity');
    const priceSpan = row.querySelector('.item-price');
    
    if (select.value) {
        const option = select.options[select.selectedIndex];
        const price = parseFloat(option.dataset.price);
        const qty = parseInt(quantity.value) || 1;
        priceSpan.textContent = '$' + (price * qty).toFixed(2);
    } else {
        priceSpan.textContent = '$0.00';
    }
    
    calculateTotal();
}

function removeRow(button) {
    button.closest('tr').remove();
    calculateTotal();
}

function calculateTotal() {
    let subtotal = 0;
    selectedItems = [];
    
    document.querySelectorAll('#servicesBody tr').forEach(row => {
        const select = row.querySelector('.service-select');
        const quantity = row.querySelector('.item-quantity');
        
        if (select.value) {
            const price = parseFloat(select.options[select.selectedIndex].dataset.price);
            const qty = parseInt(quantity.value) || 1;
            const total = price * qty;
            subtotal += total;
            
            selectedItems.push({
                service_id: select.value,
                quantity: qty,
                price: price
            });
        }
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

// Initialize with one service row
addServiceRow();
</script>
@endsection
