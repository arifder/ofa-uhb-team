<?php

namespace App\Livewire;

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Notulensi;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class NotulensiIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url(as: 'fakultas_id')]
    public string $fakultasId = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFakultasId(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'fakultasId']);
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();
        $query = Notulensi::with(['fakultas', 'user', 'pesertaRapat', 'dosens']);

        if ($user && in_array($user->role, ['admin_fst', 'admin_fis'], true)) {
            $query->fakultas($user->fakultas_id);
        } elseif ($user && $user->role === 'dosen') {
            $dosen = Dosen::where('user_id', $user->id)->first();
            if ($dosen) {
                $query->whereHas('pesertaRapat', fn ($q) => $q->where('dosen_id', $dosen->id));
            } else {
                $query->whereRaw('1=0');
            }
        }

        if ($this->search !== '') {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('nomor_bap', 'like', "%{$search}%");
            });
        }

        if ($this->fakultasId !== '') {
            $query->where('fakultas_id', $this->fakultasId);
        }

        return view('livewire.notulensi-index', [
            'notulensiList' => $query->latest()->paginate(10)->withQueryString(),
            'fakultasList' => Fakultas::all(),
        ]);
    }
}
