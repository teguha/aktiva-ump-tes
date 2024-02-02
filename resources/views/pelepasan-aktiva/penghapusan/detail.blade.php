@extends('layouts.pageSubmit')

@section('action', route($routes . '.update', $record->id))

@section('card-body')
    @section('page-content')
    @method('PATCH')
    @csrf
    <!-- header -->
    <input type="text" class="form-control" name="is_detail" value="1" hidden readonly>
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
                @section('card-header')
                    <div class="card-header">
                        <h3 class="card-title">@yield('card-title', $title)</h3>
                        <div class="card-toolbar">
                            @section('card-toolbar')
                                @include('layouts.forms.btnBackTop')
                            @show
                        </div>
                    </div>
                @show
                <div class="card-body">
                    {{-- @csrf --}}
                    @include('pelepasan-aktiva.penghapusan.subs.card-1')
                </div>
            </div>
        </div>
    </div>
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">@yield('card-title', "Detail Aktiva")</h3>
        </div>
        <div class="card-body p-8">
            @csrf
            @include('pelepasan-aktiva.penghapusan.subs.card-2')
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between">
                @if ($page_action == "approval")
                    @include('layouts.forms.btnBack')
                    @include('layouts.forms.btnDropdownApproval')
                    @include('layouts.forms.modalReject')
                    @include('layouts.forms.modalRevision')
                @endif
            </div>
        </div>
    </div>
    @php
    $colors = [
        1 => 'primary',
        2 => 'info',
    ];
    @endphp
    @if($page_action == "detail")
    <div class="row">
        <div class="col-md-6" style="margin-top:20px!important;">
          <div class="card card-custom"  style="height:100%;">
              <div class="card-header">
                  <h3 class="card-title">
                      Alur Persetujuan
                  </h3>
              </div>
              <div class="card-body">
                  <div class="row">
                      <div class="col-md-12">
                          <div class="d-flex flex-column mr-5">
                              <div class="d-flex align-items-center justify-content-center">
                                  @php
                                  $menu =\App\Models\Globals\Menu::where('module', $module)->first();
                                  // dd($module);
                                  @endphp
                                  @if ($menu->flows()->get()->groupBy('order')->count() == 0)
                                  <span class="label label-light-info font-weight-bold label-inline" data-toggle="tooltip">Data tidak tersedia.</span>
                                  @else
                                  @foreach ($orders = $menu->flows()->get()->groupBy('order') as $i => $flows)
                                  @foreach ($flows as $j => $flow)
                                  <span class="label label-light-{{ $colors[$flow->type] }} font-weight-bold label-inline"
                                      data-toggle="tooltip" title="{{ $flow->show_type }}">{{ $flow->role->name }}</span>
                                  @if (!($i === $orders->keys()->last() && $j === $flows->keys()->last()))
                                  <i class="mx-2 fas fa-angle-double-right text-muted"></i>
                                  @endif
                                  @endforeach
                                  @endforeach
                                  @endif
                              </div>
                              <br>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        </div>
        <div class="col-md-6" style="margin-top:20px!important;">
          <div class="card card-custom" style="height:100%;">
              <div class="card-header">
                  <h3 class="card-title">
                      Informasi
                  </h3>
              </div>
              <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                      <div class="d-flex flex-column mr-5">
                          <p class="text-dark-50">
                              Sebelum submit pastikan data tersebut sudah sesuai.
                          </p>
                      </div>
                      <div class="ml-6 ml-lg-0 ml-xxl-6 flex-shrink-0">
                          @php
                              $menu =\App\Models\Globals\Menu::where('module', $module)->first();
                              $count = $menu->flows()->count();
                              $submit = $count == 0 ? 'disabled' : 'enabled';
                          @endphp
                          <div style="display: none">
                          @include('layouts.forms.btnBack')
                          </div>
                          @include('layouts.forms.btnDropdownSubmit')
                      </div>
                  </div>
              </div>
          </div>
        </div>
    </div>
    @endif
    <!-- end of card -->

    <!-- end of card bottom -->
    <script>
        /**
 * jQuery serializeObject
 * @copyright 2014, macek <paulmacek@gmail.com>
 * @link https://github.com/macek/jquery-serialize-object
 * @license BSD
 * @version 2.5.0
 */
