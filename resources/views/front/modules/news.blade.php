<div class="row title-area" data-lang="{{ getLang() }}">
    <div class="col-sm-12 col-md-12 title s-title">
        <h1 class="heading-primary style-2">
            <a href="{{ route('blog') }}">
                <span>Blog</span>
            </a>
        </h1>
    </div>
</div>
<div class="row">
    @php
        function file_get_contents_curl($url)
        {
            $ch = curl_init();
        
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
            $data = curl_exec($ch);
            curl_close($ch);
        
            return $data;
        }
        
        $response = file_get_contents_curl('https://oyuneks.com/blog/wp-json/wp/v2/posts?_embed&per_page=5');
        $response = json_decode($response);
        $list = [];
        foreach ($response as $value) {
            $title = strip_tags($value->title->rendered);
            $title = str_replace('&#8216;', '‘', $title);
            $title = str_replace('&#8217;', '\'', $title);
            $title = str_replace('&#8220;', '“', $title);
            $title = str_replace('&#8221;', '”', $title);
            $title = htmlspecialchars_decode($title);
        
            $detail = strip_tags($value->excerpt->rendered);
            $detail = str_replace('&#8216;', '‘', $detail);
            $detail = str_replace('&#8217;', '\'', $detail);
            $detail = str_replace('&#8220;', '“', $detail);
            $detail = str_replace('&#8221;', '”', $detail);
            $detail = htmlspecialchars_decode($detail);
            $postDate = strtotime($value->date);
            if ($postDate > time() - 60) {
                $postDate = 'Biraz Önce';
            } elseif ($postDate > time() - 60 * 59) {
                $postDate = round((time() - $postDate) / 60) . ' ' . 'Dakika Önce';
            } elseif ($postDate > time() - 60 * 60 * 23) {
                $postDate = round((time() - $postDate) / 60 / 60) . ' ' . 'Saat Önce';
            } elseif ($postDate > time() - 60 * 60 * 24 * 6) {
                $postDate = round((time() - $postDate) / 60 / 60 / 24) . ' ' . 'Gün Önce';
            } else {
                $postDate = date('Y-m-d H:i:s', $postDate);
            }
        
            if(isset( $value->_embedded->{"wp:featuredmedia"}))
            $img = $value->_embedded->{"wp:featuredmedia"}[0]->source_url;
            else 
            $img = 'https://oyuneks.com/blog/wp-content/uploads/2023/03/oyuneks-cover-photo.png';

            $list[] = ['title' => $title, 'date' => $postDate, 'detail' => $detail, 'img' => $img, 'link' => $value->link];
        }
        
    @endphp
    @foreach ($list as $u)
        <div class="news-card mb-4">
            <div class="news-body">
                @if(isset($u['img']))
                <figure onclick="location.href='{{ $u['link'] }}'">
                    <img class="lazyload" src="data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs="
                        data-src={{ $u['img'] }} width="286" height="215">
                </figure>
                @endif
                <div class="news-description">
                    <a href="{{ $u['link'] }}" class="card-title heading-secondary">{{ $u['title'] }}</a>
                    <p class="card-text">{{ $u['detail'] }}</p>
                    <span class="news-date">{{ $u['date'] }}</span>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="row justify-content-md-center mt-5">
    <div class="col-12 text-center">
        <button class="btn-inline page-button" onclick="location.href='{{ route('haber') }}'">@lang('general.tumunuGoruntule')</button>
    </div>
</div>
