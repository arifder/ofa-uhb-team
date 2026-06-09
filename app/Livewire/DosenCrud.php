<?php

namespace App\Livewire;

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Prodi;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class DosenCrud extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url(as: 'fakultas_id')]
    public string $fakultasId = '';

    #[Url(as: 'prodi_id')]
    public string $prodiId = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFakultasId(): void
    {
        $this->resetPage();
    }

    public function updatedProdiId(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'fakultasId', 'prodiId']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Dosen::with(['user', 'prodi.fakultas']);

        if ($this->fakultasId !== '') {
            $query->whereHas('prodi', function ($q) {
                $q->where('fakultas_id', $this->fakultasId);
            });
        }

        if ($this->prodiId !== '') {
            $query->where('prodi_id', $this->prodiId);
        }

        if ($this->search !== '') {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nidn', 'like', "%{$search}%");
            });
        }

        return view('livewire.dosen-crud', [
            'dosens' => $query->paginate(10)->withQueryString(),
            'fakultasList' => Fakultas::all(),
            'prodiList' => Prodi::orderBy('nama_prodi')->get(),
        ]);
    }
}
