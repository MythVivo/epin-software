@extends('front.layouts.app')
@section('css')
<style>
    td {
        vertical-align: middle;
    }
</style>
@endsection
@section('body')
<?php
if (Cookie::get('redirect') !== null) {
    $package = Cookie::get('package');
    $adet = Cookie::get('adet');
}
if ($package > 0) {
    $package = \App\Models\GamesPackages::where('id', $package)->first();
} else {
    $adet = 0;
    $package = array();
}
?>
<section class="game pt-140">
    <div class="container">
        <div class="accordion" id="online-pay">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#pay-online" aria-expanded="true" aria-controls="pay-online">
                        Online Ödeme
                    </button>
                </h2>
                <div id="pay-online" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                     data-bs-parent="#online-pay">
                    <div class="accordion-body">
                        <div class="row">

                    <form>
<h2>Ödeme</h2>

<div class="pay-limit-select">

<label>
<input type="radio" name="tutar" value="25">
<span>25TL</span>
</label>
<label>
<input type="radio" name="tutar" value="25">
<span>50TL</span>
</label>
<label>
<input type="radio" name="tutar" value="25">
<span>75TL</span>
</label>
<label>
<input type="radio" name="tutar" value="25">
<span>100TL</span>
</label>
<label>
<span><input type="text" name="tutar" value=""></span>
</label>
</div>

</form>





                        </div>



                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="pay-eft">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#pay-eft" aria-expanded="false" aria-controls="pay-eft">
                        EFT/Havale
                    </button>
                </h2>
                <div id="pay-eft" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                     data-bs-parent="#online-pay">
                    <div class="accordion-body">
                        <strong>This is the second item's accordion body.</strong> It is hidden by default, until the
                        collapse plugin adds the appropriate classes that we use to style each element. These classes
                        control the overall appearance, as well as the showing and hiding via CSS transitions. You can
                        modify any of this with custom CSS or overriding our default variables. It's also worth noting
                        that just about any HTML can go within the <code>.accordion-body</code>, though the transition
                        does limit overflow.
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
@endsection

@section('js')
<script>
$("input[name=tutar]").keyup(function(e){


if(e.target.type == "text"){

    $("input[type=radio]").prop('checked',false)
}

})
    </script>

    @endsection
