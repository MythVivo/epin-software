<?php

use Carbon\Carbon;

?>

<table lang="{{getLang()}}" id="datatable" class="table table-bordered nowrap"

       style="border-collapse: collapse; border-spacing: 0; width: 100%;">

    <thead>

    <tr>

        <th>Kategori</th>

        <th>Tarih</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </thead>

    <tbody>

    <?php

    $files = new DirectoryIterator('front/avatars/');

    ?>

    @foreach($files as $f)

        @if($f->getFilename() != "." and $f->getFilename() != ".." and $f->getFilename() != "brandicon.png")

            <tr>

                <td>{{$f}}</td>

                <td>{{Carbon::createFromTimestamp($f->getMTime())->format('Y-m-d H:i:s')}}</td>

                <td>
                    @if(userRoleIsAdmin(Auth::user()->id))
                    <button onclick="location.href='?sil=kategori&kategori={{$f}}'" type="button"

                            class="btn btn-lg btn-outline-danger waves-effect waves-light">

                        <i class="far fa-trash-alt"></i>

                    </button>
                    @endif
                </td>

            </tr>

        @endif

    @endforeach

    </tbody>

    <tfoot>

    <tr>

        <th>Kategori</th>

        <th>Tarih</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </tfoot>

</table>



