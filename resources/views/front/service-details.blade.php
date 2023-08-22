@extends('front.layout')

@section('pagename')
 - {{__('Service')}} - {{convertUtf8($service->title)}}
@endsection

@section('meta-keywords', "$service->meta_keywords")
@section('meta-description', "$service->meta_description")

@section('content')
  <!--   breadcrumb area start   -->
  <div class="breadcrumb-area service-details" style="background-image: url('{{asset('assets/front/img/' . $bs->breadcrumb)}}');background-size:cover;">
     <div class="container">
        <div class="breadcrumb-txt service-details">
           <div class="row">
              <div class="col-xl-7 col-lg-8 col-sm-10">
                 <span>{{convertUtf8($bs->service_details_title)}}</span>
                 <h1>{{convertUtf8($service->title)}}</h1>
                 <ul class="breadcumb">
                    <li><a href="{{route('front.index')}}">{{__('Home')}}</a></li>
                    <li>{{__('Service Details')}}</li>
                 </ul>
              </div>
           </div>
        </div>
     </div>
     <div class="breadcrumb-area-overlay"></div>
  </div>
  <!--   breadcrumb area end    -->


  <!--    services details section start   -->
  <div class="service-details-section">
     <div class="container">
        <div class="row">
           <div class="col-lg-7">
              <div class="service-details">
                {!! convertUtf8($service->content) !!}
              </div>
           </div>
           <!--    service sidebar start   -->
           <div class="col-lg-4">
             <div class="blog-sidebar-widgets">
                <div class="searchbar-form-section">
                   <form action="{{route('front.services')}}">
                      <div class="searchbar">
                         <input name="category" type="hidden" value="{{request()->input('category')}}">
                         <input name="term" type="text" placeholder="{{__('Search Services')}}" value="{{request()->input('term')}}">
                         <button type="submit"><i class="fa fa-search"></i></button>
                      </div>
                   </form>
                </div>
             </div>
             
           </div>
           <!--    service sidebar end   -->
        </div>
     </div>
  </div>
  <!--    services details section end   -->

@endsection
