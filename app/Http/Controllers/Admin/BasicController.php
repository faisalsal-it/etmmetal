<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BasicSetting;
use App\BasicExtended;
use App\Language;
use Session;
use Validator;
use Config;
use Artisan;

class BasicController extends Controller
{
    public function favicon(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abs'] = $lang->basic_setting;
        return view('admin.basic.favicon', $data);
    }

    public function updatefav(Request $request, $langid)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json(['errors' => $validator->errors(), 'id' => 'favicon']);
        }

        if ($request->hasFile('file')) {
            $bs = BasicSetting::where('language_id', $langid)->firstOrFail();
            @unlink('assets/front/img/' . $bs->favicon);
            $filename = uniqid() .'.'. $img->getClientOriginalExtension();
            $img->move('assets/front/img/', $filename);

            $bs->favicon = $filename;
            $bs->save();

        }

        return response()->json(['status' => "success", 'image' => 'favicon']);
    }

    public function logo(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abs'] = $lang->basic_setting;

        return view('admin.basic.logo', $data);
    }

    public function updatelogo(Request $request, $langid)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json(['errors' => $validator->errors(), 'id' => 'logo']);
        }

        if ($request->hasFile('file')) {

            $bs = BasicSetting::where('language_id', $langid)->firstOrFail();
            // only remove the the previous image, if it is not the same as default image or the first image is being updated
            @unlink('assets/front/img/' . $bs->logo);
            $filename = uniqid() .'.'. $img->getClientOriginalExtension();
            $img->move('assets/front/img/', $filename);

            $bs->logo = $filename;
            $bs->save();

        }
        return response()->json(['status' => "success", 'image' => 'logo']);
    }


    public function homeversion(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abs'] = $lang->basic_setting;

        return view('admin.basic.homeversion', $data);
    }

    public function updatehomeversion(Request $request, $langid)
    {
        $bs = BasicSetting::where('language_id', $langid)->firstOrFail();
        $bs->home_version = $request->home_version;
        $bs->save();

        Session::flash('success', "$request->home_version version activated successfully!");
        return back();
    }


    public function basicinfo(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abs'] = $lang->basic_setting;
        $data['abe'] = $lang->basic_extended;

        return view('admin.basic.basicinfo', $data);
    }

    public function updatebasicinfo(Request $request, $langid)
    {
        $request->validate([
            'contact_mail' => 'required',
            'order_mail' => 'required',
            'website_title' => 'required',
            'base_color' => 'required',
            'secondary_base_color' => 'required',
        ]);

        $bs = BasicSetting::where('language_id', $langid)->firstOrFail();
        $bs->contact_mail = $request->contact_mail;
        $bs->website_title = $request->website_title;
        $bs->base_color = $request->base_color;
        $bs->secondary_base_color = $request->secondary_base_color;
        $bs->save();

        $be = BasicExtended::where('language_id', $langid)->firstOrFail();
        $be->order_mail = $request->order_mail;
        $be->save();

        Session::flash('success', 'Basic informations updated successfully!');
        return back();
    }

    public function seo(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abe'] = $lang->basic_extended;

        return view('admin.basic.seo', $data);
    }

    public function updateseo(Request $request, $langid)
    {
        $be = BasicExtended::where('language_id', $langid)->firstOrFail();
        $be->home_meta_keywords = $request->home_meta_keywords;
        $be->home_meta_description = $request->home_meta_description;
        $be->services_meta_keywords = $request->services_meta_keywords;
        $be->services_meta_description = $request->services_meta_description;
        $be->packages_meta_keywords = $request->packages_meta_keywords;
        $be->packages_meta_description = $request->packages_meta_description;
        $be->portfolios_meta_keywords = $request->portfolios_meta_keywords;
        $be->portfolios_meta_description = $request->portfolios_meta_description;
        $be->team_meta_keywords = $request->team_meta_keywords;
        $be->team_meta_description = $request->team_meta_description;
        $be->career_meta_keywords = $request->career_meta_keywords;
        $be->career_meta_description = $request->career_meta_description;
        $be->calendar_meta_keywords = $request->calendar_meta_keywords;
        $be->calendar_meta_description = $request->calendar_meta_description;
        $be->gallery_meta_keywords = $request->gallery_meta_keywords;
        $be->gallery_meta_description = $request->gallery_meta_description;
        $be->faq_meta_keywords = $request->faq_meta_keywords;
        $be->faq_meta_description = $request->faq_meta_description;
        $be->blogs_meta_keywords = $request->blogs_meta_keywords;
        $be->blogs_meta_description = $request->blogs_meta_description;
        $be->contact_meta_keywords = $request->contact_meta_keywords;
        $be->contact_meta_description = $request->contact_meta_description;
        $be->quote_meta_keywords = $request->quote_meta_keywords;
        $be->quote_meta_description = $request->quote_meta_description;
        $be->save();

        Session::flash('success', 'SEO informations updated successfully!');
        return back();
    }

    public function support(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abs'] = $lang->basic_setting;

        return view('admin.basic.support', $data);
    }

    public function updatesupport(Request $request, $langid)
    {
        $request->validate([
            'support_email' => 'required|email|max:100',
            'support_phone' => 'required|max:30',
        ]);

        $bs = BasicSetting::where('language_id', $langid)->firstOrFail();
        $bs->support_email = $request->support_email;
        $bs->support_phone = $request->support_phone;
        $bs->save();

        Session::flash('success', 'Support Informations updated successfully!');
        return back();
    }

    public function breadcrumb(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abs'] = $lang->basic_setting;

        return view('admin.basic.breadcrumb', $data);
    }

    public function updatebreadcrumb(Request $request, $langid)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json(['errors' => $validator->errors(), 'id' => 'breadcrumb']);
        }


        if ($request->hasFile('file')) {

            $bs = BasicSetting::where('language_id', $langid)->firstOrFail();
            @unlink('assets/front/img/' . $bs->breadcrumb);
            $filename = uniqid() .'.'. $img->getClientOriginalExtension();
            $img->move('assets/front/img/', $filename);

            $bs->breadcrumb = $filename;
            $bs->save();
        }

        return response()->json(['status' => "success", 'image' => 'breadcrumb']);
    }

    public function heading(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abs'] = $lang->basic_setting;
        $data['abe'] = $lang->basic_extended;

        return view('admin.basic.headings', $data);
    }

    public function updateheading(Request $request, $langid)
    {
        $request->validate([
            'service_title' => 'required|max:30',
            'service_subtitle' => 'required|max:40',
            'career_title' => 'required|max:30',
            'career_subtitle' => 'required|max:40',
            'event_calendar_title' => 'required|max:30',
            'event_calendar_subtitle' => 'required|max:40',
            'service_details_title' => 'required|max:30',
            'portfolio_title' => 'required|max:30',
            'portfolio_subtitle' => 'required|max:40',
            'portfolio_details_title' => 'required|max:40',
            'blog_details_title' => 'required|max:30',
            'contact_title' => 'required|max:30',
            'contact_subtitle' => 'required|max:40',
            'gallery_title' => 'required|max:30',
            'gallery_subtitle' => 'required|max:40',
            'team_title' => 'required|max:30',
            'team_subtitle' => 'required|max:40',
            'faq_title' => 'required|max:30',
            'faq_subtitle' => 'required|max:40',
            'pricing_title' => 'required|max:30',
            'pricing_subtitle' => 'required|max:40',
            'blog_title' => 'required|max:30',
            'blog_subtitle' => 'required|max:40',
            'quote_title' => 'required|max:30',
            'quote_subtitle' => 'required|max:40',
            'error_title' => 'required|max:30',
            'error_subtitle' => 'required|max:40',
        ]);

        $bs = BasicSetting::where('language_id', $langid)->firstOrFail();
        $be = BasicExtended::where('language_id', $langid)->firstOrFail();
        $bs->service_title = $request->service_title;
        $bs->service_subtitle = $request->service_subtitle;
        $bs->service_details_title = $request->service_details_title;
        $bs->portfolio_title = $request->portfolio_title;
        $bs->portfolio_subtitle = $request->portfolio_subtitle;
        $bs->portfolio_details_title = $request->portfolio_details_title;
        $bs->blog_details_title = $request->blog_details_title;
        $bs->contact_title = $request->contact_title;
        $bs->contact_subtitle = $request->contact_subtitle;
        $bs->gallery_title = $request->gallery_title;
        $bs->gallery_subtitle = $request->gallery_subtitle;
        $bs->team_title = $request->team_title;
        $bs->team_subtitle = $request->team_subtitle;
        $bs->faq_title = $request->faq_title;
        $bs->faq_subtitle = $request->faq_subtitle;
        $bs->blog_title = $request->blog_title;
        $bs->blog_subtitle = $request->blog_subtitle;
        $bs->quote_title = $request->quote_title;
        $bs->quote_subtitle = $request->quote_subtitle;
        $bs->error_title = $request->error_title;
        $bs->error_subtitle = $request->error_subtitle;
        $bs->save();


        $be->pricing_title = $request->pricing_title;
        $be->pricing_subtitle = $request->pricing_subtitle;
        $be->career_title = $request->career_title;
        $be->career_subtitle = $request->career_subtitle;
        $be->event_calendar_title = $request->event_calendar_title;
        $be->event_calendar_subtitle = $request->event_calendar_subtitle;
        $be->save();

        Session::flash('success', 'Page title & subtitles updated successfully!');
        return back();
    }

    public function script()
    {
        return view('admin.basic.scripts');
    }

    public function updatescript(Request $request)
    {

        $bss = BasicSetting::all();

        foreach ($bss as $bs) {
            $bs->tawk_to_script = $request->tawk_to_script;
            $bs->is_tawkto = $request->is_tawkto;
            $bs->is_disqus = $request->is_disqus;
            $bs->disqus_script = $request->disqus_script;
            $bs->google_analytics_script = $request->google_analytics_script;
            $bs->is_analytics = $request->is_analytics;
            $bs->appzi_script = $request->appzi_script;
            $bs->is_appzi = $request->is_appzi;
            $bs->addthis_script = $request->addthis_script;
            $bs->is_addthis = $request->is_addthis;
            $bs->is_recaptcha = $request->is_recaptcha;
            $bs->google_recaptcha_site_key = $request->google_recaptcha_site_key;
            $bs->google_recaptcha_secret_key = $request->google_recaptcha_secret_key;
            $bs->save();
        }


        $bes = BasicExtended::all();
        foreach ($bes as $key => $be) {
            $be->facebook_pexel_script = $request->facebook_pexel_script;
            $be->is_facebook_pexel = $request->is_facebook_pexel;
            $be->save();
        }

        Session::flash('success', 'Scripts updated successfully!');
        return back();
    }

    public function maintainance()
    {
        return view('admin.basic.maintainance');
    }

    public function updatemaintainance(Request $request)
    {
        $bss = BasicSetting::all();
        foreach ($bss as $bs) {
            $bs->maintainance_text = $request->maintainance_text;
            $bs->maintainance_mode = $request->maintainance_mode;
            $bs->save();
        }

        if ($request->maintainance_mode == 1) {
            Artisan::call('down');
        } else {
            @unlink('core/storage/framework/down');
        }

        Session::flash('success', 'Maintanance mode & page updated successfully!');
        return "success";
    }

    public function uploadmaintainance(Request $request)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json(['errors' => $validator->errors(), 'id' => 'maintainance_img']);
        }

        @unlink("assets/front/img/maintainance.png");
        $request->file('file')->move('assets/front/img/', 'maintainance.png');
        return response()->json(['status' => "success", 'image' => 'Maintanance image']);
    }

    public function announcement(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abs'] = $lang->basic_setting;

        return view('admin.basic.announcement', $data);
    }

    public function updateannouncement(Request $request, $langid)
    {

        $bs = BasicSetting::where('language_id', $langid)->firstOrFail();
        if ($request->filled('announcement_delay')) {
            $bs->announcement_delay = $request->announcement_delay;
        } else {
            $bs->announcement_delay = 0.00;
        }
        $bs->is_announcement = $request->is_announcement;
        $bs->save();

        Session::flash('success', 'Updated successfully!');
        return "success";
    }

    public function uploadannouncement(Request $request, $langid)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json(['errors' => $validator->errors(), 'id' => 'announcement_img']);
        }

        if ($request->hasFile('file')) {
            $bs = BasicSetting::where('language_id', $langid)->firstOrFail();
            @unlink('assets/front/img/' . $bs->announcement);
            $filename = uniqid() .'.'. $img->getClientOriginalExtension();
            $img->move('assets/front/img/', $filename);

            $bs->announcement = $filename;
            $bs->save();

        }

        return response()->json(['status' => "success", 'image' => 'Announcement image']);
    }


    public function avaibility(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abs'] = $lang->basic_setting;
        $data['abe'] = $lang->basic_extended;

        return view('admin.basic.avaibility', $data);
    }

    public function updateavaibility(Request $request, $langid)
    {

        $bs = BasicSetting::where('language_id', $langid)->firstOrFail();
        $bs->is_service = $request->is_service;
        $bs->is_portfolio = $request->is_portfolio;
        $bs->is_team = $request->is_team;
        $bs->is_gallery = $request->is_gallery;
        $bs->is_faq = $request->is_faq;
        $bs->is_blog = $request->is_blog;
        $bs->is_contact = $request->is_contact;
        $bs->is_quote = $request->is_quote;
        $bs->save();

        $be = BasicExtended::where('language_id', $langid)->firstOrFail();
        $be->is_order_package = $request->is_order_package;
        $be->is_packages = $request->is_packages;
        $be->is_career = $request->is_career;
        $be->is_calendar = $request->is_calendar;
        $be->save();

        Session::flash('success', 'Page avaibility updated successfully!');
        return back();
    }


    public function sections(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abs'] = $lang->basic_setting;

        return view('admin.basic.sections', $data);
    }

    public function updatesections(Request $request, $langid)
    {

        $bs = BasicSetting::where('language_id', $langid)->firstOrFail();
        $bs->feature_section = $request->feature_section;
        $bs->intro_section = $request->intro_section;
        $bs->service_section = $request->service_section;
        $bs->approach_section = $request->approach_section;
        $bs->statistics_section = $request->statistics_section;
        $bs->portfolio_section = $request->portfolio_section;
        $bs->testimonial_section = $request->testimonial_section;
        $bs->team_section = $request->team_section;
        $bs->news_section = $request->news_section;
        $bs->call_to_action_section = $request->call_to_action_section;
        $bs->partner_section = $request->partner_section;
        $bs->top_footer_section = $request->top_footer_section;
        $bs->copyright_section = $request->copyright_section;
        $bs->save();

        $be = BasicExtended::where('language_id', $langid)->firstOrFail();
        $be->pricing_section = $request->pricing_section;
        $be->save();

        Session::flash('success', 'Sections customized successfully!');
        return back();
    }

    public function cookiealert(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abe'] = $lang->basic_extended;

        return view('admin.basic.cookie', $data);
    }

    public function updatecookie(Request $request, $langid)
    {
        $request->validate([
            'cookie_alert_status' => 'required',
            'cookie_alert_text' => 'required',
            'cookie_alert_button_text' => 'required|max:25',
        ]);

        $be = BasicExtended::where('language_id', $langid)->firstOrFail();
        $be->cookie_alert_status = $request->cookie_alert_status;
        $be->cookie_alert_text = $request->cookie_alert_text;
        $be->cookie_alert_button_text = $request->cookie_alert_button_text;
        $be->save();

        Session::flash('success', 'Cookie alert updated successfully!');
        return back();
    }
}
