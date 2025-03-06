        $(document).ready(function() {
            // Show spouse details when "Married" is selected
            $('input[name="marital_status"]').change(function() {
                if ($('#married').is(':checked')) {
                    $('#spouseDetails').slideDown();
                    $('#spouseDetail').slideDown();

                } else {
                    $('#spouseDetails').slideUp();
                    $('#spouseDetail').slideUp();

                }
            });

            // Function to add a new child entry
            $('#addChild').click(function() {
                let childIndex = $('.child-row').length + 1;
                let childHtml = `
                <div class="row child-row mt-3 border p-3 rounded bg-light">
                    <div class="col-md-4">
                        <label class="form-label"><i class="fa fa-user" style="color: #e96a01"></i> Child Name</label>
                        <input type="text" class="form-control" name="children[${childIndex}][name]" placeholder="Enter Child's Name">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="fa fa-calendar" style="color: #e96a01"></i> Date of Birth</label>
                        <input type="date" class="form-control" name="children[${childIndex}][dob]">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label"><i class="fa fa-venus-mars" style="color: #e96a01"></i> Gender</label>
                        <select class="form-control" name="children[${childIndex}][gender]">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label"><i class="fa fa-camera" style="color: #e96a01"></i> Photo</label>
                        <input type="file" class="form-control" name="children[${childIndex}][photo]">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger removeChild"><i class="fa fa-trash-alt"></i></button>
                    </div>
                </div>`;
                $('#childrenContainer').append(childHtml);
            });

            // Remove child entry
            $(document).on('click', '.removeChild', function() {
                $(this).closest('.child-row').remove();
            });
        });
 
        function handleFamilySelection(type) {
            const select = document.getElementById(`${type}_name_select`);
            const selectedOption = select.options[select.selectedIndex];
            const selectedValue = select.value;
            const photoUrl = selectedOption.getAttribute('data-photo');

            const inputDiv = document.getElementById(`${type}_name_input_div`);
            const uploadDiv = document.getElementById(`${type}_photo_upload_div`);
            const previewDiv = document.getElementById(`${type}_photo_preview_div`);
            const previewImg = document.getElementById(`${type}_photo_preview`);
            const previewLink = document.getElementById(`${type}_photo_link`);

            if (selectedValue === 'other') {
                // Show input and upload fields for new entry
                inputDiv.style.display = 'block';
                uploadDiv.style.display = 'block';
                previewDiv.style.display = 'none';
                previewImg.src = '';
                previewLink.href = '#';
            } else if (selectedValue) {
                // Show photo preview for existing selection
                inputDiv.style.display = 'none';
                uploadDiv.style.display = 'none';
                previewDiv.style.display = 'block';
                previewImg.src = photoUrl;
                previewLink.href = photoUrl; // Set link to photo URL
            } else {
                // Reset everything if no valid option selected
                inputDiv.style.display = 'none';
                uploadDiv.style.display = 'none';
                previewDiv.style.display = 'none';
                previewImg.src = '';
                previewLink.href = '#';
            }
        }

        // Attach event listeners
        document.getElementById('father_name_select').addEventListener('change', function() {
            handleFamilySelection('father');
        });

        document.getElementById('mother_name_select').addEventListener('change', function() {
            handleFamilySelection('mother');
        });


    let cropper;
    
    document.getElementById('profile_photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const image = document.getElementById('cropperImage');
                image.src = event.target.result;
    
                const cropperModal = new bootstrap.Modal(document.getElementById('cropperModal'));
                cropperModal.show();
    
                cropperModal._element.addEventListener('shown.bs.modal', function() {
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 2,
                        autoCropArea: 1,
                    });
                }, { once: true });
            };
            reader.readAsDataURL(file);
        }
    });
    
    document.getElementById('cropImageBtn').addEventListener('click', function() {
        const croppedCanvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300,
        });
    
        croppedCanvas.toBlob(function(blob) {
            const file = new File([blob], 'profile_photo.jpg', { type: 'image/jpeg' });
    
            // Create a temporary DataTransfer object to replace the file input (optional approach if you want)
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
    
            const fileInput = document.getElementById('profile_photo');
            fileInput.files = dataTransfer.files;
    
            // OR convert cropped image to base64 if you want to stick to base64 flow
            const base64 = croppedCanvas.toDataURL('image/jpeg');
            document.getElementById('cropped_profile_photo').value = base64;
    
            // Close the modal
            bootstrap.Modal.getInstance(document.getElementById('cropperModal')).hide();
        }, 'image/jpeg');
    });
