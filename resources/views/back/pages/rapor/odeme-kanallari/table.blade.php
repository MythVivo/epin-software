<table lang="{{getLang()}}" id="datatable" class="table table-bordered nowrap"
       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
        <th>Id</th>
        <th>Ödeme Kanalı</th>
        <th>Toplam Tutar</th>
        <th>Tarih</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($_GET['date']))
        <?php
        $date = $_GET['date'];
        $date2 = $_GET['date2'];
        foreach ($_GET['channel'] as $ch) {
            $channel[] = $ch;
        }
        ?>
    @else
        <?php
        $date = date('Y-m-d');
        $date2 = date('Y-m-d');
        $channel = array(0);
        ?>
    @endif
    <?php
    if(in_array("0", $channel)) {
        $query = DB::table('payment_channels')->get();
    } else {
        $query = DB::table('payment_channels')->whereIn('id', $channel)->get();
    }


    ?>
    @foreach($query as $u)
        <?php
        $odemeler = DB::table('odemeler')->whereDate('created_at', '>=', $date)->whereDate('created_at', '<=', $date2)->where('channel', $u->id)->where('status', '1')->whereNull('deleted_at');
        $total = $odemeler->sum('amount');
        ?>
        <tr>
            <td>{{$u->id}}</td>
            <td class="text-center">
                {{findPaymentChannel($u->id)}}
            </td>
            <td>{{$total}} TL</td>
            <td>
                {{$date}} - {{$date2}}
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>Id</th>
        <th>Ödeme Kanalı</th>
        <th>Toplam Tutar</th>
        <th>Tarih</th>
    </tr>
    </tfoot>
</table>

