// var myDropzone2 = null;
Dropzone.options.myAwesomeDropzone = {

    // Dropzone configuration
    autoProcessQueue: true,
    addRemoveLinks: false,
    uploadMultiple: true,
    parallelUploads: 100,
    maxFiles: 20,
    previewsContainer: '#dropzone-previews',
    // clickable:'#dropzone-previews',
    acceptedFiles: ".jpeg,.jpg,.png,.gif,.bmp",
    maxFilesize: 2,

    // The setting up of the dropzone
    init: function() {
        myDropzone = this;

        myDropzone.on("addedfile", function(file) {
            $( '#uploadMsg' ).hide();
        });

        myDropzone.on("maxfilesexceeded", function(file) {
            $( '#uploadMsg' ).append('<h4>Max amount of files exceeded. Only '+maxFiles+' files can be uploaded at once.</h4>');
        });


        // First change the button to actually tell Dropzone to process the queue.
        $("#sbmtbtn").on('click',function(e) {
          // Make sure that the form isn't actually being sent.
              e.preventDefault();
              e.stopPropagation();
              myDropzone.processQueue();
        });

        // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
        // of the sending event because uploadMultiple is set to true.
        this.on("sendingmultiple", function() {
          // Gets triggered when the form is actually being sent.
          // Hide the success button or the complete form.
          console.log('sendingmultiple')
        });
        this.on("successmultiple", function(files, response) {
            console.log('successmultiple')
          // Gets triggered when the files have successfully been sent.
          // Redirect user or notify of success.
          setTimeout(removeFiles, 500)
          console.log('removeFiles should be called soon')
          freshLibraryImages = response.images
        });
        this.on("errormultiple", function(files, response) {
          // alert('error');
          // Gets triggered when there was an error sending the files.
          // Maybe show form again, and notify user of error
        });
    }
}

Dropzone.options.galleryUpload = {
    // Dropzone configuration
    autoProcessQueue: true,
    addRemoveLinks: false,
    uploadMultiple: true,
    parallelUploads: 100,
    maxFiles: 20,
    previewsContainer: '#galleryUploadPreviews',
    // clickable:'#dropzone-previews',
    acceptedFiles: ".jpeg,.jpg,.png,.gif,.bmp",
    maxFilesize: 2,

    // The setting up of the dropzone
    init: function() {
        myDropzone2 = this;

        myDropzone2.on("addedfile", function(file) {
            $( '#galleryUploadMessage' ).hide();
        });

        myDropzone2.on("maxfilesexceeded", function(file) {
            $( '#galleryUploadMessage' ).append('<h4>Max amount of files exceeded. Only '+maxFiles+' files can be uploaded at once.</h4>');
        });


        // First change the button to actually tell Dropzone to process the queue.
        $("#sbmtbtn").on('click',function(e) {
          // Make sure that the form isn't actually being sent.
              e.preventDefault();
              e.stopPropagation();
              myDropzone2.processQueue();
        });

        // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
        // of the sending event because uploadMultiple is set to true.
        this.on("sendingmultiple", function() {
          // Gets triggered when the form is actually being sent.
          // Hide the success button or the complete form.
          console.log('sendingmultiple')
        });
        this.on("successmultiple", function(files, response) {
            console.log('successmultiple')
          // Gets triggered when the files have successfully been sent.
          // Redirect user or notify of success.
          setTimeout(removeFiles, 500)
          console.log('removeFiles should be called soon')
          freshLibraryImages = response.images
        });
        this.on("errormultiple", function(files, response) {
          // alert('error');
          // Gets triggered when there was an error sending the files.
          // Maybe show form again, and notify user of error
        });
    }
}
