<?php

use Carbon\Carbon;

?>
<table lang="{{getLang()}}" id="datatable" class="table table-bordered nowrap"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>Başlık</th>
        <th>İkon</th>
        <th>Eklenme Tarihi</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </thead>
    <tbody>

    @foreach(DB::table('icons')->whereNull('deleted_at')->get() as $f)
        <tr>
            <td>{{$f->title}}</td>
            <td class="text-center">{!! $f->icon !!}</td>
            <td>{{$f->created_at}}</td>
            <td>
                @if(userRoleIsAdmin(Auth::user()->id))
                <button onclick="location.href='?sil={{$f->id}}'" type="button"
                        class="btn btn-lg btn-outline-danger waves-effect waves-light">
                    <i class="far fa-trash-alt"></i>
                </button>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>Başlık</th>
        <th>İkon</th>
        <th>Eklenme Tarihi</th>
        <th>@lang('admin.aksiyon')</th>
    </tr>
    </tfoot>
</table>

