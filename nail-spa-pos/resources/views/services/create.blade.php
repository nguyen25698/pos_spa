@extends('layouts.app')

@section('title', 'Add Service - Nail Spa POS')

@section('content')
<div class="card" style="max-width: 600px;">
    <h2>Add New Service</h2>
    
    <form action="{{ route('services.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="name">Service Name *</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label for="category">Category *</label>
            <select name="category" id="category" class="form-control" required>
                <option value="Manicure">Manicure</option>
                <option value="Pedicure">Pedicure</option>
                <option value="Nail Art">Nail Art</option>
                <option value="Treatment">Treatment</option>
                <option value="Waxing">Waxing</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label for="price">Price ($) *</label>
                <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <label for="duration_minutes">Duration (minutes) *</label>
                <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" min="1" required>
            </div>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" value="1" checked> Active
            </label>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-success">Save Service</button>
            <a href="{{ route('services.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
