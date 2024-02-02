<?php

namespace App\Http\Controllers;

use App\Models\Aktiva\Aktiva;
use App\Models\Aktiva\PembelianAktivaDetail;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Globals\Notification;
use App\Models\Globals\TempFiles;
use App\Models\Master\Bank\BankAccount;
use App\Models\Master\Barang\Vendor;
use App\Models\Master\Geo\City;
use App\Models\Master\Geo\Province;
use App\Models\Master\Jurnal\COA;
use App\Models\Master\MasaPenggunaan\MasaPenggunaan;
use App\Models\Master\MataAnggaran\MataAnggaran;
use App\Models\Master\Org\Kelompok;
use App\Models\Master\Org\OrgStruct;
use App\Models\Master\Org\Position;
use Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AjaxController extends Controller
{
    public function saveTempFiles(Request $request)
    {
        $this->beginTransaction();
        try {
            if ($file = $request->file('file')) {
                $file_path = str_replace('.' . $file->extension(), '', $file->hashName());
                $file_path .= '.' . $file->getClientOriginalExtension();

                $temp = new TempFiles;
                $temp->file_name = $file->getClientOriginalName();
                $temp->file_path = $file->storeAs('temp-files', $file_path, 'public');
                // $temp->file_type = $file->extension();
                $temp->file_size = $file->getSize();
                $temp->flag = $request->flag;
                $temp->save();
                return $this->commit([
                    'file' => TempFiles::find($temp->id)
                ]);
            }
            return $this->rollback(['message' => 'File not found']);
        } catch (\Exception $e) {
            return $this->rollback(['error' => $e->getMessage()]);
        }
    }

    public function userNotification()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->simplePaginate(25);
        return $this->render('layouts.base.notification', compact('notifications'));
    }

    public function userNotificationRead(Notification $notification)
    {
        auth()->user()
            ->notifications()
            ->updateExistingPivot($notification, array('readed_at' => now()), false);
        return redirect($notification->full_url);
    }

    public function selectAktiva(Request $request)
    {
        $with_where_has_fn = function ($q) use ($request) {
            $q
                ->orderBy('nama_aktiva');
        };
        $items = Aktiva::with(
            [
                'pembelianAktivaDetail'  => $with_where_has_fn
            ]
        )
            ->whereHas('pembelianAktivaDetail', $with_where_has_fn)
            ->when(
                $keyword = $request->keyword,
                function ($q) use ($keyword) {
                    $q
                        ->where('code', 'LIKE', '%' . $keyword . '%')
                        ->orWhereHas(
                            'pembelianAktivaDetail',
                            function ($q) use ($keyword) {
                                $q->where('nama_aktiva', 'LIKE', '%' . $keyword . '%');
                            }
                        );
                }
            )
            ->when(
                $struct_id = $request->struct_id,
                function ($q) use ($struct_id) {
                    $q->where('struct_id', $struct_id);
                }
            )
            ->when(
                $pemeriksaan_id = $request->pemeriksaan_id,
                function ($q) use ($request, $pemeriksaan_id) {
                    $q->whereDoesntHave(
                        'pemeriksaanDetail',
                        function ($q) use ($pemeriksaan_id) {
                            $q->where('pemeriksaan_id', $pemeriksaan_id);
                        }
                    );
                },
                function ($q) {
                    $q
                        ->whereDoesntHave('penghapusanAktivaDetail')
                        ->whereDoesntHave('penjualanAktivaDetail')
                        ->whereNull('mutasi_aktiva_detail_id');
                }
            )
            ->paginate();
        // dd(json_decode($items));

        $results = [];
        $more = $items->hasMorePages();
        foreach ($items as $item) {
            $results[] = ['id' => $item->id, 'text' => $item->code . ' - ' . $item->pembelianAktivaDetail->nama_aktiva];
        }
        return response()->json(compact('results', 'more'));
    }

    public function getAktivaById(Request $request)
    {
        $item = Aktiva::with(
            [
                'pembelianAktivaDetail'  => function ($q) {
                    $q->with('vendor');
                }
            ]
        )
            ->find($request->id);
        return $item;
    }

    public function selectBankAccount($search, Request $request)
    {
        $items = BankAccount::keywordBy('number')->orderBy('number');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            case 'find':
                return $items->with('owner')->find($request->id);
                break;

            case 'byOwner':
                $owners = json_decode($request->owner);
                $items = $items->whereIn('user_id', $owners);
                break;

            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->paginate();
        $results = [];
        $more = $items->hasMorePages();
        foreach ($items as $item) {
            $results[] = ['id' => $item->id, 'text' => $item->number . ' (' . ($item->owner->name ?? '') . ')'];
        }
        return response()->json(compact('results', 'more'));
    }

    public function getBankAccountById(Request $request)
    {
        $item = BankAccount::with('owner')->find($request->id);
        return $item;
    }

    public function selectBank($search, Request $request)
    {
        $items = Bank::keywordBy('bank')->where('status', '2')->orderBy('bank');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->paginate();
        return $this->responseSelect2($items, 'bank', 'id');
    }


    public function selectCOA(Request $request)
    {
        $items = COA::where('status', '2');
        $items = $items->paginate();
        $results = [];
        $more = $items->hasMorePages();
        foreach ($items as $item) {
            $results[] = ['id' => $item->id, 'text' => $item->kode_akun . ' - ' . $item->nama_akun];
        }
        return response()->json(compact('results', 'more'));
    }

    public function selectVendor(Request $request)
    {
        $items = Vendor::where('status', '2');
        $items = $items->paginate();
        $results = [];
        $more = $items->hasMorePages();
        foreach ($items as $item) {
            $results[] = ['id' => $item->id, 'text' => $item->name];
        }
        return response()->json(compact('results', 'more'));
    }

    public function selectMataAnggaran(Request $request)
    {
        $items = MataAnggaran::all();
        $items = $items->paginate();
        $results = [];
        $more = $items->hasMorePages();
        foreach ($items as $item) {
            $results[] = ['id' => $item->id, 'text' => $item->nama];
        }
        return response()->json(compact('results', 'more'));
    }

    public function selectRole($search, Request $request)
    {
        $items = Role::keywordBy('name')->orderBy('name');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            case 'approver':
                $perms = str_replace('_', '.', $request->perms) . '.approve';
                $items = $items->whereHas('permissions', function ($q) use ($perms) {
                    $q->where('name', $perms);
                });
                break;

            default:
                $items = $items->whereNull('id');
                break;
        }
        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectMasaPenggunaan(Request $request)
    {
        $items = MasaPenggunaan::where('status', '2')->orderBy('masa_penggunaan');
        $items = $items->paginate();
        return $this->responseSelect2($items, 'masa_penggunaan', 'masa_penggunaan');
    }

    public function selectStruct($search, Request $request)
    {
        $items = OrgStruct::keywordBy('name')->orderBy('level')->orderBy('name');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            case 'parent_boc':
                $items = $items->whereIn('level', ['root']);
                break;
            case 'parent_bod':
                $items = $items->whereIn('level', ['root', 'bod']);
                break;
            case 'parent_division':
                $items = $items->whereIn('level', ['bod']);
                break;
            case 'parent_department':
                $items = $items->whereIn('level', ['division']);
                break;
            case 'parent_branch':
                $items = $items->whereIn('level', ['bod']);
                break;
            case 'parent_subbranch':
                $items = $items->whereIn('level', ['branch']);
                break;
            case 'parent_cash':
                $items = $items->whereIn('level', ['branch', 'subbranch']);
                break;
            case 'parent_payment':
                $items = $items->whereIn('level', ['branch', 'subbranch', 'cash']);
                break;
            case 'parent_group':
                $items = $items->whereIn('level', ['division']);
                break;
            case 'parent_position':
                $items = $items->whereNotIn('level', ['root', 'group']);
                break;
            case 'object_audit':
                $items = $items->whereIn('level', ['division', 'department', 'branch', 'group'])
                    ->where('level', $request->object_type);
                break;

            default:
                $items = $items->whereNull('id');
                break;
        }
        $results = [];
        $more = false;
        $items = $items->get();

        $levels = ['root', 'boc', 'bod', 'division', 'department', 'branch', 'subbranch'];
        $i = 0;
        foreach ($levels as $level) {
            if ($items->where('level', $level)->count()) {
                foreach ($items->where('level', $level) as $item) {
                    $results[$i]['text'] = strtoupper($item->show_level);
                    $results[$i]['children'][] = ['id' => $item->id, 'text' => $item->name];
                }
                $i++;
            }
        }
        return response()->json(compact('results', 'more'));
    }

    public function selectPembelianAktiva(Request $request)
    {
        $items = PembelianAktivaDetail::keywordBy('nama_aktiva')
            ->with('struct', 'vendor')
            ->when(
                $struct_id = $request->struct_id,
                function ($q) use ($struct_id) {
                    $q->where('struct_id', $struct_id);
                }
            )
            ->orderBy('nama_aktiva')
            ->paginate();

        $results = [];
        $more = $items->hasMorePages();
        foreach ($items as $item) {
            $results[] = ['id' => $item->id, 'text' => $item->nama_aktiva];
        }
        return response()->json(compact('results', 'more'));
    }

    public function getSelectPembelianAktivaById(Request $request)
    {
        $item = PembelianAktivaDetail::with('struct', 'vendor')
            ->whereHas('vendor')
            ->find($request->id);
        return $item;
    }

    public function selectPosition($search, Request $request)
    {
        $items = Position::keywordBy('name')
            ->orderBy('name')
            ->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectKelompok($search, Request $request)
    {
        $items = Kelompok::keywordBy('name')
            ->orderBy('name')
            ->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectUser($search, Request $request)
    {
        $items = User::keywordBy('name')
            ->whereHas('position')
            ->orderBy('name')
            ->paginate();

        $results = [];
        $more = $items->hasMorePages();
        foreach ($items as $item) {
            $results[] = ['id' => $item->id, 'text' => $item->name . ' (' . ($item->position->name ?? '') . ')'];
        }
        return response()->json(compact('results', 'more'));
    }

    public function provinceOptions($search)
    {
        $items = Province::keywordBy('name')->orderBy('name');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            default:
                $items = $items->whereNull('id');
                break;
        }
        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectProvince($search, Request $request)
    {
        $items = Province::keywordBy('name')
            ->orderBy('name')
            ->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function cityOptions(Request $request)
    {
        return City::when(
            $province_id = $request->province_id,
            function ($q) use ($province_id) {
                $q->where('province_id', $province_id);
            }
        )
            ->orderBy('name', 'ASC')
            ->get();
    }

    public function cityOptionsRoot(Request $request)
    {
        $items = City::when(
            $province_id = $request->province_id,
            function ($q) use ($province_id) {
                $q->where('province_id', $province_id);
            }
        )
            ->orderBy('name', 'ASC')
            ->get();

        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }

    public function selectCity($search, Request $request)
    {
        Log::info("search", [$search]);
        Log::info("request", [$request->all()]);
        $items = City::keywordBy('name')->orderBy('name');
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            case 'province':
                Log::info("province", [$request->province_id]);
                $items = $items->where('province_id', $request->province_id);
                break;
            default:
                $items = $items->whereNull('id');
                break;
        }

        $items = $items->orderBy('name', 'ASC')->get();
        Log::info("items", [$items]);
        $results = [];
        foreach ($items as $item) {
            $results[] = ['id' => $item->id, 'text' => $item->name];
        }
        return response()->json(compact('results'));
    }
}
