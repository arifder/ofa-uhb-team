<?php

namespace App\Livewire;

use App\Models\Fakultas;
use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class UsersCrud extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $role = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedRole(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'role']);
        $this->resetPage();
    }

    public function render()
    {
        $query = User::with(['fakultas', 'prodi', 'dosen'])->where('status', 'aktif');

        if ($this->role !== '') {
            $query->where('role', $this->role);
        }

        if ($this->search !== '') {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('dosen', function($qDosen) use ($search) {
                        $qDosen->where('nama_lengkap', 'like', "%{$search}%");
                    });
            });
        }

        $fakultasList = Fakultas::all();

        return view('livewire.users-crud', [
            'users' => $query->paginate(10)->withQueryString(),
            'fakultasList' => $fakultasList,
            'fstId' => $fakultasList->first(fn ($fakultas) => str_contains(strtolower($fakultas->nama_fakultas), 'sains'))?->id,
            'fisId' => $fakultasList->first(fn ($fakultas) => str_contains(strtolower($fakultas->nama_fakultas), 'sosial'))?->id,
        ]);
    }
}
