<?php

namespace App\Http\Controllers\Master\Barang;

use App\Exports\GenerateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Barang\VendorRequest;
use App\Models\Master\Barang\Vendor;
use App\Models\Master\Geo\City;
use App\Models\Master\Geo\Province;
use App\Models\Master\RekeningBank\RekeningBank;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    protected $module = 'vendor';
    protected $routes = 'master.vendor';
    protected $views  = 'master.vendor_barang';
    protected $perms = 'master';
    private $datas;

    public function __construct()
    {
        $this->prepare(
            [
                'module' => $this->module,
                'routes' => $this->routes,
                'views' => $this->views,
                'perms' => $this->perms,
                'permission' => $this->perms . '.view',
                'title' => 'Vendor',
                'breadcrumb' => [
                    'Konsol Admin' => route($this->routes . '.index'),
                    'Parameter' => route($this->routes . '.index'),
                    'Vendor' => route($this->routes . '.index'),
                ]
            ]
        );
        $this->datas['provinces'] = Province::select("id", "name")->get();
        $this->datas['cities'] = City::select("id", "name")->get();
    }

    public function index()
    {
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:name|label:Vendor|className:text-left'),
                    // $this->makeColumn('name:address|label:Alamat|className:text-left'),
                    // $this->makeColumn('name:province|label:Provinsi|className:text-left'),
                    // $this->makeColumn('name:city|label:Kota|className:text-left'),
                    // $this->makeColumn('name:telp|label:Telepon|className:text-left'),
                    // $this->makeColumn('name:email|label:Email|className:text-left'),
                    $this->makeColumn('name:contact_person|label:Contact Person|className:text-center'),
                    $this->makeColumn('name:bank|label:Bank, No Rekening, Nama Pemilik|className:text-center'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action|className:text-center'),
                ],
            ],
        ]);
        return $this->render($this->views . '.index');
    }

    public function grid()
    {
        $user = auth()->user();
        $records = Vendor::grid()->filters()->dtGet();

        return \DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'name',
                function ($record) {
                    return $record->name;
                }
            )
            ->addColumn(
                'telp',
                function ($record) {
                    return $record->telp;
                }
            )
            ->addColumn(
                'address',
                function ($record) {
                    return  $record->address;
                }
            )
            ->addColumn(
                'email',
                function ($record) {
                    return  $record->email;
                }
            )
            ->addColumn(
                'contact_person',
                function ($record) {
                    return  $record->contact_person;
                }
            )
            ->addColumn(
                'bank',
                function ($record) {
                    $label = '';
                    $rekening = RekeningBank::where('vendor_id', $record->id)->get();
                    // menampilkan RekeningBank yang statusnya active
                    $rekening = $rekening->filter(function ($item) {
                        return $item->status == "active";
                    });

                    if ($rekening) {
                        foreach ($rekening as $r) {
                            if ($label) $label .= "<br>";
                            $label .= $r->getBankName() . ' <br>' . $r->no_rekening . '<br>' . $r->nama_pemilik;
                        }
                    }

                    return $label;
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn(
                'action',
                function ($record) use ($user) {
                    $actions = [];

                    if ($record->checkAction('show', $this->perms)) {
                        $actions[] = 'type:show|id:' . $record->id;
                    }
                    if ($record->checkAction('edit', $this->perms)) {
                        $actions[] = 'type:edit|id:' . $record->id;
                    }
                    if ($record->checkAction('delete', $this->perms)) {
                        $actions[] = [
                            'type' => 'delete',
                            'id' => $record->id,
                            'attrs' => 'data-confirm-text="' . __('Hapus Parameter Vendor') . ' ' . $record->name . '?"',
                        ];
                    }
                    return $this->makeButtonDropdown($actions);
                }
            )
            ->addColumn(

                'status',
                function ($record) {
                    return $record->status;
                }
            )
            ->rawColumns(['contact_person', 'status', 'bank', 'email', 'telp', 'address', 'name', 'action', 'updated_by'])
            ->make(true);
    }

    public function create()
    {
        $data = $this->datas;
        $page_action = "create";
        $provinces = Province::select("id", "name")->get();
        $record = new Vendor();
        $this->pushBreadcrumb(['Tambah' => route($this->routes . '.create', $record)]);
        return $this->render($this->views . '.create', compact('page_action', 'data', 'provinces'));
    }

    public function store(VendorRequest $request)
    {
        $record = new Vendor;
        return $record->handleStoreOrUpdate($request);
    }

    public function show(Vendor $record)
    {
        $page_action = "show";
        return $this->render($this->views . '.show', compact('record', 'page_action'));
    }

    public function edit(Vendor $record)
    {

        $this->pushBreadcrumb(['Detil' => route($this->routes . '.edit', $record)]);
        $province = Province::where('id', $record->ref_province_id)->first();
        $city = City::where('id', $record->ref_city_id)->first();
        $page_action = "edit";
        return $this->render($this->views . '.edit', compact('record', 'province', 'city', 'page_action'));
    }

    public function update(VendorRequest $request, Vendor $record)
    {
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(Vendor $record)
    {
        return $record->handleDestroy();
    }

    public function import()
    {
        if (request()->get('download') == 'template') {
            return $this->template();
        }
        return $this->render($this->views . '.import');
    }

    public function addBank(Request $request)
    {
        $latest_id = $request->latest_id;
        $id = $latest_id + 1;
        return $this->render($this->views . '.subs.new_bank', compact('id'));
    }

    public function template()
    {
        $fileName = date('Y-m-d') . ' Template Import Data ' . $this->prepared('title') . '.xlsx';
        $view = $this->views . '.template';
        $data = [];
        return \Excel::download(new GenerateExport($view, $data), $fileName);
    }

    public function importSave(Request $request)
    {
        $request->validate([
            'uploads.uploaded' => 'required',
            'uploads.temp_files_ids.*' => 'required',
        ], [], [
            'uploads.uploaded' => 'File',
            'uploads.temp_files_ids.*' => 'File',
        ]);

        $record = new Vendor;
        return $record->handleImport($request);
    }
}
