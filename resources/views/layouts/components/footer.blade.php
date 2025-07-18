			<!-- Footer opened -->
			<div class="main-footer">
				<div class="container-fluid pd-t-0-f ht-100p">
					  Developed By <a href="javascript:void(0);" class="text-primary">33 Crores</a>.  
				</div>
			</div>
			<!-- Footer closed -->

<script>
    // Click image to open file input
    document.getElementById('photoTrigger').addEventListener('click', function () {
        document.getElementById('photoInput').click();
    });

    // Update preview and auto-submit form
    document.getElementById('photoInput').addEventListener('change', function (event) {
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