<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Service;
use App\Scategory;
use App\Language;
use Validator;
use Session;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->first();

        $lang_id = $lang->id;
        $data['services'] = Service::where('language_id', $lang_id)->orderBy('id', 'DESC')->paginate(10);

        $data['lang_id'] = $lang_id;

        return view('admin.service.service.index', $data);
    }

    public function edit($id)
    {
        $data['service'] = Service::findOrFail($id);
        $data['ascats'] = Scategory::where('status', 1)->where('language_id', $data['service']->language_id)->get();
        return view('admin.service.service.edit', $data);
    }

    public function upload(Request $request)
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
            return response()->json(['errors' => $validator->errors(), 'id' => 'service']);
        }

        $filename = time() . '.' . $img->getClientOriginalExtension();
        $request->session()->put('service_image', $filename);
        $request->file('file')->move('assets/front/img/services/', $filename);
        return response()->json(['status' => "session_put", "image" => "service_image", 'filename' => $filename]);
    }

    public function store(Request $request)
    {
        $messages = [
            'language_id.required' => 'The language field is required'
        ];

        $slug = make_slug($request->title);

        $rules = [
            'language_id' => 'required',
            'service_image' => 'required',
            'title' => 'required|max:255',
            'serial_number' => 'required',
            'category' => 'required',
            'content' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $service = new Service;
        $service->language_id = $request->language_id;
        $service->title = $request->title;
        $service->main_image = $request->service_image;
        $service->slug = $slug;
        $service->scategory_id = $request->category;
        $service->meta_description = $request->meta_description;
        $service->meta_keywords = $request->meta_keywords;
        $service->serial_number = $request->serial_number;
        $service->content = $request->content;
        $service->save();

        Session::flash('success', 'Service added successfully!');
        return "success";
    }

    public function update(Request $request)
    {
        $slug = make_slug($request->title);
        $service = Service::findOrFail($request->service_id);

        $rules = [
            'title' => 'required|max:255',
            'category' => 'required',
            'content' => 'required',
            'serial_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $service->title = $request->title;
        $service->slug = $slug;
        $service->scategory_id = $request->category;
        $service->serial_number = $request->serial_number;
        $service->meta_keywords = $request->meta_keywords;
        $service->meta_description = $request->meta_description;
        $service->content = $request->content;
        $service->save();

        Session::flash('success', 'Service updated successfully!');
        return "success";
    }

    public function uploadUpdate(Request $request, $id)
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
            return response()->json(['errors' => $validator->errors(), 'id' => 'service_image']);
        }

        $service = Service::findOrFail($id);
        if ($request->hasFile('file')) {
            $filename = time() . '.' . $img->getClientOriginalExtension();
            $request->file('file')->move('assets/front/img/services/', $filename);
            @unlink('assets/front/img/services/' . $service->main_image);
            $service->main_image = $filename;
            $service->save();
        }

        return response()->json(['status' => "success", "image" => "Service image", 'service' => $service]);
    }

    public function delete(Request $request)
    {
        $service = Service::findOrFail($request->service_id);
        @unlink('assets/front/img/services/' . $service->main_image);
        $service->delete();

        Session::flash('success', 'Service deleted successfully!');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $service = Service::findOrFail($id);
            @unlink('assets/front/img/services/' . $service->main_image);
            $service->delete();
        }

        Session::flash('success', 'Services deleted successfully!');
        return "success";
    }

    public function getcats($langid)
    {
        $scategories = Scategory::where('language_id', $langid)->get();

        return $scategories;
    }
}
