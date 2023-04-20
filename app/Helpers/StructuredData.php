<?php

namespace App\Helpers;


class StructuredData
{
    public static function PriceGuard($data)
    {
        /* echo '
        <script>
            var pData = null;
            document.addEventListener("DOMContentLoaded", function(event) { 
                setInterval(()=>{
                $.ajax({
                    url : "/priceguard",
                    type: "POST",
                    data : ' . json_encode($data) . ',
                    success: function(data, textStatus, jqXHR)
                    {
                        if(pData != null && data != pData)
                        {
                            location.reload();
                        }
                        pData = data;
                        
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                
                    }
                });
            },10000);
            });
        </script>
        '; */
    }
    public static function jsonLd($type, $data)
    {
        if ($type == 'packages') {
            $priceList = array_map(function ($package) {
                if($package->discount_type == 1) {
                    $discount = $package->price - ($package->discount_amount * $package->price)/100;
                } else {
                    $discount = $package->price;
                }
                return $discount;
            }, $data['packages']->toArray());
    
            $ldComments = [];
            $rateList = [];
            foreach ($data['comments'] as $comment) {
                $rateList[] = $comment['rate'];
                $maskedUsername = explode(' ', $comment['user_name']);
                $maskedUsername = mb_substr($maskedUsername[0], 0, 1) . '*** ' . mb_substr(end($maskedUsername), 0, 1) . '***';
                $ldComments[] = [
                    '@context' => 'http://schema.org/',
                    'author' => [
                        '@type' => 'Person',
                        'name' => $maskedUsername,
                    ],
                    'datePublished' => (new \DateTime($comment['created_at']))->format('d/m/Y'),
                    'description' => $comment['text'],
                    'name' => $data['epin']->title,
                    'reviewRating' => [
                        '@type' => 'Rating',
                        'bestRating' => 5,
                        'ratingValue' => $comment['rate'],
                        'worstRating' => 1,
                    ],
                ];
            }
    
            $rateAvg = empty($rateList) ? 5 : number_format(array_sum($rateList) / count($rateList), 1, '.', '');
            $rateCount = empty($rateList) ? 1 : count($rateList);
            $ld = [
                '@context' => 'http://schema.org/',
                '@type' => 'Product',
                'sku' => "E-{$data['epin']->id}",
                'mpn' => 'online',
                'name' => $data['epin']->title,
                'image' => asset(env('ROOT') . env('FRONT') . env('GAMES_TITLES') . $data['epin']->image),
                'description' => $data['epin']->description,
                'brand' => [
                    '@type' => 'Brand',
                    'name' => $data['epin']->title,
                    'url' => \Request::url(),
                ],
                'aggregateRating' => [
                    '@type' => 'AggregateRating',
                    'ratingValue' => $rateAvg,
                    'reviewCount' => $rateCount,
                ],
                'review' => $ldComments,
                'model' => [],
                'offers' => [],
            ];
    
            if (count($priceList) > 0) {
                $ld['offers'] = [
                    '@type' => 'AggregateOffer',
                    'lowPrice' => number_format(min($priceList), 2, '.', ''),
                    'highPrice' => number_format(max($priceList), 2, '.', ''),
                    'offerCount' => count($data['packages']),
                    'priceCurrency' => 'TRY',
                ];
            }
    
            foreach ($data['packages'] as $package) {
                if($package->discount_type == 1) {
                    $discount = $package->price - ($package->discount_amount * $package->price)/100;
                } else {
                    $discount = $package->price;
                }
                $ld['model'][] = [
                    '@type' => 'ProductModel',
                    'offers' => [
                        'priceCurrency' => 'TRY',
                        'name' => $package->title,
                        'price' => number_format($discount, 2, '.', ''),
                        'image' => asset(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES') . $package->image),
                        'availability' => 'http://schema.org/InStock',
                        'url' => \Request::url() . '/' . \Str::slug($package->title) . '-' . $package->id,
                        'priceValidUntil' => (new \DateTime())->modify('+1 day')->format('d/m/Y H:i:s'),
                    ],
                    'name' => $package->title,
                    'image' => asset(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES') . $package->image),
                    'sku' => "P-{$package->id}",
                    'brand' => [
                        '@type' => 'Brand',
                        'name' => $data['epin']->title,
                        'url' => \Request::url(),
                    ],
                    'description' => $data['epin']->description . ' ' . $data['epin']->title,
                    'mpn' => 'online',
                    /* "review" => $ldComments, */
                    'review' => [],
                    'aggregateRating' => [
                        'type' => 'AggregateRating',
                        'ratingValue' => $rateAvg,
                        'reviewCount' => $rateCount,
                    ],
                ];
            }
        } elseif ($type == 'package') {
            $package = $data['package'];
            $ldComments = [];
            $rateList = [];
            if($package->discount_type == 1) {
                $discount = $package->price - ($package->discount_amount * $package->price)/100;
            } else {
                $discount = $package->price;
            }
            foreach ($data['comments'] as $comment) {
                $rateList[] = $comment['rate'];
                $maskedUsername = explode(' ', $comment['user_name']);
                $maskedUsername = mb_substr($maskedUsername[0], 0, 1) . '*** ' . mb_substr(end($maskedUsername), 0, 1) . '***';
                $ldComments[] = [
                    '@context' => 'http://schema.org/',
                    'author' => [
                        '@type' => 'Person',
                        'name' => $maskedUsername,
                    ],
                    'datePublished' => (new \DateTime($comment['created_at']))->format('d/m/Y'),
                    'description' => $comment['text'],
                    'name' => $data['epin']->title,
                    'reviewRating' => [
                        '@type' => 'Rating',
                        'bestRating' => 5,
                        'ratingValue' => $comment['rate'],
                        'worstRating' => 1,
                    ],
                ];
            }
    
            $rateAvg = empty($rateList) ? 5 : number_format(array_sum($rateList) / count($rateList), 1, '.', '');
            $rateCount = empty($rateList) ? 1 : count($rateList);
            $ld = [
                '@context' => 'http://schema.org/',
                '@type' => 'Product',
                'sku' => "P-{$package->id}",
                'mpn' => 'online',
                'name' => $package->title,
                'image' => asset(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES') . $package->image),
                'description' => $data['epin']->description,
                'brand' => [
                    '@type' => 'Brand',
                    'name' => $data['epin']->title,
                    'url' => \Request::url(),
                ],
                'aggregateRating' => [
                    '@type' => 'AggregateRating',
                    'ratingValue' => $rateAvg,
                    'reviewCount' => $rateCount,
                ],
                'review' => $ldComments,
                'offers' => [
                    '@type' => 'Offer',
                    'priceCurrency' => 'TRY',
                    'name' => $package->title,
                    'price' => number_format($discount, 2, '.', ''),
                    'image' => asset(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES') . $package->image),
                    'availability' => 'http://schema.org/InStock',
                    'url' => \Request::url() . '/' . \Str::slug($package->title) . '-' . $package->id,
                    'priceValidUntil' => (new \DateTime())->modify('+1 day')->format('d/m/Y H:i:s'),
                ]
            ];
        } elseif ($type == 'gamegoldPackages') {
            $priceList = array_map(function ($package) {
                return $package['satis_fiyat'];
            }, $data['packages']->toArray());


            $ldComments = [];
            $rateList = [];
            foreach ($data['comments'] as $comment) {
                $rateList[] = $comment['rate'];
                $maskedUsername = explode(' ', $comment['user_name']);
                $maskedUsername = mb_substr($maskedUsername[0], 0, 1) . '*** ' . mb_substr(end($maskedUsername), 0, 1) . '***';
                $ldComments[] = [
                    '@context' => 'http://schema.org/',
                    'author' => [
                        '@type' => 'Person',
                        'name' => $maskedUsername,
                    ],
                    'datePublished' => (new \DateTime($comment['created_at']))->format('d/m/Y'),
                    'description' => $comment['text'],
                    'name' => $data['epin']->title,
                    'reviewRating' => [
                        '@type' => 'Rating',
                        'bestRating' => 5,
                        'ratingValue' => $comment['rate'],
                        'worstRating' => 1,
                    ],
                ];
            }

            $rateAvg = empty($rateList) ? 5 : number_format(array_sum($rateList) / count($rateList), 1, '.', '');
            $rateCount = empty($rateList) ? 1 : count($rateList);
            $ld = [
                '@context' => 'http://schema.org/',
                '@type' => 'Product',
                'sku' => "G-{$data['epin']->id}",
                'mpn' => 'online',
                'name' => $data['epin']->title,
                'image' => asset(env('ROOT') . env('FRONT') . env('GAMES_TITLES') . $data['epin']->image),
                'description' => $data['epin']->description,
                'brand' => [
                    '@type' => 'Brand',
                    'name' => $data['epin']->title,
                    'url' => \Request::url(),
                ],
                'aggregateRating' => [
                    '@type' => 'AggregateRating',
                    'ratingValue' => $rateAvg,
                    'reviewCount' => $rateCount,
                ],
                'review' => $ldComments,
                'model' => [],
                'offers' => [],
            ];

            if (count($priceList) > 0) {
                $ld['offers'] = [
                    '@type' => 'AggregateOffer',
                    'lowPrice' => number_format(min($priceList), 2, '.', ''),
                    'highPrice' => number_format(max($priceList), 2, '.', ''),
                    'offerCount' => count($data['packages']),
                    'priceCurrency' => 'TRY',
                ];
            }

            foreach ($data['packages'] as $package) {
                if ($package->discount_type == 1) {
                    $discount = $package->price - ($package->discount_amount * $package->price) / 100;
                } else {
                    $discount = $package->price;
                }
                $ld['model'][] = [
                    '@type' => 'ProductModel',
                    'offers' => [
                        'priceCurrency' => 'TRY',
                        'name' => $package->title,
                        'price' => number_format($discount, 2, '.', ''),
                        'image' => asset(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES') . $package->image),
                        'availability' => 'http://schema.org/InStock',
                        'url' => \Request::url() . '/' . \Str::slug($package->title) . '-' . $package->id,
                        'priceValidUntil' => (new \DateTime())->modify('+1 day')->format('d/m/Y H:i:s'),
                    ],
                    'name' => $package->title,
                    'image' => asset(env('ROOT') . env('FRONT') . env('GAMES_PACKAGES') . $package->image),
                    'sku' => "PG-{$package->id}",
                    'brand' => [
                        '@type' => 'Brand',
                        'name' => $data['epin']->title,
                        'url' => \Request::url(),
                    ],
                    'description' => $data['epin']->description . ' ' . $data['epin']->title,
                    'mpn' => 'online',
                    'review' => [],
                    'aggregateRating' => [
                        'type' => 'AggregateRating',
                        'ratingValue' => $rateAvg,
                        'reviewCount' => $rateCount,
                    ],
                ];
            }
        }


        return json_encode($ld, JSON_UNESCAPED_UNICODE);
    }
}
