<?php

namespace App\Livewire;

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\KasTagihan;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TagihanIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url(as: 'fakultas_id')]
    public string $fakultasId = '';

    #[Url]
    public string $status = '';

    #[Url]
    public string $bulan = '';

    #[Url]
    public string $tahun = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updated($property): void
    {
        if ($property !== 'search') {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'fakultasId', 'status', 'bulan', 'tahun']);
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();
        $query = KasTagihan::with(['dosen', 'dosen.prodi', 'fakultas']);

        if ($user && in_array($user->role, ['admin_fst', 'admin_fis'], true)) {
            $query->where('fakultas_id', $user->fakultas_id);
        } elseif ($user && $user->role === 'dosen') {
            $dosen = Dosen::where('user_id', $user->id)->first();
            $query->when($dosen, fn ($q) => $q->where('dosen_id', $dosen->id), fn ($q) => $q->whereRaw('1=0'));
        }

        if ($this->search !== '') {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('dosen', fn ($q) => $q->where('nama_lengkap', 'like', "%{$search}%"));
            });
        }

        if ($this->fakultasId !== '') {
            $query->where('fakultas_id', $this->fakultasId);
        }
        if ($this->status !== '') {
            $query->where('status', $this->status);
        }
        if ($this->bulan !== '') {
            $query->where('bulan', $this->bulan);
        }
        if ($this->tahun !== '') {
            $query->where('tahun', $this->tahun);
        }

        $totalQuery = clone $query;
        $totalTagihan = (clone $totalQuery)->sum('jumlah');
        $totalDibayar = (clone $totalQuery)->sum('dibayar_amount');

        $fakultasList = ($user && in_array($user->role, ['super_admin', 'kepala_unit'], true))
            ? Fakultas::all()
            : Fakultas::where('id', $user?->fakultas_id)->get();

        return view('livewire.tagihan-index', [
            'tagihanList' => $query->latest()->paginate(10)->withQueryString(),
            'fakultasList' => $fakultasList,
            'totalTagihan' => $totalTagihan,
            'totalDibayar' => $totalDibayar,
            'totalSisa' => $totalTagihan - $totalDibayar,
        ]);
    }
}
