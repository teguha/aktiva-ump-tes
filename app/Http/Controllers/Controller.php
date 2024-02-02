<?php

namespace App\Http\Controllers;

use App\Models\Traits\ResponseTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use ResponseTrait;

    private $data = [
        'title'       => 'TITLE',
        'subtitle'    => '',
        'breadcrumb'  => ['Home' => '/'],
        'routes'      => '',
        'views'      => '',
        'activeMenu'  => '',
        'module'      => '',
        'tableStruct' => [],
    ];

    public function prepare($binding)
    {
        $this->data = array_merge($this->data, $binding);
        if ($this->data['views'] == '') {
            $this->data['views'] = $this->data['routes'];
        }
        if ($this->data['module'] == '') {
            $this->data['module'] = str_replace('.', '_', $this->data['routes']);
        }
        $this->data['title'] = __($this->data['title']);
        request()->merge([
            'module' => $this->prepared('module'),
            'routes' => $this->prepared('routes'),
        ]);
    }

    public function prepared($name = null)
    {
        if ($name) {
            return $this->data[$name];
        }
        return $this->data;
    }

    public function pushBreadcrumb($values)
    {
        $this->data['breadcrumb'] = array_merge($this->data['breadcrumb'], $values);
    }

    public function render($view, $additional = [])
    {
        $this->prepare($additional);
        if (empty($this->data['permission']) || auth()->user()->checkPerms($this->data['permission'])) {
            return view($view, $this->data);
        }
        return abort(403);
    }

    public function makeColumn($params)
    {
        $columns = [
            'name' => '',
            'data' => '',
            'label' => '',
            'width' => '',
            'sortable' => false,
            'className' => '',
        ];

        if (is_string($params)) {
            $params = $this->checkGeneralColumn($params);
            $params = str_to_array($params, '|', ':');
        }
        $params['data']      = $params['data'] ?? $params['name'];
        $params['label']     = $params['label'] ?? ucfirst(str_replace('_', ' ', $params['name']));
        $params['className'] = $params['className'] ?? 'text-center';
        $params = array_merge($columns, $params);
        $params['label'] = __($params['label']);
        return $params;
    }

    public function checkGeneralColumn($params)
    {
        if ($params === 'name:num') {
            $params = 'name:num|label:#|sortable:false|width:20px';
        }
        elseif ($params === 'name:action') {
            $params = 'name:action|label:Aksi|sortable:false|width:40px';
        }
        elseif ($params === 'name:created_by') {
            $params = 'name:created_by|label:Dibuat Oleh|width:180px';
        }
        elseif ($params === 'name:created_at') {
            $params = 'name:created_at|label:Dibuat Pada|width:180px';
        }
        elseif ($params === 'name:updated_by') {
            $params = 'name:updated_by|label:Diperbarui|width:160px';
        }
        elseif ($params === 'name:updated_at') {
            $params = 'name:updated_at|label:Diperbarui Pada|width:180px';
        }
        elseif ($params === 'name:status') {
            $params = 'name:status|label:Status|className:text-center|width:100px';
        }
        return $params;
    }

    public function makeLabel($label, $color='primary', $params = [])
    {
        $default = [
            'class' => '',
            'attrs' => '',
            'style' => '',
        ];
        $params = array_merge($default, $params);
        return '<span class="label label-'.$color.' label-inline '.$params['class'].'" '.$params['attrs'].' style="'.$params['style'].'">'.$label.'</span>';
    }

    public function getStatus($key)
    {
        return \Base::getStatus($key);
    }

    public function makeButton($params = [])
    {
        $settings = [
            'id'       => '',
            'class'    => '',
            'label'    => 'Button',
            'tooltip'  => '',
            'url'      => '',
        ];

        $btn   = '';
        $datas = '';
        $attrs = '';

        if (isset($params['datas'])) {
            foreach ($params['datas'] as $k => $v) {
                $datas .= ' data-'.$k.'="'.$v.'"';
            }
        }

        if (isset($params['attributes'])) {
            foreach ($params['attributes'] as $k => $v) {
                $attrs .= ' '.$k.'="'.$v.'"';
            }
        }

        if (isset($params['type'])) {
            switch ($params['type']) {
                case 'modal':
                    $settings['class']   = 'base-modal--render text-primary';
                    break;

                case 'show':
                    $settings['class']   = 'base-modal--render text-primary';
                    $settings['label']   = '<i class="mx-2 fa fa-eye"></i>';
                    $settings['tooltip'] = 'Lihat';
                    $settings['url']     = route($this->data['routes'].'.show', $params['id']);
                    break;

                case 'page-show':
                    $settings['class']   = 'base-content--replace text-primary';
                    $settings['label']   = '<i class="mx-2 fa fa-eye"></i>';
                    $settings['tooltip'] = 'Lihat';
                    $settings['url']     = route($this->data['routes'].'.show', $params['id']);
                    break;

                case 'edit':
                    $settings['class']   = 'base-modal--render text-success';
                    $settings['label']   = '<i class="mx-2 fa fa-edit"></i>';
                    $settings['tooltip'] = 'Ubah';
                    $settings['url']     = route($this->data['routes'].'.edit', $params['id']);
                    break;

                case 'page-edit':
                    $settings['class']   = 'base-content--replace text-success';
                    $settings['label']   = '<i class="mx-2 fa fa-edit"></i>';
                    $settings['tooltip'] = 'Ubah';
                    $settings['url']     = route($this->data['routes'].'.edit', $params['id']);
                    break;

                case 'delete':
                    $settings['class']   = 'base-modal--confirm text-danger';
                    $settings['label']   = '<i class="mx-2 fa fa-trash"></i>';
                    $settings['tooltip'] = 'Hapus';
                    $settings['url']     = route($this->data['routes'].'.destroy', $params['id']);
                    if (isset($params['confirm_text'])) {
                        $datas .= 'data-confirm-text="'.$params['confirm-text'].'"';
                    }
                    else {
                        $datas .= 'data-confirm-text="'.__('Apakah Anda yakin?').'"';
                    }
                    break;

                case 'activate':
                    $settings['class'] = 'base-form--activate btn btn-sm';
                    $settings['url']   = route($this->data['routes'].'.activate', $params['id']);
                    $datas .= 'data-status="'.$params['status'].'"';
                    if ($params['status'] === 1) {
                        $settings['class']   .= ' btn-primary';
                        $settings['label']   = 'ACTIVE';
                        $settings['tooltip'] = 'Status is active';
                    }
                    else {
                        $settings['class']   .= ' btn-default';
                        $settings['label']   = 'NONACTIVE';
                        $settings['tooltip'] = 'Status is nonactive';
                    }
                    break;

                case 'page':
                case 'url':
                default:
                    $settings['class']   = 'base-content--replace';
                    $settings['label']   = '<i class="fa fa-eye"></i>';
                    break;
            }
        }

        $params  = array_merge($settings, $params);
        $btn = '<a href="'.$params['url'].'"
                    class="'.$params['class'].'"
                    data-toggle="tooltip"
                    data-container="#content"
                    title="'.$params['tooltip'].'"
                    '.$datas.' '.$attrs.' '.'>
                    '.$params['label'].'
                </a>';

        return $btn;
    }

    public function makeButtonDropdown($params = [], $id = null)
    {
        $btn = '';
        if (!empty($params)) {
            $btn = '<div class="dropdown dropleft">
                        <button type="button" class="btn btn-light-primary btn-icon btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ki ki-bold-more-hor"></i>
                        </button>
                        <div class="dropdown-menu">';
                        foreach ($params as $item) {
                            if (is_string($item)) {
                                $item = str_to_array($item, '|', ':');
                            }

                            $default = [
                                'modal' => true,
                                'class' => (!empty($item['page'])) ? 'base-content--replace' : 'base-modal--render',
                                'url' => 'javascript:;',
                                'icon' => '',
                                'label' => 'Title',
                                'attrs' => '',
                            ];

                            if (!empty($item['type'])) {
                                $item['id'] = $item['id'] ?? $id;
                                switch ($item['type']) {
                                    case 'create':
                                        $default['icon'] = 'fa fa-plus text-primary';
                                        $default['label'] = 'Tambah';
                                        $default['url'] = $item['url'] ?? '';
                                        break;
                                    case 'show':
                                        $default['icon'] = 'fa fa-eye text-primary';
                                        $default['label'] = 'Lihat';
                                        $default['url'] = route($this->prepared('routes').'.show', $item['id']);
                                        $default['attrs'] = 'data-modal-backdrop="true"';
                                        break;
                                    case 'approval':
                                    case 'authorization':
                                        $default['icon'] = 'fa fa-check text-primary';
                                        $default['label'] = 'Approval';
                                        $default['url'] = $item['url'] ?? route($this->prepared('routes').'.approval', $item['id']);
                                        break;
                                    case 'edit':
                                        $default['icon'] = 'fa fa-edit text-success';
                                        $default['label'] = 'Ubah';
                                        $default['url'] = route($this->prepared('routes').'.edit', $item['id']);
                                        break;
                                    case 'print':
                                        $default['icon'] = 'fa fa-print text-dark';
                                        $default['label'] = 'Cetak';
                                        $default['url'] = route($this->prepared('routes').'.print', $item['id']);
                                        $default['class'] = '';
                                        $default['attrs'] = 'target="_blank"';
                                        break;
                                    case 'download':
                                        $default['icon'] = 'fa fa-download text-dark';
                                        $default['label'] = 'Download';
                                        $default['url'] = route($this->prepared('routes').'.download', $item['id']);
                                        $default['class'] = '';
                                        $default['attrs'] = 'target="_blank"';
                                        break;
                                    case 'delete':
                                        $default['icon'] = 'fa fa-trash text-danger';
                                        $default['label'] = 'Hapus';
                                        $default['class'] = 'base-modal--confirm';
                                        $default['url'] = route($this->prepared('routes').'.destroy', $item['id']);
                                        if(!empty($item['text'])) {
                                            $confirm = __('Hapus data').' '.$item['text'].'?';
                                            $default['attrs'] = 'data-confirm-text="'.$confirm.'"';
                                        }
                                        break;
                                    case 'history':
                                        $default['icon'] = 'fa fa-clock text-dark';
                                        $default['label'] = 'Riwayat';
                                        $default['url'] = route($this->prepared('routes').'.history', $item['id']);
                                        $default['attrs'] = 'data-modal-backdrop="default"';
                                        break;
                                    case 'tracking':
                                        $default['icon'] = 'fas fa-chart-line text-info';
                                        $default['label'] = 'Tracking Approval';
                                        $default['url'] = route($this->prepared('routes').'.tracking', $item['id']);
                                        $default['attrs'] = 'data-modal-backdrop="default"';
                                        break;
                                }
                            }
                            $item = array_merge($default, $item);
                            $btn .= '<a class="dropdown-item '.$item['class'].'" href="'.$item['url'].'" '.$item['attrs'].'>
                                        <i class="pb-1 mr-3 '.$item['icon'].'"></i>'.__($item['label']).'
                                    </a>';
                        }
                        $btn .= '</div>
                    </div>';

        }
        return $btn;
    }
}
