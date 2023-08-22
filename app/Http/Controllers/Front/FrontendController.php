<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BasicSetting as BS;
use App\BasicExtended as BE;
use App\Slider;
use App\Scategory;
use App\Jcategory;
use App\Portfolio;
use App\Feature;
use App\Point;
use App\Statistic;
use App\Testimonial;
use App\Gallery;
use App\Faq;
use App\Page;
use App\Member;
use App\Blog;
use App\Partner;
use App\Service;
use App\Job;
use App\Archive;
use App\Bcategory;
use App\Subscriber;
use App\Quote;
use App\Language;
use App\Package;
use App\PackageOrder;
use App\Admin;
use App\CalendarEvent;
use App\Mail\ContactMail;
use App\Mail\OrderPackage;
use App\Mail\OrderQuote;
use App\PackageInput;
use App\QuoteInput;
use Session;
use Validator;
use Config;
use Mail;

class FrontendController extends Controller
{
    public function __construct()
    {
        $bs = BS::first();
        $be = BE::first();

        Config::set('captcha.sitekey', $bs->google_recaptcha_site_key);
        Config::set('captcha.secret', $bs->google_recaptcha_secret_key);
    }

    public function index()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['currentLang'] = $currentLang;

        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;
        $lang_id = $currentLang->id;

