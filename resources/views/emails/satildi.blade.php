@extends('emails.layouts.app')
@section('body')
    <div class="u-row-container" style="padding: 0px;background-color: transparent">
        <div class="u-row"
             style="Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #113c55;">
            <div style="border-collapse: collapse;display: table;width: 100%;background-color: transparent;">
                <!--[if (mso)|(IE)]>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="padding: 0px;background-color: transparent;" align="center">
                            <table cellpadding="0" cellspacing="0" border="0" style="width:600px;">
                                <tr style="background-color: #113c55;"><![endif]-->

                <!--[if (mso)|(IE)]>
                <td align="center" width="600"
                    style="width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;"
                    valign="top"><![endif]-->
                <div class="u-col u-col-100"
                     style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
                    <div style="width: 100% !important;">
                        <!--[if (!mso)&(!IE)]><!-->
                        <div style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;">
                            <!--<![endif]-->



                            <table style="font-family:'Lato',sans-serif;" role="presentation" cellpadding="0"
                                   cellspacing="0" width="100%" border="0">
                                <tbody>
                                <tr>
                                    <td style="overflow-wrap:break-word;word-break:break-word;padding:10px 40px 40px;font-family:'Lato',sans-serif;"
                                        align="left">

                                        <div style="color: #ffffff; line-height: 150%; text-align: center; word-wrap: break-word;">

                                            <p style="font-size: 14px; line-height: 150%;"><span
                                                        style="font-family: Lato, sans-serif; font-size: 14px; line-height: 21px;">
                                            {{$title}} isimli ilanınız satılmıştır. ₺{{$price}} tutarındaki kazancınızın tarafınıza aktarılabilmesi için lütfen oturum açıp canlı destekle iletişime geçerek iteminizi siteye teslim edin.
                                                </span>
                                            </p>
                                        </div>

                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <table id="u_content_button_1" style="font-family:'Lato',sans-serif;"
                                   role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                <tbody>
                                <tr>
                                    <td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:'Lato',sans-serif;"
                                        align="left">

                                        <div align="center">
                                            <!--[if mso]>
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                                   style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;font-family:'Lato',sans-serif;">
                                                <tr>
                                                    <td style="font-family:'Lato',sans-serif;" align="center">
                                                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml"
                                                                     xmlns:w="urn:schemas-microsoft-com:office:word"
                                                                     href=""
                                                                     style="height:42px; v-text-anchor:middle; width:306px;"
                                                                     arcsize="24%" stroke="f"
                                                                     fillcolor="#000000">
                                                            <w:anchorlock/>
                                                            <center style="color:#FFFFFF;font-family:'Lato',sans-serif;">
                                            <![endif]-->
                                            <a href="{{route('giris')}}" target="_blank"
                                               style="box-sizing: border-box;display: inline-block;font-family:'Lato',sans-serif;text-decoration: none;-webkit-text-size-adjust: none;text-align: center;color: #FFFFFF; background-color: #000000; border-radius: 10px; -webkit-border-radius: 10px; -moz-border-radius: 10px; width:auto; max-width:100%; overflow-wrap: break-word; word-break: break-word; word-wrap:break-word; mso-border-alt: none;">
                                                        <span class="v-padding"
                                                              style="display:block;padding:13px 40px;line-height:120%;"><strong><span
                                                                        style="font-size: 14px; line-height: 16.8px; font-family: Lato, sans-serif;">
                                                                    Siteye Git
                                                                </span></strong></span>
                                            </a>
                                            <!--[if mso]></center></v:roundrect></td></tr></table><![endif]-->
                                        </div>

                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <table style="font-family:'Lato',sans-serif;" role="presentation" cellpadding="0"
                                   cellspacing="0" width="100%" border="0">
                                <tbody>
                                <tr>
                                    <td style="overflow-wrap:break-word;word-break:break-word;padding:10px 40px 40px;font-family:'Lato',sans-serif;"
                                        align="left">

                                        <div style="color: #ffffff; line-height: 150%; text-align: center; word-wrap: break-word;">

                                            <p style="font-size: 14px; line-height: 150%; padding-top: 10px;"><span
                                                        style="font-family: Lato, sans-serif; font-size: 14px; line-height: 21px;">
                                                           Bunun bir yanlışlık olduğunu mu düşünüyorsun? <br>
                                                               Bu e-postayı beklemiyorsanız, herhangi bir şüpheli
                                                    davranışı her zaman <a href="mailto:{{getSiteContactEmail()}}">{{getSiteName()}} destek</a> ekibimize bildirebilirsiniz.
                                                            </span>
                                            </p>
                                        </div>

                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
                    </div>
                </div>
                <!--[if (mso)|(IE)]></td><![endif]-->
                <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
            </div>
        </div>
    </div>

@endsection
