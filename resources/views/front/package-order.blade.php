@extends('front.layout')

@section('pagename')
 - {{__('Order of') . ' ' . $package->title}}
@endsection

@section('meta-keywords', "$package->meta_keywords")
@section('meta-description', "$package->meta_description")

@section('content')
  <!--   breadcrumb area start   -->
  <div class="breadcrumb-area cases" style="background-image: url('{{asset('assets/front/img/' . $bs->breadcrumb)}}');background-size:cover;">
     <div class="container">
        <div class="breadcrumb-txt">
           <div class="row">
              <div class="col-xl-7 col-lg-8 col-sm-10">
                 <span>{{__('Package Order')}}</span>
                 <h1>{{__('Place Order for')}} <p class="d-inline-block" style="color:#{{$bs->base_color}};">{{convertUtf8($package->title)}}</p></h1>
                 <ul class="breadcumb">
                    <li><a href="{{route('front.index')}}">{{__('Home')}}</a></li>
                    <li>{{__('Package Order')}}</li>
                 </ul>
              </div>
           </div>
        </div>
     </div>
     <div class="breadcrumb-area-overlay"></div>
  </div>
  <!--   breadcrumb area end    -->


  <!--   quote area start   -->
  <div class="quote-area">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <form action="{{route('front.packageorder.submit')}}" enctype="multipart/form-data" method="POST">
            @csrf
            <input type="hidden" name="package_id" value="{{$package->id}}">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-element mb-4">
                        <label>{{__('Name')}} <span>**</span></label>
                        <input name="name" type="text" value="{{old("name")}}" placeholder="{{__('Enter Name')}}">

                        @if ($errors->has("name"))
                        <p class="text-danger mb-0">{{$errors->first("name")}}</p>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-element mb-4">
                        <label>{{__('Email')}} <span>**</span></label>
                        <input name="email" type="text" value="{{old("email")}}" placeholder="{{__('Enter Email Address')}}">

                        @if ($errors->has("email"))
                        <p class="text-danger mb-0">{{$errors->first("email")}}</p>
                        @endif
                    </div>
                </div>

                @foreach ($inputs as $input)

                    <div class="{{$input->type == 4 || $input->type == 3 ? 'col-lg-12' : 'col-lg-6'}}">
                        <div class="form-element mb-4">
                            @if ($input->type == 1)
                                <label>{{convertUtf8($input->label)}} @if($input->required == 1) <span>**</span> @endif</label>
                                <input name="{{$input->name}}" type="text" value="{{old("$input->name")}}" placeholder="{{convertUtf8($input->placeholder)}}">
                            @endif

                            @if ($input->type == 2)
                                <label>{{convertUtf8($input->label)}} @if($input->required == 1) <span>**</span> @endif</label>
                                <select name="{{$input->name}}">
                                    <option value="" selected disabled>{{convertUtf8($input->placeholder)}}</option>
                                    @foreach ($input->package_input_options as $option)
                                        <option value="{{convertUtf8($option->name)}}" {{old("$input->name") == convertUtf8($option->name) ? 'selected' : ''}}>{{convertUtf8($option->name)}}</option>
                                    @endforeach
                                </select>
                            @endif

                            @if ($input->type == 3)
                                <label>{{convertUtf8($input->label)}} @if($input->required == 1) <span>**</span> @endif</label>
                                @foreach ($input->package_input_options as $option)
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" id="customCheckboxInline{{$option->id}}" name="{{$input->name}}[]" class="custom-control-input" value="{{convertUtf8($option->name)}}" {{is_array(old("$input->name")) && in_array(convertUtf8($option->name), old("$input->name")) ? 'checked' : ''}}>
                                        <label class="custom-control-label" for="customCheckboxInline{{$option->id}}">{{convertUtf8($option->name)}}</label>
                                    </div>
                                @endforeach
                            @endif

                            @if ($input->type == 4)
                                <label>{{convertUtf8($input->label)}} @if($input->required == 1) <span>**</span> @endif</label>
                                <textarea name="{{$input->name}}" id="" cols="30" rows="10" placeholder="{{convertUtf8($input->placeholder)}}">{{old("$input->name")}}</textarea>
                            @endif

                            @if ($errors->has("$input->name"))
                            <p class="text-danger mb-0">{{$errors->first("$input->name")}}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($ndaIn->active == 1)
            <div class="row mb-4">
              <div class="col-lg-12">
                <div class="form-element mb-2">
                  <label>{{__('NDA File')}} @if($ndaIn->required == 1) <span>**</span> @endif</label>
                  <input type="file" name="nda" value="">
                </div>
                <p class="text-warning mb-0">** {{__('Only doc, docx, pdf, rtf, txt, zip, rar files are allowed')}}</p>
                @if ($errors->has('nda'))
                  <p class="text-danger mb-0">{{$errors->first('nda')}}</p>
                @endif
              </div>
            </div>
            @endif

            @if ($bs->is_recaptcha == 1)
              <div class="row mb-4">
                <div class="col-lg-12">
                  {!! NoCaptcha::renderJs() !!}
                  {!! NoCaptcha::display() !!}
                  @if ($errors->has('g-recaptcha-response'))
                    @php
                        $errmsg = $errors->first('g-recaptcha-response');
                    @endphp
                    <p class="text-danger mb-0">{{__("$errmsg")}}</p>
                  @endif
                </div>
              </div>
            @endif

            <div class="row">
              <div class="col-lg-12">
                <button type="submit" name="button">{{__('Submit')}}</button>
              </div>
            </div>
          </form>
        </div>
        <div class="col-lg-4 mt-4 mt-lg-0">
          <div class="single-pricing-table package-order">
             <span class="title">{{convertUtf8($package->title)}}</span>
             <div class="price">
                <h1>{{$package->currency}}{{$package->price}}</h1>
             </div>
             <div class="features">
                {!! convertUtf8($package->description) !!}
             </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--   quote area end   -->
@endsection
