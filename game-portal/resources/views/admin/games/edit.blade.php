@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
           
            <div class="dashboard-header" style="margin-bottom: 2rem;">
                <div>
                    <h1 class="dashboard-title">
                        Edit <span class="gradient-text">Game</span>
                    </h1>
                    <p class="dashboard-subtitle">Update: {{ $game->title }}</p>
                </div>
            </div>

           
            <div class="admin-section">
                <div style="padding: 2rem;">
                    <form action="{{ route('admin.games.update', $game) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                      
                        <div class="mb-4">
                            <label for="title" class="form-label" style="color: rgba(255,255,255,0.7); font-weight: 500; margin-bottom: 0.5rem;">
                                Game Title *
                            </label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $game->title) }}" 
                                   required
                                   style="background: rgba(255,255,255,0.05); border: 1px solid rgba(96, 165, 250, 0.2); color: #fff; padding: 12px 16px; border-radius: 8px;">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="mb-4">
                            <label for="description" class="form-label" style="color: rgba(255,255,255,0.7); font-weight: 500; margin-bottom: 0.5rem;">
                                Description *
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="5" 
                                      required
                                      style="background: rgba(255,255,255,0.05); border: 1px solid rgba(96, 165, 250, 0.2); color: #fff; padding: 12px 16px; border-radius: 8px; resize: vertical;">{{ old('description', $game->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    
                        <div class="mb-4">
                            <label for="price" class="form-label" style="color: rgba(255,255,255,0.7); font-weight: 500; margin-bottom: 0.5rem;">
                                Price ($)
                            </label>
                            <input type="number" 
                                   class="form-control @error('price') is-invalid @enderror" 
                                   id="price" 
                                   name="price" 
                                   value="{{ old('price', $game->price) }}" 
                                   step="0.01"
                                   min="0"
                                   placeholder="0.00"
                                   style="background: rgba(255,255,255,0.05); border: 1px solid rgba(96, 165, 250, 0.2); color: #fff; padding: 12px 16px; border-radius: 8px;">
                            <small style="color: rgba(255,255,255,0.5); font-size: 0.85rem;">Leave empty or 0 for free games</small>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                       
                        @if($game->image)
                        <div class="mb-4">
                            <label class="form-label" style="color: rgba(255,255,255,0.7); font-weight: 500; margin-bottom: 0.5rem;">
                                Current Image
                            </label>
                            <div style="background: rgba(255,255,255,0.03); padding: 1rem; border-radius: 12px; border: 1px solid rgba(96, 165, 250, 0.2);">
                                <img src="{{ asset('storage/' . $game->image) }}" 
                                     alt="{{ $game->title }}" 
                                     style="max-width: 300px; border-radius: 8px; border: 2px solid rgba(96, 165, 250, 0.3);">
                            </div>
                        </div>
                        @endif

                        
                        <div class="mb-4">
                            <label for="image" class="form-label" style="color: rgba(255,255,255,0.7); font-weight: 500; margin-bottom: 0.5rem;">
                                Change Image (optional)
                            </label>
                            <input type="file" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*"
                                   style="background: rgba(255,255,255,0.05); border: 1px solid rgba(96, 165, 250, 0.2); color: #fff; padding: 12px 16px; border-radius: 8px;">
                            <small style="color: rgba(255,255,255,0.5); font-size: 0.85rem;">Max size: 10MB (jpg, png, gif, webp)</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <label style="color: rgba(255,255,255,0.6); font-size: 0.9rem; margin-bottom: 0.5rem;">New Preview:</label>
                                <img id="preview" src="" alt="Preview" style="max-width: 300px; border-radius: 12px; border: 2px solid rgba(96, 165, 250, 0.3);">
                            </div>
                        </div>

                       
                        <div class="mb-4">
                            <label class="form-label" style="color: rgba(255,255,255,0.7); font-weight: 500; margin-bottom: 1rem; display: block;">
                                Categories *
                            </label>
                            <div class="row g-3">
                                @foreach($categories as $category)
                                <div class="col-md-4">
                                    <label for="category{{ $category->id }}" class="category-checkbox-label">
                                        <input class="category-checkbox-input @error('categories') is-invalid @enderror" 
                                               type="checkbox" 
                                               name="categories[]" 
                                               value="{{ $category->id }}" 
                                               id="category{{ $category->id }}"
                                               {{ in_array($category->id, old('categories', $game->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <span class="category-checkbox-custom"></span>
                                        <span class="category-checkbox-text">{{ $category->name }}</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('categories')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                       
                        <div class="d-flex justify-content-between" style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(96, 165, 250, 0.1);">
                            <a href="{{ route('admin.games.index') }}" class="btn btn-secondary" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(96, 165, 250, 0.2); color: #fff; padding: 12px 24px; font-weight: 500;">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px 32px; font-weight: 600;">
                                <i class="fas fa-save"></i> Update Game
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection