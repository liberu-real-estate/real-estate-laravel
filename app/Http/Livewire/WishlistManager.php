<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class WishlistManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    protected $listeners = ['favoriteAdded' => '$refresh', 'favoriteRemoved' => '$refresh'];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function removeFavorite($propertyId)
    {
        $user = Auth::user();
        
        $favorite = Favorite::where('user_id', $user->id)
            ->where('property_id', $propertyId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            session()->flash('success', 'Property removed from wishlist successfully');
            $this->emit('favoriteRemoved');
        }
    }

    public function sortByColumn($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $user = Auth::user();
        
        $query = $user->favoriteProperties()
            ->with(['images', 'neighborhood', 'features', 'category']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('postal_code', 'like', '%' . $this->search . '%');
            });
        }

        // Apply sorting
        if ($this->sortBy === 'price') {
            $query->orderBy('price', $this->sortDirection);
        } elseif ($this->sortBy === 'title') {
            $query->orderBy('title', $this->sortDirection);
        } else {
            // Default: sort by when it was added to favorites
            $query->join('favorites', 'properties.id', '=', 'favorites.property_id')
                  ->where('favorites.user_id', $user->id)
                  ->orderBy('favorites.created_at', $this->sortDirection)
                  ->select('properties.*', 'favorites.created_at as favorited_at');
        }

        $favorites = $query->paginate(12);

        return view('livewire.wishlist-manager', [
            'favorites' => $favorites,
            'totalFavorites' => $user->favorites()->count(),
        ]);
    }
}
