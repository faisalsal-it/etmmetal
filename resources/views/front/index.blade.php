
@extends('front.layout')

@section('meta-keywords', "$be->home_meta_keywords")
@section('meta-description', "$be->home_meta_description")


@section('content')
  <!--   hero area start   -->
  @if ($bs->home_version == 'static')
    @includeif('front.partials.static')
  @elseif ($bs->home_version == 'slider')
    @includeif('front.partials.slider')
  @elseif ($bs->home_version == 'video')
    @includeif('front.partials.video')
  @elseif ($bs->home_version == 'particles')
    @includeif('front.partials.particles')
  @elseif ($bs->home_version == 'water')
    @includeif('front.partials.water')
  @elseif ($bs->home_version == 'parallax')
    @includeif('front.partials.parallax')
  @endif
  <!--   hero area end    -->
<style>
   

.box {
  /* Rectangle 12: */
  width: 300px;
  height: 360px;
  background: rgba(255, 255, 255, 0.8);
  box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1), 0px 1px 3px 0px rgba(0, 0, 0, 0.05);
  border-radius: 5px;
  transition: 0.4s ease-in-out all;
  &:hover {
    /* Rectangle 12: */
   
    box-shadow: 0px 12px 20px 0px rgba(0, 0, 0, 0.13), 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
    border-radius: 5px;
  }
}



.action-btn {
  width: 50%;
  height: 10px;
  background: #3993FB;
  border-radius: 10px;
  margin: 35px auto 15px auto;
  opacity: 0;
  transition: 0.2s ease-in-out opacity;
}

.description {
  width: 50%;
  height: 10px;
  border-radius: 10px;
  background: rgba(0, 0, 0, 0.07);
  margin: 30px auto;
  &:before {
    display: block;
    content: " ";
    width: 100%;
    position: relative;
    top: 20px;
    height: 10px;
    border-radius: 5px;
    background: rgba(0, 0, 0, 0.07);
    margin: 10px auto;
  }
  &:after {
    display: block;
    content: " ";
    width: 70%;
    position: relative;
    top: 20px;
    height: 10px;
    border-radius: 10px;
    background: rgba(0, 0, 0, 0.07);
    margin: 10px auto;
  }
}

.circlecont {
  width: 110px;
  height: 110px;
  position: relative;
  margin: 80px auto 35px auto;
}

.circle {
  width: 110px;
  height: 110px;
  position: absolute;
  /* Mask: */
  background-image: linear-gradient(66deg, #32ABFC 0%, #4965FA 100%);
  border-radius: 110px;
}

.hover-circles .circle {
  opacity: 0.2;
  position: absolute;
  top: 0;
  background-blend-mode: multiply;
}

.circlecont .hover-circles .circle {
  transform: translate(0px, 0px);
  animation-fill-mode: forwards;
}

.box:hover {
  height: 400px;
}

.box:hover .action-btn {
  opacity: 1;
}

.box:hover .hover-circles .circle {
  &:first-child {
    animation: 1.8s circle-1 ease-in-out infinite;
  }
  &:nth-child(2) {
    animation: 2.5s circle-2 ease-in-out infinite;
  }
  &:nth-child(3) {
    animation: 2.7s circle-3 ease-in-out infinite;
  }
  &:nth-child(4) {
    animation: 2.4s circle-4 ease-in-out infinite;
  }
  &:nth-child(5) {
    animation: 3.2s circle-5 ease-in-out infinite;
  }
}

@keyframes circle-1 {
  0% {
    transform: translate(0px, 0px);
  }

  50% {
    transform: translate(15px, 15px);
  }
}


@keyframes circle-2 {
  0% {
    transform: translate(0px, 0px);
  }

  50% {
    transform: translate(10px, -15px);
  }
}


@keyframes circle-3 {
  0% {
    transform: translate(0px, 0px);
  }

  50% {
    transform: translate(-5px, -25px);
  }
}


@keyframes circle-4 {
  0% {
    transform: translate(0px, 0px);
  }

  50% {
    transform: translate(-15px, 20px);
  }
}


@keyframes circle-5 {
  0% {
    transform: translate(0px, 0px);
  }

  50% {
    transform: translate(15px, 20px);
  }
}
.garis_tepi1 {
     border: 2px solid red;
   }
   .fa_custom {
color: 
#ffffff
}
   </style>

  <!--    introduction area start   -->
  <div class="intro-section" @if($bs->feature_section == 0) style="margin-top: 0px;" @endif>
     <div class="container">
       @if ($bs->feature_section == 1)
       <div class="hero-features">
          <div class="row">
            @foreach ($features as $key => $feature)
                <style>
                    .sf{{$feature->id}}::after {
                        background-color: #{{$feature->color}};
                    }
                </style>
                <div class="col-md-3 col-sm-6 single-hero-feature sf{{$feature->id}}" style="background-color: #{{$feature->color}};">
                <div class="outer-container">
                    <div class="inner-container">
                        <div class="icon-wrapper">
                        <i class="{{$feature->icon}}"></i>
                        </div>
                        <h3>{{convertUtf8($feature->title)}}</h3>
                    </div>
                </div>
            </div>
            @endforeach
          </div>
       </div>
       @endif

       @if ($bs->intro_section == 1)
       <div class="row">
          <div class="col-lg-6 {{$rtl == 1 ? 'pl-lg-0' : 'pr-lg-0'}}">
             <div class="intro-txt">
                <span class="section-title">{{convertUtf8($bs->intro_section_title)}}</span>
                <h2 class="section-summary">{{convertUtf8($bs->intro_section_text)}} </h2>
                @if (!empty($bs->intro_section_button_url) && !empty($bs->intro_section_button_text))
                <a href="{{$bs->intro_section_button_url}}" class="intro-btn" target="_blank"><span>{{convertUtf8($bs->intro_section_button_text)}}</span></a>
                @endif
             </div>
          </div>
          <div class="col-lg-6 {{$rtl == 1 ? 'pr-lg-0' : 'pl-lg-0'}} px-md-3 px-0">
             <div class="intro-bg" style="background-image: url('{{asset('assets/front/img/'.$bs->intro_bg)}}'); background-size: cover;">
                @if (!empty($bs->intro_section_video_link))
                <a id="play-video" class="video-play-button" href="{{$bs->intro_section_video_link}}">
                  <span></span>
                </a>
                @endif
             </div>
          </div>
       </div>
       @endif
     </div>
  </div>
  <!--    introduction area end   -->


  @if ($bs->service_section == 1)
  <!--   service section start   -->
  <div class="service-categories">
    <div class="container">
       <div class="row text-center">
          <div class="col-lg-6 offset-lg-3">
             <span class="section-title">{{convertUtf8($bs->service_section_title)}}</span>
             <h2 class="section-summary">{{convertUtf8($bs->service_section_subtitle)}}</h2>
          </div>
       </div>
    </div>
    <div class="container">
      <div class="row">
        @foreach ($scats as $key => $scat)
          <div class="col-xl-3 col-lg-4 col-sm-6">
            <div class="single-category">
              @if (!empty($scat->image))
                <div class="img-wrapper">
                    <img src="{{asset('assets/front/img/service_category_icons/'.$scat->image)}}" alt="">
                </div>
              @endif
              <div class="text">
                <h4>{{convertUtf8($scat->name)}}</h4>
                <p>{{convertUtf8($scat->short_text)}}</p>
                <a href="{{route('front.services', ['category'=>$scat->id])}}" class="readmore">{{__('Read More')}}</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
  <!--   service section end   -->
  @endif
  

  @if ($bs->approach_section == 1)
  <!--   how we do section start   -->
  <div class="approach-section">
     <div class="container">
        <div class="row">
           <div class="col-lg-6">
              <div class="approach-summary">
                 <span class="section-title">{{convertUtf8($bs->approach_title)}}</span>
                 <h2 class="section-summary">{{convertUtf8($bs->approach_subtitle)}}</h2>
                
              </div>
           </div>
           <div class="col-lg-6">
              <ul class="approach-lists">
                 @foreach ($points as $key => $point)
                   <li class="single-approach">
                      <div class="approach-icon-wrapper"><i class="{{$point->icon}}"></i></div>
                      <div class="approach-text">
                         <h4>{{convertUtf8($point->title)}}</h4>
                         <p>{{convertUtf8($point->short_text)}}</p>
                      </div>
                   </li>
                 @endforeach
              </ul>
           </div>
        </div>
     </div>
  </div>
  <!--   how we do section end   -->
  @endif
  
  <section class="section m-none">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-6 wow" data-aos="fade-right">
	<br>
<img class="img-responsive center-block" src="lingkungan.jpg" height="270" width="450"/>
</br>
</div>

<div class="col-xs-12 col-sm-12 col-md-5 col-md-offset-1">
<div class="heading heading-1 mt-70 wow fadeInUp" data-wow-duration="1s">
	<br>
<h2 class="heading--title">Sustainability And CSR</h2>
</br>
<div class="heading--divider"></div>
</div>

<div class="about-content wow fadeInUp" data-wow-duration="1s" >
<p> Had been growing bushes around marine environments and mountain tops and encouraging frequent mineral drinking water analysis study of tolerable mineral water and fish pond.
Also, i am offering free of charge prophylactic pandemic routines throughout horticulture section by way of undertaking adjoining the environmental analysis each one fourth hereafter; many people will certainly defend bordering environment and home significantly more try really hard to by means of widely saying the results for the community residents. </p>


</div>
</div>
</div>
</div>
</section>

<br><br><br>

  @if ($bs->statistics_section == 1)
  <!--    statistics section start    -->
  <div class="statistics-section @if($bs->home_version != 'parallax') statistics-bg @endif" id="statisticsSection" @if($bs->home_version == 'parallax') data-parallax="scroll" data-speed="0.2" data-image-src="{{asset('assets/front/img/statistic_bg.jpg')}}" @endif>
     <div class="statistics-container">
        <div class="container">
           <div class="row no-gutters">
             @foreach ($statistics as $key => $statistic)
               <div class="col-lg-3 col-md-6">
                  <div class="round"
                     data-value="1"
                     data-number="{{convertUtf8($statistic->quantity)}}"
                     data-size="200"
                     data-thickness="6"
                     data-fill="{
                     &quot;color&quot;: &quot;#{{$bs->base_color}}&quot;
                     }">
                     <strong></strong>
                     <h5><i class="{{$statistic->icon}}"></i> {{convertUtf8($statistic->title)}}</h5>
                  </div>
               </div>
             @endforeach
           </div>
        </div>
     </div>
     <div class="statistic-overlay"></div>
  </div>
  <!--    statistics section end    -->
  @endif
                  </br></br></br>
                  <head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
                  
<section class="section m-none">
  <div class="row">
		<div class="col-md-12 center">
			<center><h2> YOU MAY ALSO <strong> LOOK FOR : </strong></h2></center>
		</div>
	</div>
   </br></br></br></br>
   <br><br>
   <center>
      
  <div class="row">
   
  <div class="col-md-3 col-sm-7">
  <div class="container">
  <div class="box">
   <br>
    <div class="title">
    <center><span><strong>World Tin Ingot Prices</strong></span></center>
    </div>
                  </br>
    <div class="description"></div>
    <div class="circlecont">
      <div class="circle ">
      <br>
      <center><i class="fa fa-bar-chart-o fa_custom fa-4x"></i></center>
                  </br>
      </div>
      <div class="hover-circles">
        <div class="circle">
        </div>
       
      </div>
    </div>
    <div class="action-btn">
      <br>
    <center><a href="https://www.lme.com/en/Metals/Non-ferrous/LME-Tin#Trading+day+summary" target="_blank" class="btn btn-tertiary mr-xs mb-lg">Click here</a></center>
      </br>
    </div>
  </div>
</div>
</div>
                  
<div class="col-md-3 col-sm-7">
  <div class="container">
  <div class="box">
   <br>
    <div class="title">
    <center><span><strong>World Aluminum Prices</strong></span></center>
    </div>
   </br>
    <div class="description"></div>
    <div class="circlecont">
      <div class="circle ">
      <br>
      <center><i class="fa fa-bar-chart-o fa_custom fa-4x"></i></center>
                  </br>
      </div>
      <div class="hover-circles">
        <div class="circle">
        
        </div>
       
      </div>
    </div>
    <div class="action-btn">
      <br>
    <center><a href="https://www.lme.com/en/Metals/Non-ferrous/LME-Aluminium#Trading+day+summary" target="_blank" class="btn btn-tertiary mr-xs mb-lg">Click here</a></center>
                  </br> 
   </div>
  </div>
</div>
</div>
<div class="col-md-3 col-sm-7">
  <div class="container">
  <div class="box">
   <br>
    <div class="title">
    <center><span><strong>World Copper Prices</strong></span></center>
    </div>
   </br>
    <div class="description"></div>
    <div class="circlecont">
      <div class="circle ">
      <br>
      <center><i class="fa fa-bar-chart-o fa_custom fa-4x"></i></center>
                  </br>
      </div>
      <div class="hover-circles">
        <div class="circle"></div>
       
      </div>
    </div>
    <div class="action-btn">
      <br>
    <center><a href="https://www.lme.com/en/Metals/Non-ferrous/LME-Tin#Trading+day+summary" target="_blank" class="btn btn-tertiary mr-xs mb-lg">Click here</a></center>
       </br> 
   </div>
  </div>
</div>
</div>
<div class="col-md-3 col-sm-7">
  <div class="container">
  <div class="box">
   <br>
    <div class="title">
    <center><span><strong>World Aluminium Alloy</strong></span></center>
    </div>
   </br>
    <div class="description"></div>
    <div class="circlecont">
      <div class="circle">
         <br>
      <center><i class="fa fa-bar-chart-o fa_custom fa-4x"></i></center>
                  </br>
      </div>
      <div class="hover-circles">
        <div class="circle">
        </div>
      </div>
    </div>
    <div class="action-btn">
      <br>
    <center><a href="https://www.lme.com/en/Metals/Non-ferrous/LME-Aluminium-Alloy#Trading+day+summary" target="_blank" class="btn btn-tertiary mr-xs mb-lg">Click here</a></center>
                  </br> 
   </div>
  </div>
 </div>
</div>
</div>
                  </br></br>
</center>
</section>


  @if ($bs->portfolio_section == 1)
  <!--    case section start   -->
  <div class="case-section">
     <div class="container">
        <div class="row text-center">
           <div class="col-lg-6 offset-lg-3">
              <span class="section-title">{{convertUtf8($bs->portfolio_section_title)}}</span>
              <h2 class="section-summary">{{convertUtf8($bs->portfolio_section_text)}}</h2>
           </div>
        </div>
     </div>
     <div class="container-fluid">
        <div class="row">
           <div class="col-md-12">
              <div class="case-carousel owl-carousel owl-theme">
                 @foreach ($portfolios as $key => $portfolio)
                   <div class="single-case single-case-bg-1" style="background-image: url('{{asset('assets/front/img/portfolios/featured/'.$portfolio->featured_image)}}');">
                      <div class="outer-container">
                         <div class="inner-container">
                            <h4>{{convertUtf8(strlen($portfolio->title)) > 36 ? convertUtf8(substr($portfolio->title, 0, 36)) . '...' : convertUtf8($portfolio->title)}}</h4>
                            @if (!empty($portfolio->service))
                            <p>{{convertUtf8($portfolio->service->title)}}</p>
                            @endif

                            <a href="{{route('front.portfoliodetails', [$portfolio->slug, $portfolio->id])}}" class="readmore-btn"><span>{{__('Read More')}}</span></a>

                         </div>
                      </div>
                   </div>
                 @endforeach
              </div>
           </div>
        </div>
     </div>
  </div>
  <!--    case section end   -->
  @endif

  @if ($bs->testimonial_section == 1)
  <!--   Testimonial section start    -->
  <div class="testimonial-section pb-115">
     <div class="container">
        <div class="row text-center">
           <div class="col-lg-6 offset-lg-3">
              <span class="section-title">{{convertUtf8($bs->testimonial_title)}}</span>
              <h2 class="section-summary">{{convertUtf8($bs->testimonial_subtitle)}}</h2>
           </div>
        </div>
        <div class="row">
           <div class="col-md-12">
              <div class="testimonial-carousel owl-carousel owl-theme">
                 @foreach ($testimonials as $key => $testimonial)
                   <div class="single-testimonial">
                      <div class="img-wrapper"><img src="{{asset('assets/front/img/testimonials/'.$testimonial->image)}}" alt=""></div>
                      <div class="client-desc">
                         <p class="comment">{{convertUtf8($testimonial->comment)}}</p>
                         <h6 class="name">{{convertUtf8($testimonial->name)}}</h6>
                         <p class="rank">{{convertUtf8($testimonial->rank)}}</p>
                      </div>
                   </div>
                 @endforeach
              </div>
           </div>
        </div>
     </div>
  </div>
  <!--   Testimonial section end    -->
  @endif


  @if ($bs->team_section == 1)
  <!--    team section start   -->
  <div class="team-section section-padding" @if($bs->home_version != 'parallax') style="background-image: url('{{asset('assets/front/img/'.$bs->team_bg)}}'); background-size:cover;" @endif @if($bs->home_version == 'parallax') data-parallax="scroll" data-speed="0.2" data-image-src="{{asset('assets/front/img/'.$bs->team_bg)}}" @endif>
     <div class="team-content">
        <div class="container">
           <div class="row text-center">
              <div class="col-lg-6 offset-lg-3">
                 <span class="section-title">{{convertUtf8($bs->team_section_title)}}</span>
                 <h2 class="section-summary">{{convertUtf8($bs->team_section_subtitle)}}</h2>
              </div>
           </div>
           <div class="row">
              <div class="team-carousel common-carousel owl-carousel owl-theme">
                @foreach ($members as $key => $member)
                 <div class="single-team-member">
                    <div class="team-img-wrapper">
                       <img src="{{asset('assets/front/img/members/'.$member->image)}}" alt="">
                       <div class="social-accounts">
                          <ul class="social-account-lists">
                             @if (!empty($member->facebook))
                               <li class="single-social-account"><a href="{{$member->facebook}}"><i class="fab fa-facebook-f"></i></a></li>
                             @endif
                             @if (!empty($member->twitter))
                               <li class="single-social-account"><a href="{{$member->twitter}}"><i class="fab fa-twitter"></i></a></li>
                             @endif
                             @if (!empty($member->linkedin))
                               <li class="single-social-account"><a href="{{$member->linkedin}}"><i class="fab fa-linkedin-in"></i></a></li>
                             @endif
                             @if (!empty($member->instagram))
                               <li class="single-social-account"><a href="{{$member->instagram}}"><i class="fab fa-instagram"></i></a></li>
                             @endif
                          </ul>
                       </div>
                    </div>
                    <div class="member-info">
                       <h5 class="member-name">{{convertUtf8($member->name)}}</h5>
                       <small>{{convertUtf8($member->rank)}}</small>
                    </div>
                 </div>
                @endforeach
              </div>
           </div>
        </div>
     </div>
     <div class="team-overlay"></div>
  </div>
  <!--    team section end   -->
  @endif


  @if ($be->pricing_section == 1)
  <!-- pricing begin -->
  <div class="pricing-tables">
     <div class="container">
       <div class="row text-center">
          <div class="col-lg-6 offset-lg-3">
             <span class="section-title">{{convertUtf8($be->pricing_title)}}</span>
             <h2 class="section-summary">{{convertUtf8($be->pricing_subtitle)}}</h2>
          </div>
       </div>
        <div class="pricing-carousel common-carousel owl-carousel owl-theme">
          @foreach ($packages as $key => $package)
            <div class="single-pricing-table">
               <span class="title">{{convertUtf8($package->title)}}</span>
               <div class="price">
                  <h1>{{$package->currency}}{{$package->price}}</h1>
               </div>
               <div class="features">
                  {!! convertUtf8($package->description) !!}
               </div>

               <a href="{{route('front.packageorder.index', $package->id)}}" class="pricing-btn">{{__('Place Order')}}</a>

            </div>
          @endforeach
        </div>
     </div>
  </div>
  <!-- pricing end -->
  @endif



  @if ($bs->news_section == 1)
  <!--    blog section start   -->
  <div class="blog-section section-padding">
     <div class="container">
        <div class="row text-center">
           <div class="col-lg-6 offset-lg-3">
              <span class="section-title">{{convertUtf8($bs->blog_section_title)}}</span>
              <h2 class="section-summary">{{convertUtf8($bs->blog_section_subtitle)}}</h2>
           </div>
        </div>
        <div class="blog-carousel owl-carousel owl-theme common-carousel">
           @foreach ($blogs as $key => $blog)
              <div class="single-blog">
                 <div class="blog-img-wrapper">
                    <img src="{{asset('assets/front/img/blogs/'.$blog->main_image)}}" alt="">
                 </div>
                 <div class="blog-txt">
                    @php
                        $blogDate = \Carbon\Carbon::parse($blog->created_at)->locale("$currentLang->code");
                        $blogDate = $blogDate->translatedFormat('jS F, Y');
                    @endphp

                    <p class="date"><small>{{__('By')}} <span class="username">{{__('Admin')}}</span></small> | <small>{{$blogDate}}</small> </p>

                    <h4 class="blog-title"><a href="{{route('front.blogdetails', [$blog->slug, $blog->id])}}">{{convertUtf8(strlen($blog->title)) > 40 ? convertUtf8(substr($blog->title, 0, 40)) . '...' : convertUtf8($blog->title)}}</a></h4>


                    <p class="blog-summary">{!! convertUtf8(strlen(strip_tags($blog->content)) > 100) ? convertUtf8(substr(strip_tags($blog->content), 0, 100)) . '...' : convertUtf8(strip_tags($blog->content)) !!}</p>


                    <a href="{{route('front.blogdetails', [$blog->slug, $blog->id])}}" class="readmore-btn"><span>{{__('Read More')}}</span></a>

                 </div>
              </div>
           @endforeach
        </div>
     </div>
  </div>
  <!--    blog section end   -->
  @endif


  @if ($bs->call_to_action_section == 1)
  <!--    call to action section start    -->
  <div class="cta-section" style="background-image: url('{{asset('assets/front/img/'.$bs->cta_bg)}}')">
     <div class="container">
        <div class="cta-content">
           <div class="row">
              <div class="col-md-9 col-lg-7">
                 <h3>{{convertUtf8($bs->cta_section_text)}}</h3>
              </div>
              <div class="col-md-3 col-lg-5 contact-btn-wrapper">
                 <a href="{{$bs->cta_section_button_url}}" class="boxed-btn contact-btn"><span>{{convertUtf8($bs->cta_section_button_text)}}</span></a>
              </div>
           </div>
        </div>
     </div>
     <div class="cta-overlay"></div>
  </div>
  <!--    call to action section end    -->
  @endif


  @if ($bs->partner_section == 1)
  <!--   partner section start    -->
  <div class="partner-section">
     <div class="container top-border">
        <div class="row">
           <div class="col-md-12">
              <div class="partner-carousel owl-carousel owl-theme common-carousel">
                 @foreach ($partners as $key => $partner)
                   <a class="single-partner-item d-block" href="{{$partner->url}}" target="_blank">
                      <div class="outer-container">
                         <div class="inner-container">
                            <img src="{{asset('assets/front/img/partners/'.$partner->image)}}" alt="">
                         </div>
                      </div>
                   </a>
                 @endforeach
              </div>
           </div>
        </div>
     </div>
  </div>
  <!--   partner section end    -->
  @endif

@endsection

