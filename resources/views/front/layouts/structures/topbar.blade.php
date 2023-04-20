<style>
    * {
        box-sizing: border-box;
    }

    .topContainer {
        width: 100%;
        ;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        max-width: 1526px;
        margin: auto;
        background-color: #1e1f2b;
    }

    .topContainer>img:first-child {
        border-radius: 0px 0px 0px 10px;
    }

    .topContainer>img:last-child {
        border-radius: 0px 0px 10px 0px;
    }

    .topMidContainer {
        height: 75px;
        width: 575px;
        /* background: url("https://oyuneks.com/public/front/images/topbar/575x75/background.png"); */
        margin: auto;
    }

    .topMidContainer {
        display: flex;
        flex-direction: row;
        justify-content: center;
    }

    .topMidContainer>img {
        transition: all 1s linear;
    }

    .topMidContainer>img:last-child {

        transform: scale(0.5);
    }

    .topMidContainer>img:first-child {
        transform: scale(0.1) rotate(1deg);
    }

    .topMidContainer>img:nth-child(2) {
        transition: unset;
    }

    @media only screen and (min-width: 600px) {
        .header-margin {
            margin-top: 244px !important;
        }
    }

    @media only screen and (max-width: 600px) {
        .topContainer {
            display: none;
        }
    }

    @media only screen and (max-width: 1200px) {

        .topContainer>img:last-child,
        .topContainer>img:first-child {
            display: none !important;
        }
    }
</style>
<a rel="nofollow noreferrer noopener" target="_blank" href="https://discord.com/invite/oyuneks" class="topContainer">
    <img src="https://oyuneks.com/public/front/images/topbar/400x75/400x75-left4.png" alt="">
    <div class="topMidContainer">
        <img src="https://oyuneks.com/public/front/images/topbar/575x75/2000-degerinde.png" alt="">
        <img src="https://oyuneks.com/public/front/images/topbar/575x75/hediye2.png" alt="">
        <img src="https://oyuneks.com/public/front/images/topbar/575x75/discord-cekilisimize-katilmak-icin.png" alt="">
    </div>
    <img src="https://oyuneks.com/public/front/images/topbar/400x75/400x75-right4.png" alt="">
</a>

<script>
    var flipFlop = false;
    var flipFlop2 = false;
    var init = false;
    var timerFunc = function() {
        flipFlop = !flipFlop;
        var scale = flipFlop ? "0.95" : "0.8";
        document.querySelector(".topMidContainer>img:last-child").style.transform = "scale(" + scale + ")";
        !init && (document.querySelector(".topMidContainer>img:first-child").style.transform = "scale(1) rotate(359deg)");
        init = true;
    };
    var speedTimer = function() {
        flipFlop2 = !flipFlop2;
        var rot = flipFlop2 ? '359deg' : '1deg';
        document.querySelector(".topMidContainer>img:nth-child(2)").style.transform = "rotate(" + rot + ")";
    }
    document.addEventListener("DOMContentLoaded", function() {

        setTimeout(timerFunc, 100);;
        setInterval(timerFunc, 1000);
        setInterval(speedTimer, 500);
    });
</script>