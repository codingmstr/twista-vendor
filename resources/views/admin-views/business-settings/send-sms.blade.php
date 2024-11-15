@extends('layouts.admin.app')

@section('title',translate('messages.send_sms'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title mr-3">
                <span class="page-header-icon">
                    <img src="{{asset('assets/admin/img/sms.png')}}" class="w--26" alt="">
                </span>
                <span>
                     {{translate("messages.send_sms")}}
                </span>
            </h1>
        </div>
        <!-- Page Header -->
        <div class="card gx-2 gx-lg-3">
            <div class="card-body">
                <form action="{{route('admin.business-settings.do-send-sms')}}" method="post" enctype="multipart/form-data" id="send_sms">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6 col-12">
                            <div class="form-group">
                                <label class="input-label" for="customer">{{translate('messages.customers')}}
                                  
                                </label>
                                <select id='customer' name="customer_ids[]" data-placeholder="{{translate('messages.select_customer_by_name_or_phone')}}" class="js-data-example-ajax form-control" >

                                </select>
                            </div>
                        </div>
                        
                         <div class="col-sm-6 col-12">
                            <div class="form-group">
                                <label class="input-label" for="vendor">{{translate('messages.vendors')}}
                                  
                                </label>
                                <select id='vendor' name="vendor_ids[]" data-placeholder="{{translate('messages.select_vendor_by_name_or_phone')}}" class="js-data-example-ajax2 form-control" >

                                </select>
                            </div>
                        </div>
                      
                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label" for="referance">{{translate('messages.message')}}</label>

                                <input type="text" placeholder="{{ translate('message') }}" class="form-control" name="message" id="message">
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" id="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                    </div>
                </form>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

<style>
    
   .select2-selection--multiple{
       min-height:41px !important;
        height:auto !important;
    }
</style>

@push('script_2')
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });


            $('#column3_search').on('change', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script>

        $('#send_sms').on('submit', function (e) {

            e.preventDefault();
            var formData = new FormData(this);

            Swal.fire({
                title: '{{translate('messages.are_you_sure')}}',
                text: '{{translate('messages.send_sms')}}',
                type: 'info',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: 'primary',
                cancelButtonText: '{{translate('messages.no')}}',
                confirmButtonText: '{{translate('messages.yes')}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.post({
                        url: '{{route('admin.business-settings.do-send-sms')}}',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType:"JSON",
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        success: function (data) {
                              $('#loading').hide();
                            if (data.error) {
                                    toastr.error(data.error, {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
                            } else {
                                toastr.success('{{translate("messages.send_sms_successfully")}}', {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000);

                            }
                        },
                        error:function(){
                               $('#loading').hide();
                        }
                    });
                }
            })
        })

        $('.js-data-example-ajax').select2({
             multiple: true,
            ajax: {
                url: '{{route('admin.users.customer.select-list')}}',
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                    results: data
                    };
                },
                __port: function (params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });
        
        
          $('.js-data-example-ajax2').select2({
             multiple: true,
            ajax: {
                url: '{{route('admin.users.vendor.select-list')}}',
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                    results: data
                    };
                },
                __port: function (params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });
    </script>
@endpush
