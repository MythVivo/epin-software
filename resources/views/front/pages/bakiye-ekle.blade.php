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
    if (isset($_COOKIE['redirect'])) {
        $package = $_COOKIE['package'];
        $adet = $_COOKIE['adet'];
    } else {
        if (!isset($redirect)) {
            echo "<meta http-equiv='refresh' content='2;url=" . route('homepage') . "' />";
            die(__('general.yonlendiriliyorsunuz'));
        }
    }
    $package = \App\Models\GamesPackages::where('id', $package)->first();
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
                            <form>
                                <div class="row">
                                    <h2>Ödeme</h2>

                                    <div class="col">
                                        <input type="radio" class="btn-check" name="options-outlined"
                                               id="success-outlined"
                                               autocomplete="off" checked>
                                        <label class="btn btn-outline-success w-100" for="success-outlined">₺25</label>
                                    </div>
                                    <div class="col">
                                        <input type="radio" class="btn-check" name="options-outlined"
                                               id="danger-outlined"
                                               autocomplete="off">
                                        <label class="btn btn-outline-success w-100" for="danger-outlined">₺50</label>
                                    </div>

                                    <div class="col">
                                        <input type="radio" class="btn-check" name="options-outlined"
                                               id="danger-outlined1"
                                               autocomplete="off">
                                        <label class="btn btn-outline-success w-100" for="danger-outlined1">₺75</label>
                                    </div>

                                    <div class="col">
                                        <input type="radio" class="btn-check" name="options-outlined"
                                               id="danger-outlined2"
                                               autocomplete="off">
                                        <label class="btn btn-outline-success w-100" for="danger-outlined2">₺100</label>
                                    </div>

                                    <div class="col">
                                       - Veya Tutar Girin :
                                    </div>

                                    <div class="col">

                                        <input type="radio" class="btn-check" name="options-outlined"
                                               id="danger_outlined3"
                                               autocomplete="off">
                                        <label for="danger_outlined3">
                                            <input class="form-control w-100" type="number" step="0.01" name="tutar"
                                                   autocomplete="off">
                                        </label>
                                    </div>


                                </div>

                            </form>


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
                            <strong>This is the second item's accordion body.</strong> It is hidden by default, until
                            the
                            collapse plugin adds the appropriate classes that we use to style each element. These
                            classes
                            control the overall appearance, as well as the showing and hiding via CSS transitions. You
                            can
                            modify any of this with custom CSS or overriding our default variables. It's also worth
                            noting
                            that just about any HTML can go within the <code>.accordion-body</code>, though the
                            transition
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
        $("input[name=tutar]").click(function (e) {
            $("#danger_outlined3").prop('checked', true)
        })
    </script>

@endsection
