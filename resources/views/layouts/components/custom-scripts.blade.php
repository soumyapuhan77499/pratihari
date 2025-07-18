  <!-- JQuery min js -->
  <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>

  <!-- Bootstrap js -->
  <script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

  <!-- Ionicons js -->
  <script src="{{ asset('assets/plugins/ionicons/ionicons.js') }}"></script>

  <!-- Moment js -->
  <script src="{{ asset('assets/plugins/moment/moment.js') }}"></script>

  <!-- eva-icons js -->
  <script src="{{ asset('assets/plugins/eva-icons/eva-icons.min.js') }}"></script>

  @yield('scripts')

  <!-- generate-otp js -->
  <script src="{{ asset('assets/js/generate-otp.js') }}"></script>

  <!--Internal  Perfect-scrollbar js -->
  <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

  <!-- THEME-COLOR JS -->
  <script src="{{ asset('assets/js/themecolor.js') }}"></script>

  <!-- CUSTOM JS -->
  <script src="{{ asset('assets/js/custom.js') }}"></script>

  <!-- exported JS -->
  <script src="{{ asset('assets/js/exported.js') }}"></script>

  <script>
      // Click image to open file input
      document.getElementById('photoTrigger').addEventListener('click', function() {
          document.getElementById('photoInput').click();
      });

      // Update preview and auto-submit form
      document.getElementById('photoInput').addEventListener('change', function(event) {
          if (event.target.files.length > 0) {
              const file = event.target.files[0];
              const previewUrl = URL.createObjectURL(file);
              document.getElementById('profilePreview').src = previewUrl;

              // Optional delay to show preview before upload
              setTimeout(() => {
                  document.getElementById('photoUploadForm').submit();
              }, 300);
          }
      });
  </script>
