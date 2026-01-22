@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            
            <div class="dashboard-header" style="margin-bottom: 2rem;">
                <div>
                    <h1 class="dashboard-title">
                        Edit <span class="gradient-text">Category</span>
                    </h1>
                    <p class="dashboard-subtitle">Update: {{ $category->name }}</p>
                </div>
            </div>

            
            <div class="admin-section">
                <div style="padding: 2rem;">
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        
                        <div class="mb-4">
                            <label for="name" class="form-label" style="color: rgba(255,255,255,0.7); font-weight: 500; margin-bottom: 0.5rem;">
                                Category Name *
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $category->name) }}" 
                                   required
                                   style="background: rgba(255,255,255,0.05); border: 1px solid rgba(96, 165, 250, 0.2); color: #fff; padding: 12px 16px; border-radius: 8px;">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                       
                        <div class="mb-4" style="background: rgba(96, 165, 250, 0.05); border: 1px solid rgba(96, 165, 250, 0.2); border-radius: 8px; padding: 1rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <small style="color: rgba(255,255,255,0.5); display: block; margin-bottom: 4px;">Games using this category</small>
                                    <span style="color: #60a5fa; font-weight: 600; font-size: 1.2rem;">{{ $category->games_count ?? 0 }}</span>
                                </div>
                                <div>
                                    <small style="color: rgba(255,255,255,0.5); display: block; margin-bottom: 4px;">Created</small>
                                    <span style="color: rgba(255,255,255,0.8); font-weight: 500;">{{ $category->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                      
                        <div class="d-flex justify-content-between" style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(96, 165, 250, 0.1);">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(96, 165, 250, 0.2); color: #fff; padding: 12px 24px; font-weight: 500;">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px 32px; font-weight: 600;">
                                <i class="fas fa-save"></i> Update Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection