@extends('admin.layout')

@php
$selLang = \App\Language::where('code', request()->input('language'))->first();
@endphp
@if(!empty($selLang) && $selLang->rtl == 1)
@section('styles')
<style>
    form:not(.modal-form) input,
    form:not(.modal-form) textarea,
    form:not(.modal-form) select,
    select[name='language'] {
        direction: rtl;
    }
    form:not(.modal-form) .note-editor.note-frame .note-editing-area .note-editable {
        direction: rtl;
        text-align: right;
    }
</style>
@endsection
@endif

@section('content')
<div class="page-header">
   <h4 class="page-title">Services</h4>
   <ul class="breadcrumbs">
      <li class="nav-home">
         <a href="{{route('admin.dashboard')}}">
         <i class="flaticon-home"></i>
         </a>
      </li>
      <li class="separator">
         <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
         <a href="#">Service Page</a>
      </li>
      <li class="separator">
         <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
         <a href="#">Services</a>
      </li>
   </ul>
</div>
<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-header">
            <div class="row">
               <div class="col-lg-4">
                  <div class="card-title d-inline-block">Services</div>
               </div>
               <div class="col-lg-3">
                  @if (!empty($langs))
                  <select name="language" class="form-control" onchange="window.location='{{url()->current() . '?language='}}'+this.value">
                     <option value="" selected disabled>Select a Language</option>
                     @foreach ($langs as $lang)
                     <option value="{{$lang->code}}" {{$lang->code == request()->input('language') ? 'selected' : ''}}>{{$lang->name}}</option>
                     @endforeach
                  </select>
                  @endif
               </div>
               <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                  <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> Add Service</a>
                  <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete" data-href="{{route('admin.service.bulk.delete')}}"><i class="flaticon-interface-5"></i> Delete</button>
               </div>
            </div>
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-lg-12">
                  @if (count($services) == 0)
                  <h3 class="text-center">NO SERVICE FOUND</h3>
                  @else
                  <div class="table-responsive">
                     <table class="table table-striped mt-3">
                        <thead>
                           <tr>
                              <th scope="col">
                                 <input type="checkbox" class="bulk-check" data-val="all">
                              </th>
                              <th scope="col">Image</th>
                              <th scope="col">Title</th>
                              <th scope="col">Category</th>
                              <th scope="col">Serial Number</th>
                              <th scope="col">Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($services as $key => $service)
                           <tr>
                              <td>
                                 <input type="checkbox" class="bulk-check" data-val="{{$service->id}}">
                              </td>
                              <td><img src="{{asset('assets/front/img/services/'.$service->main_image)}}" alt="" width="70"></td>
                              <td>{{strlen(convertUtf8($service->title)) > 100 ? convertUtf8(substr($service->title, 0, 100)) . '...' : convertUtf8($service->title)}}</td>
                              <td>
                                 @if (!empty($service->scategory))
                                 {{convertUtf8($service->scategory->name)}}
                                 @endif
                              </td>
                              <td>{{$service->serial_number}}</td>
                              <td>
                                 <a class="btn btn-secondary btn-sm" href="{{route('admin.service.edit', $service->id) . '?language=' . request()->input('language')}}">
                                 <span class="btn-label">
                                 <i class="fas fa-edit"></i>
                                 </span>
                                 Edit
                                 </a>
                                 <form class="deleteform d-inline-block" action="{{route('admin.service.delete')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="service_id" value="{{$service->id}}">
                                    <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                    <span class="btn-label">
                                    <i class="fas fa-trash"></i>
                                    </span>
                                    Delete
                                    </button>
                                 </form>
                              </td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
                  @endif
               </div>
            </div>
         </div>
         <div class="card-footer">
            <div class="row">
               <div class="d-inline-block mx-auto">
                  {{$services->appends(['language' => request()->input('language')])->links()}}
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- Create Service Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Add Service</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form class="mb-3 dm-uploader drag-and-drop-zone modal-form" enctype="multipart/form-data" action="{{route('admin.service.upload')}}" method="POST">
               <div class="form-row px-2">
                  <div class="col-12 mb-2">
                     <label for=""><strong>Image **</strong></label>
                  </div>
                  <div class="col-md-12 d-md-block d-sm-none mb-3">
                     <img src="{{asset('assets/admin/img/noimage.jpg')}}" alt="..." class="img-thumbnail">
                  </div>
                  <div class="col-sm-12">
                     <div class="from-group mb-2">
                        <input type="text" class="form-control progressbar" aria-describedby="fileHelp" placeholder="No image uploaded..." readonly="readonly" />
                        <div class="progress mb-2 d-none">
                           <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                              role="progressbar"
                              style="width: 0%;"
                              aria-valuenow="0" aria-valuemin="0" aria-valuemax="0">
                              0%
                           </div>
                        </div>
                     </div>
                     <div class="mt-4">
                        <div role="button" class="btn btn-primary mr-2">
                           <i class="fa fa-folder-o fa-fw"></i> Browse Files
                           <input type="file" title='Click to add Files' />
                        </div>
                        <small class="status text-muted">Select a file or drag it over this area..</small>
                        <p class="em text-danger mb-0" id="errservice_image"></p>
                     </div>
                  </div>
               </div>
            </form>
            <form id="ajaxForm" class="modal-form" action="{{route('admin.service.store')}}" method="POST">
               @csrf
               <input type="hidden" id="image" name="" value="">
               <div class="form-group">
                  <label for="">Language **</label>
                  <select id="language" name="language_id" class="form-control">
                     <option value="" selected disabled>Select a language</option>
                     @foreach ($langs as $lang)
                     <option value="{{$lang->id}}">{{$lang->name}}</option>
                     @endforeach
                  </select>
                  <p id="errlanguage_id" class="mb-0 text-danger em"></p>
               </div>
               <div class="form-group">
                  <label for="">Title **</label>
                  <input type="text" class="form-control" name="title" placeholder="Enter title" value="">
                  <p id="errtitle" class="mb-0 text-danger em"></p>
               </div>
               <div class="form-group">
                  <label for="">Category **</label>
                  <select id="scategory" class="form-control" name="category" disabled>
                     <option value="" selected disabled>Select a category</option>
                  </select>
                  <p id="errcategory" class="mb-0 text-danger em"></p>
               </div>
               <div class="form-group">
                  <label for="">Content **</label>
                  <textarea class="form-control summernote" name="content" data-height="300" placeholder="Enter content"></textarea>
                  <p id="errcontent" class="mb-0 text-danger em"></p>
               </div>
               <div class="form-group">
                  <label for="">Serial Number **</label>
                  <input type="number" class="form-control ltr" name="serial_number" value="" placeholder="Enter Serial Number">
                  <p id="errserial_number" class="mb-0 text-danger em"></p>
                  <p class="text-warning"><small>The higher the serial number is, the later the service will be shown everywhere.</small></p>
               </div>
               <div class="form-group">
                  <label>Meta Keywords</label>
                  <input class="form-control" name="meta_keywords" value="" placeholder="Enter meta keywords" data-role="tagsinput">
                  <p id="errmeta_keywords" class="mb-0 text-danger em"></p>
               </div>
               <div class="form-group">
                  <label>Meta Description</label>
                  <textarea class="form-control" name="meta_description" rows="5" placeholder="Enter meta description"></textarea>
                  <p id="errmeta_description" class="mb-0 text-danger em"></p>
               </div>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button id="submitBtn" type="button" class="btn btn-primary">Submit</button>
         </div>
      </div>
   </div>
</div>
@endsection
@section('scripts')
<script>
   $(document).ready(function() {
       $("select[name='language_id']").on('change', function() {

           $("#scategory").removeAttr('disabled');

           let langid = $(this).val();
           let url = "{{url('/')}}/admin/service/" + langid + "/getcats";
           // console.log(url);
           $.get(url, function(data) {
               console.log(data);
               let options = `<option value="" disabled selected>Select a category</option>`;
               for (let i = 0; i < data.length; i++) {
                   options += `<option value="${data[i].id}">${data[i].name}</option>`;
               }
               $("#scategory").html(options);

           });
       });

   });

   // make input fields RTL
   $("select[name='language_id']").on('change', function() {
       $(".request-loader").addClass("show");
       let url = "{{url('/')}}/admin/rtlcheck/" + $(this).val();
       console.log(url);
       $.get(url, function(data) {
           $(".request-loader").removeClass("show");
           if (data == 1) {
               $("form.modal-form input").each(function() {
                   if (!$(this).hasClass('ltr')) {
                       $(this).addClass('rtl');
                   }
               });
               $("form.modal-form select").each(function() {
                   if (!$(this).hasClass('ltr')) {
                       $(this).addClass('rtl');
                   }
               });
               $("form.modal-form textarea").each(function() {
                   if (!$(this).hasClass('ltr')) {
                       $(this).addClass('rtl');
                   }
               });
               $("form.modal-form .summernote").each(function() {
                   $(this).siblings('.note-editor').find('.note-editable').addClass('rtl text-right');
               });

           } else {
               $("form.modal-form input, form.modal-form select, form.modal-form textarea").removeClass('rtl');
               $("form.modal-form .summernote").siblings('.note-editor').find('.note-editable').removeClass('rtl text-right');
           }
       })
   });
</script>
@endsection
