@include('admin.layouts.partials.modal.logout')
<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/jquery-3.6.1.min.js') }} "></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.39/moment-timezone-with-data-10-year-range.js"></script>
@stack('after-scripts')
<!-- end main wrapper -->
<script type="text/javascript">
    $("#btn-logout").click(function() {
        window.location.href = "/admin/logout";
    });
</script>