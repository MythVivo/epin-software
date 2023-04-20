<?php

use Carbon\Carbon;

?>

<table lang="{{getLang()}}" id="datatable" class="table table-bordered nowrap"

       style="border-collapse: collapse; border-spacing: 0; width: 100%;">

    <thead>

    <tr>

        <th>Resim</th>

        <th>Kategori</th>

        <th>Eklenme Tarihi</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </thead>

    <tbody>

    <?php

    $files = new DirectoryIterator('front/avatars/');

    ?>



    @foreach($files as $f)

        @if($f->getFilename() != "." and $f->getFilename() != ".." and $f->getFilename() != "brandicon.png")

            <?php

            $filesSub = new DirectoryIterator('front/avatars/' . $f);

            ?>

            @foreach($filesSub as $ff)

                @if($ff != "." and $ff != "..")

                    <tr>

                        <td class="text-center w-25"><img class="img w-30 img-fluid"

                                 src="{{asset('/front/avatars/'.$f.'/'.$ff)}}"></td>

                        <td>{{$f}}</td>

                        <td>{{Carbon::createFromTimestamp($ff->getMTime())->format('Y-m-d H:i:s')}}</td>

                        <td>
                            @if(userRoleIsAdmin(Auth::user()->id))
                            <button onclick="location.href='?sil=resim&klasor={{$f}}&dosya={{$ff}}'" type="button"

                                    class="btn btn-lg btn-outline-danger waves-effect waves-light">

                                <i class="far fa-trash-alt"></i>

                            </button>
                            @endif

                        </td>

                    </tr>

                @endif

            @endforeach

        @endif



    @endforeach

    </tbody>

    <tfoot>

    <tr>

        <th>Resim</th>

        <th>Kategori</th>

        <th>Eklenme Tarihi</th>

        <th>@lang('admin.aksiyon')</th>

    </tr>

    </tfoot>

</table>