(function(root, factory) {

// AMD
if (typeof define === "function" && define.amd) {
  define(["exports", "jquery"], function(exports, $) {
    return factory(exports, $);
  });
}

// CommonJS
else if (typeof exports !== "undefined") {
  var $ = require("jquery");
  factory(exports, $);
}

// Browser
else {
  factory(root, (root.jQuery || root.Zepto || root.ender || root.$));
}

}(this, function(exports, $) {

var patterns = {
  validate: /^[a-z_][a-z0-9_]*(?:\[(?:\d*|[a-z0-9_]+)\])*$/i,
  key:      /[a-z0-9_]+|(?=\[\])/gi,
  push:     /^$/,
  fixed:    /^\d+$/,
  named:    /^[a-z0-9_]+$/i
};

function FormSerializer(helper, $form) {

  // private variables
  var data     = {},
      pushes   = {};

  // private API
  function build(base, key, value) {
    base[key] = value;
    return base;
  }

  function makeObject(root, value) {

    var keys = root.match(patterns.key), k;

    // nest, nest, ..., nest
    while ((k = keys.pop()) !== undefined) {
      // foo[]
      if (patterns.push.test(k)) {
        var idx = incrementPush(root.replace(/\[\]$/, ''));
        value = build([], idx, value);
      }

      // foo[n]
      else if (patterns.fixed.test(k)) {
        value = build([], k, value);
      }

      // foo; foo[bar]
      else if (patterns.named.test(k)) {
        value = build({}, k, value);
      }
    }

    return value;
  }

  function incrementPush(key) {
    if (pushes[key] === undefined) {
      pushes[key] = 0;
    }
    return pushes[key]++;
  }

  function encode(pair) {
    switch ($('[name="' + pair.name + '"]', $form).attr("type")) {
      case "checkbox":
        return pair.value === "on" ? true : pair.value;
      default:
        return pair.value;
    }
  }

  function addPair(pair) {
    if (!patterns.validate.test(pair.name)) return this;
    var obj = makeObject(pair.name, encode(pair));
    data = helper.extend(true, data, obj);
    return this;
  }

  function addPairs(pairs) {
    if (!helper.isArray(pairs)) {
      throw new Error("formSerializer.addPairs expects an Array");
    }
    for (var i=0, len=pairs.length; i<len; i++) {
      this.addPair(pairs[i]);
    }
    return this;
  }

  function serialize() {
    return data;
  }

  function serializeJSON() {
    return JSON.stringify(serialize());
  }

  // public API
  this.addPair = addPair;
  this.addPairs = addPairs;
  this.serialize = serialize;
  this.serializeJSON = serializeJSON;
}

FormSerializer.patterns = patterns;

FormSerializer.serializeObject = function serializeObject() {
  return new FormSerializer($, this).
    addPairs(this.serializeArray()).
    serialize();
};

FormSerializer.serializeJSON = function serializeJSON() {
  return new FormSerializer($, this).
    addPairs(this.serializeArray()).
    serializeJSON();
};

if (typeof $.fn !== "undefined") {
  $.fn.serializeObject = FormSerializer.serializeObject;
  $.fn.serializeJSON   = FormSerializer.serializeJSON;
}

exports.FormSerializer = FormSerializer;

return FormSerializer;
}));
    </script>
    <script>
        $(function() {
            $(document)
            .on('change', '#descriptionCtrl, #fileCtrl', function () {
                setTimeout(() => {
                    var description = $('#descriptionCtrl').val();
                    $.ajax({
                        url: "{{ route($routes . '.update', $record->id) }}",
                        method: 'PATCH',
                        data: $('#searchform').serializeObject()
                    });
                }, 1500);
            })
            .on('click', '.base-form--remove-temp-files', function(){
                setTimeout(() => {
                    $.ajax({
                        url: "{{ route($routes . '.update', $record->id) }}",
                        method: 'PATCH',
                        data: $('#searchform').serializeObject()
                    });
                }, 100);
            });
        });
    </script>
@show
@endsection
