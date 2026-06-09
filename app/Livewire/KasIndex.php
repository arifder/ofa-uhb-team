<?php

namespace App\Livewire;

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\KasTransaction;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class KasIndex extends Component
{
    use WithPagination;

    public string $jenis = 'masuk';

    #[Url]
    public string $search = '';

    #[Url(as: 'fakultas_id')]
    public string $fakultasId = '';

    #[Url(as: 'filter_tipe')]
    public string $filterTipe = 'hari';

    #[Url(as: 'tanggal_awal')]
    public string $tanggalAwal = '';

    #[Url(as: 'tanggal_akhir')]
    public string $tanggalAkhir = '';

    #[Url]
    public string $bulan = '';

    #[Url]
    public string $tahun = '';

    public function mount(string $jenis = 'masuk'): void
    {
        $this->jenis = in_array($jenis, ['masuk', 'keluar'], true) ? $jenis : 'masuk';
    }

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
        $this->reset(['search', 'fakultasId', 'tanggalAwal', 'tanggalAkhir', 'bulan', 'tahun']);
        $this->filterTipe = 'hari';
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();
        $query = KasTransaction::with(['fakultas', 'user', 'dosen'])
            ->where('jenis', $this->jenis);

        if ($user && in_array($user->role, ['admin_fst', 'admin_fis'], true)) {
            $query->where('fakultas_id', $user->fakultas_id);
        } elseif ($user && $user->role === 'dosen') {
            $dosen = Dosen::where('user_id', $user->id)->first();
            $query->when($dosen, fn ($q) => $q->where('dosen_id', $dosen->id), fn ($q) => $q->whereRaw('1=0'));
        }

        if ($this->search !== '') {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('keterangan', 'like', "%{$search}%");
            });
        }

        if ($this->fakultasId !== '') {
            $query->where('fakultas_id', $this->fakultasId);
        }

        if ($this->filterTipe === 'hari') {
            if ($this->tanggalAwal !== '') {
                $query->whereDate('tanggal', '>=', $this->tanggalAwal);
            }
            if ($this->tanggalAkhir !== '') {
                $query->whereDate('tanggal', '<=', $this->tanggalAkhir);
            }
        } elseif ($this->filterTipe === 'bulan') {
            if ($this->bulan !== '') {
                $query->whereMonth('tanggal', $this->bulan);
            }
            if ($this->tahun !== '') {
                $query->whereYear('tanggal', $this->tahun);
            }
        } elseif ($this->filterTipe === 'tahun' && $this->tahun !== '') {
            $query->whereYear('tanggal', $this->tahun);
        }

        $fakultasList = ($user && in_array($user->role, ['super_admin', 'kepala_unit'], true))
            ? Fakultas::all()
            : Fakultas::where('id', $user?->fakultas_id)->get();

        return view('livewire.kas-index', [
            'kasList' => $query->latest('tanggal')->paginate(10)->withQueryString(),
            'fakultasList' => $fakultasList,
            'title' => $this->jenis === 'masuk' ? 'Masuk' : 'Keluar',
        ]);
    }
}
