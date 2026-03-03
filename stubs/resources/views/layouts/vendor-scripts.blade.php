<!-- JAVASCRIPT -->
<script src="{{ URL::asset('/assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/metismenu/metismenu.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/node-waves/node-waves.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/waypoints/waypoints.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/jquery-counterup/jquery-counterup.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/custom/toastr.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/custom/notificationToastCustom.js') }}"></script>

<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-switch/bootstrap-switch.min.js') }}"></script>

<script>

      /* only Number Allowed */
        $(".onlyNumber").on("keypress keyup blur", function(event) {
            $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
        
</script>

@yield('script')

<!-- App js -->
<script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>

@yield('script-bottom')