        $data['sliders'] = Slider::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        $data['portfolios'] = Portfolio::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->limit(10)->get();
        $data['features'] = Feature::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        $data['points'] = Point::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        $data['statistics'] = Statistic::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        $data['testimonials'] = Testimonial::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        $data['faqs'] = Faq::orderBy('serial_number', 'ASC')->get();
        $data['members'] = Member::where('language_id', $lang_id)->get();
        $data['blogs'] = Blog::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->limit(6)->get();
        $data['partners'] = Partner::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        $data['packages'] = Package::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        return view('front.index', $data);
    }

    public function services(Request $request)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['currentLang'] = $currentLang;

        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;

        if ($bs->is_service == 0) {
            return view('errors.404');
        }
        $category = $request->category;
        $term = $request->term;

        if (!empty($category)) {
            $data['category'] = Scategory::findOrFail($category);
        }

        $data['services'] = Service::when($category, function ($query, $category) {
            return $query->where('scategory_id', $category);
        })->when($term, function ($query, $term) {
            return $query->where('title', 'like', '%' . $term . '%');
        })->when($currentLang, function ($query, $currentLang) {
            return $query->where('language_id', $currentLang->id);
        })->orderBy('serial_number', 'ASC')->paginate(6);

        return view('front.services', $data);
    }

    public function packages()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['currentLang'] = $currentLang;

        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;


        if ($be->is_packages == 0) {
            return view('errors.404');
        }

        $data['packages'] = Package::when($currentLang, function ($query, $currentLang) {
            return $query->where('language_id', $currentLang->id);
        })->orderBy('serial_number', 'ASC')->get();

        return view('front.packages', $data);
    }

    public function portfolios(Request $request)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['currentLang'] = $currentLang;

        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;

        if ($bs->is_portfolio == 0) {
            return view('errors.404');
        }
        $category = $request->category;
        $term = $request->term;

        if (!empty($category)) {
            $data['category'] = Scategory::findOrFail($category);
        }

        $data['portfolios'] = Portfolio::when($category, function ($query, $category) {
            $serviceIdArr = [];
            $serviceids = Service::select('id')->where('scategory_id', $category)->get();
            foreach ($serviceids as $key => $serviceid) {
                $serviceIdArr[] = $serviceid->id;
            }
            return $query->whereIn('service_id', $serviceIdArr);
        })->when($currentLang, function ($query, $currentLang) {
            return $query->where('language_id', $currentLang->id);
        })->orderBy('serial_number', 'ASC')->paginate(9);

        return view('front.portfolios', $data);
    }

    public function portfoliodetails($slug, $id)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $bs = $currentLang->basic_setting;

        if ($bs->is_portfolio == 0) {
            return view('errors.404');
        }

        $data['portfolio'] = Portfolio::findOrFail($id);



        return view('front.portfolio-details', $data);
    }

    public function servicedetails($slug, $id)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;
        $lang_id = $currentLang->id;

        if ($bs->is_service == 0) {
            return view('errors.404');
        }

        $data['service'] = Service::findOrFail($id);

        return view('front.service-details', $data);
    }

    public function careerdetails($slug, $id)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;
        $data['jcats'] = $currentLang->jcategories()->where('status', 1)->orderBy('serial_number', 'ASC')->get();
        $lang_id = $currentLang->id;


        if ($be->is_career == 0) {
            return view('errors.404');
        }

        $data['job'] = Job::findOrFail($id);

        $data['jobscount'] = Job::when($currentLang, function ($query, $currentLang) {
                                    return $query->where('language_id', $currentLang->id);
                                })->count();

        return view('front.career-details', $data);
    }

    public function blogs(Request $request)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['currentLang'] = $currentLang;

        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;
        $lang_id = $currentLang->id;

        if ($bs->is_blog == 0) {
            return view('errors.404');
        }
        $category = $request->category;
        if (!empty($category)) {
            $data['category'] = Bcategory::findOrFail($category);
        }
        $term = $request->term;
        $tag = $request->tag;
        $month = $request->month;
        $year = $request->year;
        $data['archives'] = Archive::orderBy('id', 'DESC')->get();
        $data['bcats'] = Bcategory::where('language_id', $lang_id)->where('status', 1)->orderBy('serial_number', 'ASC')->get();
        if (!empty($month) && !empty($year)) {
            $archive = true;
        } else {
            $archive = false;
        }

        $data['blogs'] = Blog::when($category, function ($query, $category) {
            return $query->where('bcategory_id', $category);
        })
        ->when($term, function ($query, $term) {
            return $query->where('title', 'like', '%' . $term . '%');
        })
        ->when($tag, function ($query, $tag) {
            return $query->where('tags', 'like', '%' . $tag . '%');
        })
        ->when($archive, function ($query) use ($month, $year) {
            return $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
        })
        ->when($currentLang, function ($query, $currentLang) {
            return $query->where('language_id', $currentLang->id);
        })->orderBy('serial_number', 'ASC')->paginate(6);
        return view('front.blogs', $data);
    }

    public function blogdetails($slug, $id)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;
        $lang_id = $currentLang->id;

        if ($bs->is_blog == 0) {
            return view('errors.404');
        }

        $data['blog'] = Blog::findOrFail($id);

        $data['archives'] = Archive::orderBy('id', 'DESC')->get();
        $data['bcats'] = Bcategory::where('status', 1)->where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        return view('front.blog-details', $data);
    }

    public function contact()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;

        if ($bs->is_contact == 0) {
            return view('errors.404');
        }
        if (session()->has('lang')) {
            $data['langg'] = Language::where('code', session('lang'))->first();
            return view('front.contact', $data);
        }
        return view('front.contact');
    }

    public function sendmail(Request $request)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;


        $messages = [
            'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
            'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
        ];

        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required'
        ];

        if ($bs->is_recaptcha == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $request->validate($rules, $messages);

        $bs =  BS::firstOrFail();
        $from = $request->email;
        $to = $bs->contact_mail;
        $subject = $request->subject;
        $message = $request->message;

        Mail::to($to)->send(new ContactMail($from, $subject, $message));

        Session::flash('success', 'Email sent successfully!');
        return back();
    }

    public function subscribe(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:subscribers'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $subsc = new Subscriber;
        $subsc->email = $request->email;
        $subsc->save();

        return "success";
    }

    public function quote()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;
        $lang_id = $currentLang->id;

        if ($bs->is_quote == 0) {
            return view('errors.404');
        }

        $data['services'] = Service::all();
        $data['inputs'] = QuoteInput::where('language_id', $lang_id)->get();
        $data['ndaIn'] = QuoteInput::find(10);
        return view('front.quote', $data);
    }

    public function sendquote(Request $request)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;
        $quote_inputs = $currentLang->quote_inputs;

        $nda = $request->file('nda');
        $ndaIn = QuoteInput::find(10);
        $allowedExts = array('doc', 'docx', 'pdf', 'rtf', 'txt', 'zip', 'rar');

        $messages = [
            'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
            'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
        ];

        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'nda' => [
                function ($attribute, $value, $fail) use ($nda, $allowedExts) {

                    $ext = $nda->getClientOriginalExtension();
                    if (!in_array($ext, $allowedExts)) {
                        return $fail("Only doc, docx, pdf, rtf, txt, zip, rar files are allowed");
                    }

                }
            ],
        ];


        if ($ndaIn->required == 1 && $ndaIn->active == 1) {
            if (!$request->hasFile('nda')) {
                $rules["nda"] = 'required';
            }
        }


        foreach ($quote_inputs as $input) {
            if ($input->required == 1) {
                $rules["$input->name"] = 'required';
            }
        }

        if ($bs->is_recaptcha == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $request->validate($rules, $messages);

        $fields = [];
        foreach ($quote_inputs as $key => $input) {
            $in_name = $input->name;
            if ($request["$in_name"]) {
                $fields["$in_name"] = $request["$in_name"];
            }
        }
        $jsonfields = json_encode($fields);
        $jsonfields = str_replace("\/","/",$jsonfields);


        $quote = new Quote;
        $quote->name = $request->name;
        $quote->email = $request->email;
        $quote->fields = $jsonfields;

        if ($request->hasFile('nda')) {
            $filename = uniqid() . '.' . $nda->getClientOriginalExtension();
            $nda->move('assets/front/ndas/', $filename);
            $quote->nda = $filename;
        }

        $quote->save();


        // send mail
        $from = $request->email;
        $to = $be->order_mail;
        $subject = "Quote Request Received";

        $fields = json_decode($quote->fields, true);


        Mail::to($to)->send(new OrderQuote($from, $subject, $fields));

        Session::flash('success', 'Quote request sent successfully');
        return back();
    }

    public function team()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;

        if ($bs->is_team == 0) {
            return view('errors.404');
        }
        $data['members'] = Member::when($currentLang, function ($query, $currentLang) {
            return $query->where('language_id', $currentLang->id);
        })->get();
        return view('front.team', $data);
    }

    public function career(Request $request)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;
        $data['jcats'] = $currentLang->jcategories()->where('status', 1)->orderBy('serial_number', 'ASC')->get();


        if ($be->is_career == 0) {
            return view('errors.404');
        }
        $category = $request->category;
        $term = $request->term;

        if (!empty($category)) {
            $data['category'] = Jcategory::findOrFail($category);
        }

        $data['jobs'] = Job::when($category, function ($query, $category) {
            return $query->where('jcategory_id', $category);
        })->when($term, function ($query, $term) {
            return $query->where('title', 'like', '%' . $term . '%');
        })->when($currentLang, function ($query, $currentLang) {
            return $query->where('language_id', $currentLang->id);
        })->orderBy('serial_number', 'ASC')->paginate(4);

        $data['jobscount'] = Job::when($currentLang, function ($query, $currentLang) {
            return $query->where('language_id', $currentLang->id);
        })->count();

        return view('front.career', $data);
    }

    public function calendar() {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $be = $currentLang->basic_extended;
        if ($be->is_calendar == 0) {
            return view('errors.404');
        }

        $lang_id = $currentLang->id;

        $events = CalendarEvent::where('language_id', $lang_id)->get();
        $formattedEvents = [];

        foreach ($events as $key => $event) {
            $formattedEvents["$key"]['title'] = $event->title;

            $startDate = strtotime($event->start_date);
            $formattedEvents["$key"]['start'] = date('Y-m-d H:i' ,$startDate);

            $endDate = strtotime($event->end_date);
            $formattedEvents["$key"]['end'] = date('Y-m-d H:i' ,$endDate);
        }

        $data["formattedEvents"] = $formattedEvents;

        return view('front.calendar', $data);
    }

    public function gallery()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;
        $lang_id = $currentLang->id;

        if ($bs->is_gallery == 0) {
            return view('errors.404');
        }
        $data['galleries'] = Gallery::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->paginate(12);
        return view('front.gallery', $data);
    }

    public function faq()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;
        $lang_id = $currentLang->id;

        if ($bs->is_faq == 0) {
            return view('errors.404');
        }
        $data['faqs'] = Faq::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        return view('front.faq', $data);
    }

    public function dynamicPage($slug, $id)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $data['page'] = Page::findOrFail($id);

        return view('front.dynamic', $data);
    }

    public function changeLanguage($lang)
    {
        session()->put('lang', $lang);
        app()->setLocale($lang);
        return redirect()->route('front.index');
    }

    public function packageorder($id)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $be = $currentLang->basic_extended;
        $lang_id = $currentLang->id;

        if ($be->is_order_package == 0) {
            return view('errors.404');
        }

        $data['package'] = Package::findOrFail($id);

        $data['inputs'] = PackageInput::where('language_id', $lang_id)->get();
        $data['ndaIn'] = PackageInput::find(1);

        return view('front.package-order', $data);
    }

    public function submitorder(Request $request)
    {

        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;
        $package_inputs = $currentLang->package_inputs;

        $nda = $request->file('nda');
        $ndaIn = PackageInput::find(1);
        $allowedExts = array('doc', 'docx', 'pdf', 'rtf', 'txt', 'zip', 'rar');

        $messages = [
            'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
            'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
        ];

        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'package_id' => 'required',
            'nda' => [
                function ($attribute, $value, $fail) use ($nda, $allowedExts) {

                    $ext = $nda->getClientOriginalExtension();
                    if (!in_array($ext, $allowedExts)) {
                        return $fail("Only doc, docx, pdf, rtf, txt, zip, rar files are allowed");
                    }

                }
            ],
        ];

        if ($ndaIn->required == 1 && $ndaIn->active == 1) {
            if (!$request->hasFile('nda')) {
                $rules["nda"] = 'required';
            }
        }

        foreach ($package_inputs as $input) {
            if ($input->required == 1) {
                $rules["$input->name"] = 'required';
            }
        }

        if ($bs->is_recaptcha == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $request->validate($rules, $messages);

        $fields = [];
        foreach ($package_inputs as $key => $input) {
            $in_name = $input->name;
            if ($request["$in_name"]) {
                $fields["$in_name"] = $request["$in_name"];
            }
        }
        $jsonfields = json_encode($fields);
        $jsonfields = str_replace("\/","/",$jsonfields);

        $package = Package::findOrFail($request->package_id);

        $in = $request->all();
        $in['name'] = $request->name;
        $in['email'] = $request->email;
        $in['fields'] = $jsonfields;

        if ($request->hasFile('nda')) {
            $filename = uniqid() . '.' . $nda->getClientOriginalExtension();
            $nda->move('assets/front/ndas/', $filename);
            $in['nda'] = $filename;
        }

        $in['package_title'] = $package->title;
        $in['package_currency'] = $package->currency;
        $in['package_price'] = $package->price;
        $in['package_description'] = $package->description;
        $po = PackageOrder::create($in);


        // send mail
        $from = $request->email;
        $to = $be->order_mail;
        // return response()->json(['from' => $from, 'to' => $to]);

        $subject = "Order placed for " . $package->title;

        $fields = json_decode($po->fields, true);

        Mail::to($to)->send(new OrderPackage($from, $subject, $fields, $package));

        Session::flash('success', 'Order placed successfully!');
        return back();
    }
}
