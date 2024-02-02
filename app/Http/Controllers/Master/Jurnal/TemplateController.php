<?php

namespace App\Http\Controllers\Master\Jurnal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Jurnal\EntryRequest;
use App\Http\Requests\Master\Jurnal\TemplateRequest;
use App\Models\Globals\Menu;
use App\Models\Master\Jurnal\COA;
use App\Models\Master\Jurnal\Entry;
use App\Models\Master\Jurnal\Template;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TemplateController extends Controller
{
    protected $module = 'master.template';
    protected $routes = 'master.template';
    protected $views  = 'master.jurnal.template';
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
                'title' => 'Template',
                'breadcrumb' => [
                    'Konsol Admin' => route($this->routes . '.index'),
                    'Parameter' => route($this->routes . '.index'),
                    'Jurnal' => route($this->routes . '.index'),
                    'Template' => route($this->routes . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $user = auth()->user();
        $records = Template::leftjoin('jurnal_entries','jurnal_entries.id_template', '=' , 'jurnal_template.id')->grid()->filters()->dtGet()->select('jurnal_template.*')->distinct('jurnal_template.id');
        return DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'kategori',
                function ($record) {
                    return $record->kategori;
                }
            )
            ->addColumn(
                'nama_template',
                function ($record) {
                    return ucwords($record->nama_template);
                }
            )
            ->addColumn(
                'deskripsi',
                function ($record) {
                    return '<p class="text-left my-auto" style="display: -webkit-box; -webkit-line-clamp: 3;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;">'.($record->deskripsi ? $record->deskripsi : '-') . '</p>';
                }
            )
            ->addColumn(
                'updated_by',
                function ($record) {
                    return $record->createdByRaw();
                }
            )
            ->addColumn(
                'status',
                function ($record) {
                    return $record->status;
                }
            )
            ->addColumn('action', function ($record) use ($user) {
                $actions = [];
                if ($record->checkAction('show', $this->perms)) {
                    $actions[] = 'type:show|id:' . $record->id . '|page:true';
                }
                if ($record->checkAction('edit', $this->perms)) {
                    $actions[] = 'type:edit|id:' . $record->id . '|page:true|label:Detil';
                } 
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(
                [
                    'kategori',
                    'nama_template',
                    'deskripsi',
                    'status',
                    'updated_by',
                    'action'
                ]
            )
            ->make(true);
    }

    public function index()
    {
        $data["baseContentReplace"] = true;
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:kategori|label:Kategori|className:text-left'),
                    $this->makeColumn('name:nama_template|label:Nama Template|className:text-left'),
                    $this->makeColumn('name:deskripsi|label:Deskripsi|width:200px'),
                    $this->makeColumn('name:updated_by'),
                    $this->makeColumn('name:action'),
                ],
            ],
        ]);

        return $this->render($this->views . '.index', $data);
    }

    public function create(){
        $page_action = "create";
        return $this->render($this->views . '.detail', compact("page_action"));
    }

    public function show(Template $record){
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:kode_akun|label:Kode Akun|className:text-left'),
                    $this->makeColumn('name:nama_akun|label:Nama Akun|className:text-left'),
                    $this->makeColumn('name:tipe_akun|label:Tipe Akun|className:text-center'),
                    $this->makeColumn('name:jenis|label:Jenis|className:text-center'),
                ],
                'url' => route($this->routes . '.entry', $record->id),
            ],
            'breadcrumb' => [
                'Parameter' => route($this->routes . '.index'),
                'Jurnal' => route($this->routes . '.index'),
                'Template' => route($this->routes . '.index'),
                'Lihat' => route($this->routes . '.show', $record->id),
            ]
        ]);
        $page_action = "show";
        return $this->render($this->views . '.detail', compact("page_action", "record"));
    }
    public function store(TemplateRequest $request){
        $record = new Template;
        return $record->handleStoreOrUpdate($request);
    }

    public function edit(Template $record){
        $this->prepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num'),
                    $this->makeColumn('name:kode_akun|label:Kode Akun|className:text-left'),
                    $this->makeColumn('name:nama_akun|label:Nama Akun|className:text-left'),
                    $this->makeColumn('name:tipe_akun|label:Tipe Akun|className:text-center'),
                    $this->makeColumn('name:jenis|label:Jenis|className:text-center'),
                    $this->makeColumn('name:action'),
                ],
                'url' => route($this->routes . '.entry', $record->id),
            ],
            'title' => 'Template',
            'breadcrumb' => [
                'Parameter' => route($this->routes . '.index'),
                'Jurnal' => route($this->routes . '.index'),
                'Template' => route($this->routes . '.index'),
                'Detil' => route($this->routes . '.edit', $record->id),
            ]
        ]);
        $urlAdd = route($this->routes . '.entry.add', $record->id);
        $page_action = "edit";
        return $this->render($this->views . '.detail', compact("page_action", "record", "urlAdd"));
    }

    public function entry($id){
        $user = auth()->user();
        $records = Entry::where('id_template', $id);

        return DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'kode_akun',
                function ($record) {
                    return $record->getKodeAkun();
                }
            )
            ->addColumn(
                'nama_akun',
                function ($record) {
                    return $record->getNamaAkun();
                }
            )
            ->addColumn(
                'tipe_akun',
                function ($record) {
                    return ucwords($record->getTipeAkun());
                }
            )
            ->addColumn(
                'jenis',
                function ($record) {
                    return ucwords($record->jenis);
                }
            )
            ->addColumn('action', function ($record) use ($user) {
                $actions = [];
                $actions[] = [
                    'type' => 'delete',
                    'id' => $record->id,
                    'attrs' => 'data-confirm-text="' . __('Hapus Entri Jurnal ') . ' ' . $record->getNamaAkun() . '?"',
                ];
                return $this->makeButtonDropdown($actions, $record->id);
            })
            ->rawColumns(
                [
                    'kode_akun',
                    'nama_akun',
                    'tipe_akun',
                    'jenis',
                    'action',
                ]
            )
            ->make(true);
    }

    public function addEntry($id_template){
        return $this->render($this->views . '.entry', compact('id_template'));
    }

    public function submitEntry(EntryRequest $request, $id){
        $record = new Entry;
        $record->id_template = $id;
        return $record->handleStoreOrUpdate($request);
    }

    public function update(Template $record, TemplateRequest $request){
        return $record->handleStoreOrUpdate($request);
    }

    public function destroy(Entry $record){
        return $record->handleDestroy();
    }

}
